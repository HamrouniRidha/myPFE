<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Form\DepartementType;
use App\Repository\DepartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DepartementController extends AbstractController
{
    #[Route('/departement', name: 'app_departement')]
    public function index(Request $request, DepartementRepository $departementRepository): Response
    {
        $search = $request->query->get('search');

        if ($search) {
            $departements = $departementRepository->findByDepartement($search);
        } else {
            $departements = $departementRepository->findAll();
        }

        return $this->render('departement/index.html.twig', [
            'departements' => $departements,
        ]);
    }


    #[Route('/departement/create', name: 'departement_create')]
    public function create(Request $request, EntityManagerInterface $em, DepartementRepository $departementRepository)
    {
        $departement = new Departement();
        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si un département avec le même nom existe déjà
            $existingDepartement = $departementRepository->findOneBy(['departement' => $departement->getDepartement()]);
            if ($existingDepartement) {
                $form->get('departement')->addError(new FormError('Un département avec le même nom existe déjà.'));
            } else {
                $em->persist($departement);
                $em->flush();
                $this->addFlash('success', 'Le département a été ajouté avec succès.');
                return $this->redirectToRoute('departement_create');
            }
        }

        return $this->render('departement/create.html.twig', [
            'form_departement' => $form->createView(),
        ]);
    }

    // Les autres méthodes du contrôleur (edit, show, delete) restent inchangées
    // ...
}
