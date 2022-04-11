<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Service;
use App\Entity\Employes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom')
            ->add('nom')
            ->add('telephone')
            ->add('email')
            ->add('adresse')
            ->add('poste')
            ->add('salaire')
            ->add('datedenaissance')
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'name'
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'name'
            ])
            ->add('Enregistrer', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employes::class,
        ]);
    }
}
