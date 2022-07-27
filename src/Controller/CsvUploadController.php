<?php

namespace App\Controller;

use App\Entity\CsvFile;
use App\Form\CsvUploadType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CsvUploadController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/csv/upload", name="app_csv_new")
     */
    public function __invoke(Request $request, SluggerInterface $slugger)
    {
        $csvFile = new CsvFile();
        $form = $this->createForm(CsvUploadType::class, $csvFile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var null|UploadedFile $uploadedCsvFile */
            $uploadedCsvFile = $form->get('csv_file')->getData();

            if ($uploadedCsvFile) {
                $originalFilename = pathinfo($uploadedCsvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid('', true).'.'.$uploadedCsvFile->guessExtension();

                try {
                    $uploadedCsvFile->move(
                        $this->getParameter('csv_files_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $csvFile->setCsvFilename($newFilename);

                $this->entityManager->persist($csvFile);
                $this->entityManager->flush();
            }

            return $this->redirectToRoute('app_csv_process', ['id' => $csvFile->getId(), 'check_type' => $form->get('TypeOfCheck')->getData()]);
        }

        return $this->render('csv_file/upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
