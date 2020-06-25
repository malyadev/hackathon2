<?php

namespace App\Form;

use App\Entity\Feature;
use App\Entity\Prescription;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, ['class'=> User::class, 'choice_label'=>'firstname'])
            ->add('prescriptionDrugs', CollectionType::class, [
                'entry_type' => PrescriptionDrugType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prescription::class,
        ]);
    }
}
