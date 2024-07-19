<?php

namespace App\Controller;

use Dompdf\Dompdf;
use App\Entity\Stage;
use App\Form\ValidateType;
use App\Form\RapportType;

use App\Form\StageType;
use App\Entity\Stagiaire;
use App\Entity\Historique;
use App\Service\PdfService;
use App\Form\StageNotesType;
use App\Form\SearchStageType;
use Doctrine\ORM\EntityManager;
use App\Repository\StageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;




class FirstController extends AbstractController
{
    #[Route('/template', name: 'template')]
    public function template(): Response
    {
        return $this->render(view: 'template.html.twig');
    }
    #[Route('/generateCertificate', name: 'generate_certificate', methods: ['POST'])]
    public function generateCertificate(Request $request): Response
    {
        // Récupérer les données du formulaire JSON envoyées depuis le frontend
        $data = json_decode($request->getContent(), true);

        // Vérifier si les données requises sont présentes
        if (
            !isset($data['nom']) ||
            !isset($data['prenom']) ||
            !isset($data['sujet']) ||
            !isset($data['dateDebut']) ||
            !isset($data['dateFin'])
        ) {
            return new Response('Données manquantes.', Response::HTTP_BAD_REQUEST);
        }

        // Rendre le template Twig en utilisant les données du formulaire
        $html = $this->renderView('pdf/attestation.html.twig', [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'sujet' => $data['sujet'],
            'dateDebut' => $data['dateDebut'],
            'dateFin' => $data['dateFin'],
        ]);

        // Générer le PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Renvoyer le PDF en réponse
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="attestation.pdf"');

        return $response;
    }

    #[Route('/stage', name: 'stage')]
    public function stage(StageRepository $stageRepository, Request $request, EntityManagerInterface $em): Response
    {
        $search = $request->query->get('search');
        $criteria = $request->query->get('criteria');

        if ($search && $criteria) {
            switch ($criteria) {
                case 'sujet':
                    $stages = $stageRepository->findBySujet($search);
                    break;

                case 'type':
                    $stages = $stageRepository->findByType($search);
                    break;
                default:
                    $stages = $stageRepository->findAll();
                    break;
            }
        } else {
            $stages = $stageRepository->findAll();
        }


        return $this->render('stage.html.twig', [
            'stages' => $stages,
        ]);
    }
    #[Route('/updateStageState/{id<[0-9]+>}', name: 'updateStageState')]
    public function pdateStageState(EntityManager $entityManager, Request $request, $stageId)
    {
        $stage = $entityManager->getRepository(Stage::class)->find($stageId);

        if (!$stage) {
            throw $this->createNotFoundException('Stage not found');
        }

        $data = json_decode($request->getContent(), true);
        $etat = $data['etat'];

        // Vérifier si l'état fourni est valide
        if (!in_array($etat, ['Accepté', 'Refusé', 'En cours'])) {
            return new Response('État invalide.', Response::HTTP_BAD_REQUEST);
        }

        // Mettre à jour l'état du stage
        $stage->setEtat($etat);
        $entityManager->flush();

        return new Response('État du stage mis à jour avec succès', Response::HTTP_OK);
    }



    #[Route('/note', name: 'note')]
    public function note(StageRepository $stageRepository, Request $request, EntityManagerInterface $em): Response
    {
        $search = $request->query->get('search');
        $criteria = $request->query->get('criteria');

        if ($search && $criteria) {
            switch ($criteria) {
                case 'sujet':
                    $stages = $stageRepository->findBySujet($search);
                    break;

                case 'type':
                    $stages = $stageRepository->findByType($search);
                    break;
                default:
                    $stages = $stageRepository->findAll();
                    break;
            }
        } else {
            $stages = $stageRepository->findAll();
        }

        return $this->render('note.html.twig', [
            'stages' => $stages,
        ]);
    }
    #[Route('/rapport', name: 'rapport')]
    public function rapport(StageRepository $stageRepository, Request $request, EntityManagerInterface $em): Response
    {
        $search = $request->query->get('search');
        $criteria = $request->query->get('criteria');

        if ($search && $criteria) {
            switch ($criteria) {
                case 'sujet':
                    $stages = $stageRepository->findBySujet($search);
                    break;

                case 'type':
                    $stages = $stageRepository->findByType($search);
                    break;
                default:
                    $stages = $stageRepository->findAll();
                    break;
            }
        } else {
            $stages = $stageRepository->findAll();
        }

        return $this->render('rapport.html.twig', [
            'stages' => $stages,
        ]);
    }
    #[Route('/affecte', name: 'affecte')]
    public function affecte(StageRepository $stageRepository, Request $request, EntityManagerInterface $em): Response
    {
        $search = $request->query->get('search');
        $criteria = $request->query->get('criteria');

        if ($search && $criteria) {
            switch ($criteria) {
                case 'sujet':
                    $stages = $stageRepository->findBySujet($search);
                    break;

                case 'type':
                    $stages = $stageRepository->findByType($search);
                    break;
                default:
                    $stages = $stageRepository->findAll();
                    break;
            }
        } else {
            $stages = $stageRepository->findAll();
        }

        return $this->render('affecte.html.twig', [
            'stages' => $stages,
        ]);
    }
    #[Route('/refuse', name: 'refuse')]
    public function refuse(StageRepository $stageRepository, Request $request, EntityManagerInterface $em): Response
    {
        $search = $request->query->get('search');
        $criteria = $request->query->get('criteria');

        if ($search && $criteria) {
            switch ($criteria) {
                case 'sujet':
                    $stages = $stageRepository->findBySujet($search);
                    break;

                case 'type':
                    $stages = $stageRepository->findByType($search);
                    break;
                default:
                    $stages = $stageRepository->findAll();
                    break;
            }
        } else {
            $stages = $stageRepository->findAll();
        }

        return $this->render('refuse.html.twig', [
            'stages' => $stages,
        ]);
    }



    // ...




    #[Route('/addStage', name: 'addStage')]
    public function addStage(Request $request, EntityManagerInterface $em, #[Autowire('%photo_dir%')] string $photoDir): Response
    {
        $stage = new Stage();
        $stage->setDateDebut(new \DateTime());
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $stage = $form->getData();
            if ($photos = $form['photo']->getData()) {
                $fileNameArray = [];
                foreach ($photos as $key => $photo) {
                    $filename = uniqid() . '.' . $photo->guessExtension();
                    $photo->move($photoDir, $filename);
                    array_push($fileNameArray, $filename);
                }
                $stage->setDocumentFileName($fileNameArray);
            }




            $em->persist($stage);
            $em->flush();
            $this->addFlash('success', 'Stage ajouté avec succès.'); // Add success flash message

            return $this->redirectToRoute('stage');
        }

        return $this->render('addStage.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/addRapport', name: 'addRapport')]
    public function addRapport(Request $request, EntityManagerInterface $em, #[Autowire('%photo_dir%')] string $photoDir): Response
    {
        $stage = new Stage();
        $stage->setDateDebut(new \DateTime());
        $form = $this->createForm(RapportType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $stage = $form->getData();
            if ($photos = $form['photo']->getData()) {
                $fileNameArray = [];
                foreach ($photos as $key => $photo) {
                    $filename = uniqid() . '.' . $photo->guessExtension();
                    $photo->move($photoDir, $filename);
                    array_push($fileNameArray, $filename);
                }
                $stage->setDocumentFileName($fileNameArray);
            }




            $em->persist($stage);
            $em->flush();
            $this->addFlash('success', 'Rapport ajouté avec succès.'); // Add success flash message

            return $this->redirectToRoute('rapport');
        }

        return $this->render('add_rapport.html.twig', [
            'formRapport' => $form->createView(),
        ]);
    }
    #[Route('/addValidate', name: 'addValidate')]
    public function addValidate(Request $request, EntityManagerInterface $em, #[Autowire('%photo_dir%')] string $photoDir): Response
    {
        $stage = new Stage();
        $stage->setDateDebut(new \DateTime());
        $form = $this->createForm(ValidateType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $stage = $form->getData();
            if ($photos = $form['photo']->getData()) {
                $fileNameArray = [];
                foreach ($photos as $key => $photo) {
                    $filename = uniqid() . '.' . $photo->guessExtension();
                    $photo->move($photoDir, $filename);
                    array_push($fileNameArray, $filename);
                }
                $stage->setDocumentFileName($fileNameArray);
            }



            $em->persist($stage);
            $em->flush();
            $this->addFlash('success', 'Stage ajouté avec succès.'); // Add success flash message

            return $this->redirectToRoute('stage');
        }

        return $this->render('addValidate.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/validateStage', name: 'validate')]
    public function validate(StageRepository $stageRepository, Request $request, EntityManagerInterface $em): Response
    {
        $search = $request->query->get('search');
        $criteria = $request->query->get('criteria');

        if ($search && $criteria) {
            switch ($criteria) {
                case 'sujet':
                    $stages = $stageRepository->findBySujet($search);
                    break;

                case 'type':
                    $stages = $stageRepository->findByType($search);
                    break;
                default:
                    $stages = $stageRepository->findAll();
                    break;
            }
        } else {
            $stages = $stageRepository->findAll();
        }

        return $this->render('validate.html.twig', [
            'stages' => $stages,
        ]);
    }

    #[Route('/generateCertificate', name: 'generate_certificate', methods: ['POST'])]
    public function generateeCertificate(Request $request): Response
    {
        // Récupérer les données du formulaire JSON envoyées depuis le frontend
        $data = json_decode($request->getContent(), true);

        // Vérifier si les données requises sont présentes
        if (
            !isset($data['nom']) ||
            !isset($data['prenom']) ||
            !isset($data['sujet']) ||
            !isset($data['dateDebut']) ||
            !isset($data['dateFin'])
        ) {
            return new Response('Données manquantes.', Response::HTTP_BAD_REQUEST);
        }

        // Rendre le template Twig en utilisant les données du formulaire
        $html = $this->renderView('pdf/attestation.html.twig', [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'sujet' => $data['sujet'],
            'dateDebut' => $data['dateDebut'],
            'dateFin' => $data['dateFin'],
        ]);

        // Générer le PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Renvoyer le PDF en réponse
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="attestation.pdf"');

        return $response;
    }
    #[Route('/pdf/{id}', name: 'stage.pdf')]

    public function generatePdfStage(Stage $stage = null, PdfService $pdf)
    {
    }
    #[Route('/stage/{id<[0-9]+>}/edit', name: 'stage_edit')]
    public function edit(Stage $stage, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(StageType::class, $stage);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Le stage a été modifié.');
            return $this->redirectToRoute('stage');
        }
        return $this->render('edit.html.twig', ['formStage' => $form]);
    }
    #[Route('/stage/{id<[0-9]+>}/show', name: 'stage_show')]
    public function show(Stage $stage, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        return $this->render('show.html.twig', ['formStage' => $form]);
    }



    #[Route('/stage/{id<[0-9]+>}/delete', name: 'delete')]
    public function delete(Stage $stage, EntityManagerInterface $em)
    {
        $em->remove($stage);
        $em->flush();
        $this->addFlash('success', 'Le stage a été supprimé.');
        return $this->redirectToRoute('stage');
    }
    #[Route('/stage/{id<[0-9]+>}/notes', name: 'stage_notes')]

    public function addNotes(Request $request, EntityManagerInterface $entityManager, Stage $stage): Response
    {
        $form = $this->createForm(StageNotesType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour la moyenne générale
            $stage->setMoyenne($stage->calculateAverage());

            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // Rediriger vers une autre page ou afficher un message de succès
            return $this->redirectToRoute('stage', ['id' => $stage->getId()]);
        }

        return $this->render('add_notes.html.twig', [
            'formNotes' => $form->createView(),
        ]);
    }
    #[Route('/updateStageState/{id<[0-9]+>}', name: 'updateStageState')]

    public function updateStageState(Request $request, $stageId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $stage = $entityManager->getRepository(Stage::class)->find($stageId);

        if (!$stage) {
            throw $this->createNotFoundException('Stage not found');
        }

        $data = json_decode($request->getContent(), true);
        $etat = $data['etat'];

        // Update the stage state
        $stage->setEtat($etat);
        $entityManager->flush();

        return new Response('Stage state updated successfully', Response::HTTP_OK);
    }


    // ...
}
