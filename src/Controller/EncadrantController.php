<?php

namespace App\Controller;

use App\Entity\Encadrant;
use App\Form\EncadrantType;
use App\Repository\EncadrantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EncadrantController extends AbstractController
{
    // ...



    #[Route('/encadrant', name: 'app_encadrant')]
    public function index(Request $request, EncadrantRepository $encadrantRepository): Response
    {
        $search = $request->query->get('search');
        $criteria = $request->query->get('criteria');

        // Si des critères de recherche sont fournis, on filtre les encadrants correspondants
        if ($search && $criteria) {
            switch ($criteria) {
                case 'matricule':
                    $encadrants = $encadrantRepository->findBy(['matricule' => $search]);
                    break;
                case 'nom':
                    $encadrants = $encadrantRepository->findBy(['nom' => $search]);
                    break;
                case 'prenom':
                    $encadrants = $encadrantRepository->findBy(['prenom' => $search]);
                    break;
                default:
                    $encadrants = $encadrantRepository->findAll();
                    break;
            }
        } else {
            $encadrants = $encadrantRepository->findAll();
        }

        return $this->render('encadrant/index.html.twig', [
            'encadrants' => $encadrants,
        ]);
    }

    // ...



    #[Route('/encadrant/create', name: 'encadrant_create')]
    public function create(Request $request, EntityManagerInterface $em, EncadrantRepository $encadrantRepository)
    {
        $encadrant = new Encadrant();
        $form = $this->createForm(EncadrantType::class, $encadrant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si un encadrant avec le même matricule existe déjà
            $existingEncadrant = $encadrantRepository->findOneBy(['matricule' => $encadrant->getMatricule()]);
            if ($existingEncadrant) {
                $form->get('matricule')->addError(new FormError('Un encadrant avec le même matricule existe déjà.'));
            } else {
                $em->persist($encadrant);
                $em->flush();
                $this->addFlash('success', 'L\'encadrant  ajouté avec succès.');
                return $this->redirectToRoute('encadrant_create');
            }
        }

        return $this->render('encadrant/create.html.twig', [
            'form_encadrant' => $form->createView(),
        ]);
    }

    // Les autres méthodes du contrôleur (edit, show, delete) restent inchangées
    // ...
}
