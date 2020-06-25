<?php

namespace App\Form;

use App\Entity\PrescriptionDrug;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrescriptionDrugType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('frequency')
            ->add('dose')
            ->add('duration')
            ->add('prescription')
            ->add('drug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PrescriptionDrug::class,
        ]);
    }
}
