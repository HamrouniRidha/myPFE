<?php

namespace App\Form;

use App\Entity\Stage;
use App\Entity\Stagiaire;
use App\Entity\Historique;
use App\Entity\Departement;
use App\Entity\Encadrant;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ValidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $stage = $event->getData();
                $form = $event->getForm();
                if ($stage != null && ($stage->getId() == null ||
                    $stage->getHistorique() != null)) {
                    $form->add('historique', HistoriqueType::class);
                }
            })


            ->add('historique', CollectionType::class, [
                'entry_type' => HistoriqueType::class,
                // ’entry_options’ => [’label’ => false],
                'allow_add' => true,
            ])
            ->add('sujet', null, [
                'label' => 'Sujet du stage',
                'required' => true
            ])
            ->add('date_debut', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('date_fin', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'required' => true,
            ])


            // ->add('lieu', null, [
            //     'label' => 'Lieu du stage'
            // ])
            // ->add('stagiaire', null, [
            //     'class' => Stagiaire::class,
            //     'attr' => ['class' => 'select2'],
            //     'label' => 'Stagiaire'
            // ])
            ->add('departement', null, [
                'class' => Departement::class,
                'attr' => ['class' => 'select2'],
                'label' => 'Departement'
            ])
            ->add('encadrant', null, [
                'class' => Encadrant::class,
                'attr' => ['class' => 'select2'],
                'label' => 'Encadrant'
            ])
            ->add('type', null, [
                'label' => 'Type de stage'
            ])


            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'En cours' => 'en cours',
                    'Validé' => 'Accepter',
                    'Refusé' => 'refuser',
                ],
            ])

            ->add('photo', FileType::class, [
                'required' => false,
                'mapped' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '5000k',
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/png',
                                    'application/pdf',
                                    'application/zip',
                                ],
                                'mimeTypesMessage' => 'Please upload a valid image or PDF file',
                            ]),
                        ],
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}
