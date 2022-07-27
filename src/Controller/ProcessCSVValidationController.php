<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\CsvFile;
use App\Repository\Read\CsvFileRepository;
use App\Service\Validation\Pipeline\PipelineValidator;
use App\Service\Validation\CsvFileValidationService;
use http\Exception\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProcessCSVValidationController extends AbstractController
{
    /**
     * @Route("/csv/process/{id}", name="app_csv_process")
     */
    public function __invoke(
        Request                  $request,
        SluggerInterface         $slugger,
        string                   $id,
        CsvFileValidationService $validationService,
        PipelineValidator        $simpleValidator,
        PipelineValidator        $allStagesValidator,
        CsvFileRepository        $repository
    )  {
        /** @var null|CsvFile $csvFile */
        $csvFile = $repository->findOneBy(['id' => $id]);
        $checkType = (int) $request->get('check_type', 1);

        if ($checkType === 1) {
            $validator = $simpleValidator;
        } else {
            $validator = $allStagesValidator;
        }

        if ($csvFile === null) {
            throw new InvalidArgumentException('Csv Upload doesn\'t exists');
        }

        $filePath = sprintf(
            '%s/%s',
            $this->getParameter('csv_files_directory'),
            $csvFile->getCsvFilename()
        );

        return $this->render('csv_file/process.html.twig',
            $validationService->setPipelineValidator($validator)->validateCSVFile($filePath)
        );
    }
}
