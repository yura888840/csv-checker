<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\CsvFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CsvUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csv_file', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        //'maxSize' => '600M',
                        //'mimeTypes' => [
                        //    'text/csv',
                        //],
                        'mimeTypesMessage' => 'Please upload a valid CSV document',
                    ]),
                ],
                'attr' => ['class' => 'form-control form-control-lg', 'accept' => 'text/csv'],
                'label_attr' => ['class' => 'form-label'],
                'row_attr' => ['class' => 'col-auto'],
                'label' => false,
            ])
            ->add('TypeOfCheck', ChoiceType::class, [
                'mapped' => false,
                'choices'  => [
                    'Simple check' => 1,
                    'Extended checks' => 2,
                ],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Process file'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CsvFile::class,
        ]);
    }
}
