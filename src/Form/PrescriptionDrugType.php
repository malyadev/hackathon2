<?php

namespace App\Form;

use App\Entity\Drug;
use App\Entity\Prescription;
use App\Entity\PrescriptionDrug;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrescriptionDrugType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('drug', EntityType::class, ['class'=> Drug::class, 'choice_label'=>'name'])
            ->add('frequency')
            ->add('dose')
            ->add('duration')
            ->add('advice')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PrescriptionDrug::class,
        ]);
    }
}
