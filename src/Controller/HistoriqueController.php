<?php

namespace App\Controller;

use App\Entity\Historique;
use App\Form\HistoriqueType;

use App\Repository\HistoriqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HistoriqueController extends AbstractController
{
    #[Route('/historique', name: 'app_historique')]
    public function ajoutHistorique(Request $request, EntityManagerInterface $em): Response
    {
        $hist = new Historique();

        // set properties of $hist based on submitted form data
        $form = $this->createForm(HistoriqueType::class, $hist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // persist the new Historique entity and redirect to a confirmation page
            $em->persist($hist);
            $em->flush();

            return $this->redirectToRoute('app_stagiaire');
        }

        // if the form was not submitted or was not valid, render the form
        return $this->render('templates/historique/index.html.twig', [
            'form_historique' => $form->createView(),
        ]);
    }

    #[Route('/historique/liste', name: 'app_historique_liste')]
    public function listeHistorique(HistoriqueRepository $historiqueRepository): Response
    {
        $historiques = $historiqueRepository->findAll();

        return $this->render('templates/historique/show.html.twig', [
            'historiques' => $historiques,
        ]);
    }
}
