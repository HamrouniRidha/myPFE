<?php

namespace App\Form;

use App\Entity\Stage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class StageNotesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('sujet')
            // ->add('date_debut')
            // ->add('date_fin')
            // ->add('documentFileName')
            ->add('note1')
            ->add('note2')
            ->add('note3')
            ->add('note4')
            ->add('note5');
        // ->add('moyenne');
        // ->add('etat')
        // ->add('type')
        // ->add('encadrant')
        // ->add('historique')
        // ->add('departement');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}
