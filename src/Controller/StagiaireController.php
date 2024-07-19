<?php

namespace App\Controller;

use App\Entity\Stagiaire;

use App\Entity\Historique;
use App\Form\StagiaireType;
use App\Entity\Etablissement;
use Symfony\Component\Form\FormError;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class StagiaireController extends AbstractController
{


    #[Route('/stagiaire', name: 'app_stagiaire')]



    // ...

    public function index(Request $request, StagiaireRepository $stagiaireRepository): Response
    {
        $search = $request->query->get('search');
        $criteria = $request->query->get('criteria');

        if ($search && $criteria) {
            switch ($criteria) {
                case 'cin':
                    $stagiaires = $stagiaireRepository->findByCin($search);
                    break;
                case 'nom':
                    $stagiaires = $stagiaireRepository->findByNom($search);
                    break;
                case 'prenom':
                    $stagiaires = $stagiaireRepository->findByPrenom($search);
                    break;
                default:
                    $stagiaires = $stagiaireRepository->findAll();
                    break;
            }
        } else {
            $stagiaires = $stagiaireRepository->findAll();
        }

        return $this->render('templates/stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires,
        ]);
    }


    #[Route('/stagiaire/create', name: 'stagiaire_create')]
    public function create(Request $request, EntityManagerInterface $em, StagiaireRepository $stagiaireRepository)
    {
        $stagiaire = new Stagiaire;
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cin = $stagiaire->getCin();

            // Vérifier si un stagiaire avec le même CIN existe déjà
            $existingStagiaire = $stagiaireRepository->findOneBy(['cin' => $cin]);
            if ($existingStagiaire) {
                $form->get('cin')->addError(new FormError('Un stagiaire avec le même CIN existe déjà.'));
                $this->addFlash('danger', 'Le stagiaire avec le CIN ' . $cin . ' existe déjà.');
            } else {
                $em->persist($stagiaire);
                $em->flush();
                $this->addFlash('success', 'Stagiaire ajouté avec succès.');

                return $this->redirectToRoute('stagiaire_create');
            }
        }

        return $this->render(
            'stagiaire/create.html.twig',
            ['form_stagiaire' => $form->createView()]
        );
    }



    #[Route('/stagiaire/{id<[0-9]+>}/edit', name: 'stagiaire_edit')]
    public function edit(Stagiaire $stagiaire, Request $request, EntityManagerInterface $em, StagiaireRepository $stagiaireRepository)
    {
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si un stagiaire avec le même CIN existe déjà
            $existingStagiaire = $stagiaireRepository->findOneBy(['cin' => $stagiaire->getCin()]);
            if ($existingStagiaire && $existingStagiaire !== $stagiaire) {
                $form->get('cin')->addError(new FormError('Un stagiaire avec le même CIN existe déjà.'));
            } else {
                $em->flush();
                return $this->redirectToRoute('app_stagiaire');
            }
        }

        return $this->render('stagiaire/edit.html.twig', ['form_stagiaire' => $form->createView()]);
    }


    #[Route('/stagiaire/{id<[0-9]+>}/show', name: 'stagiaire_show')]
    public function show(Stagiaire $stagiaire, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(StageType::class, $stagiaire);
        $form->handleRequest($request);

        return $this->render('stagiaire/show.html.twig', ['form_stagiaire' => $form]);
    }

    #[Route('/stagiaire/{id<[0-9]+>}/delete', name: 'stagiaire_delete')]
    public function delete(Stagiaire $stagiaire, EntityManagerInterface $em)
    {
        $em->remove($stagiaire);
        $em->flush();
        $this->addFlash('success', 'Le stagiaire a été supprimé.');
        return $this->redirectToRoute('app_stagiaire');
    }
}
