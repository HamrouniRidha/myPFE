<?php

namespace App\Form;

use App\Entity\Etablissement;
use App\Entity\Historique;
use App\Entity\Stagiaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HistoriqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentYear = (int)date('Y');

        $builder

            ->add('annee', IntegerType::class, [
                'data' => $currentYear, // Définir la valeur par défaut à l'année en cours
                'disabled' => false

            ])
            ->add('stagiaire', EntityType::class, [
                'class' => Stagiaire::class,
                'choice_label' => function (Stagiaire $stagiaire) {
                    return sprintf('%s',  $stagiaire->getCin());
                },
                'placeholder' => 'Sélectionner un stagiaire',
            ])
            ->add('etablissement', EntityType::class, [
                'class' => Etablissement::class,
                'choice_label' => function (Etablissement $etablissement) {
                    return sprintf('%s - %s', $etablissement->getNom(), $etablissement->getLieu());
                },
                'placeholder' => 'Sélectionner un établissement',
            ])
            ->add('certificat', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Historique::class,
        ]);
    }
}
