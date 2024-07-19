<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Form\EtablissementType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EtablissementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EtablissementController extends AbstractController
{
    #[Route('/etablissement', name: 'app_etablissement')]
    public function index(Request $request, EtablissementRepository $etablissementRepository): Response
    {
        $search = $request->query->get('search');
        $criteria = $request->query->get('criteria');

        if ($search && $criteria) {
            switch ($criteria) {
                case 'nom':
                    $etablissements = $etablissementRepository->findByNom($search);
                    break;
                case 'lieu':
                    $etablissements = $etablissementRepository->findByLieu($search);
                    break;
                case 'type':
                    $etablissements = $etablissementRepository->findByType($search);
                    break;
                default:
                    $etablissements = $etablissementRepository->findAll();
                    break;
            }
        } else {
            $etablissements = $etablissementRepository->findAll();
        }

        return $this->render('etablissement/index.html.twig', [
            'etablissements' => $etablissements,
        ]);
    }

    #[Route('/etablissement/create', name: 'etablissement_create')]
    public function create(Request $request, EntityManagerInterface $em, EtablissementRepository $etablissementRepository)
    {
        $etablissement = new Etablissement();
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si un établissement avec le même nom et lieu existe déjà
            $existingEtablissement = $etablissementRepository->findOneBy(['nom' => $etablissement->getNom(), 'lieu' => $etablissement->getLieu()]);
            if ($existingEtablissement) {
                $form->get('nom')->addError(new FormError('Un établissement avec le même nom et lieu existe déjà.'));
                $form->get('lieu')->addError(new FormError('Un établissement avec le même nom et lieu existe déjà.'));
            } else {
                $em->persist($etablissement);
                $em->flush();
                $this->addFlash('success', 'L\'établissement a été ajouté avec succès.');
                return $this->redirectToRoute('etablissement_create');
            }
        }

        return $this->render('etablissement/create.html.twig', [
            'form_etablissement' => $form->createView(),
        ]);
    }

    // Les autres méthodes du contrôleur (edit, show, delete) restent inchangées
    // ...
}
