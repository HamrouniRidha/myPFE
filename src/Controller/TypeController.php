<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TypeController extends AbstractController
{
    #[Route('/type', name: 'app_type')]
    public function index(Request $request, TypeRepository $typeRepository): Response
    {
        $search = $request->query->get('search');

        if ($search) {
            $types = $typeRepository->findByType($search);
        } else {
            $types = $typeRepository->findAll();
        }


        return $this->render('type/index.html.twig', [
            'types' => $types,
        ]);
    }

    #[Route('/type/create', name: 'type_create')]
    public function create(Request $request, EntityManagerInterface $em, TypeRepository $typeRepository)
    {
        $type = new Type;
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si un type avec le même nom existe déjà
            $existingType = $typeRepository->findOneBy(['type' => $type->getType()]);
            if ($existingType) {
                $form->get('type')->addError(new FormError('Un type avec le même nom existe déjà.'));
            } else {
                $em->persist($type);
                $em->flush();
                return $this->redirectToRoute('type_create');
            }
        }

        return $this->render(
            'type/create.html.twig',
            ['form_type' => $form->createView()]
        );
    }

    #[Route('/type/{id<[0-9]+>}/edit', name: 'type_edit')]
    public function edit(Type $type, Request $request, EntityManagerInterface $em, TypeRepository $typeRepository)
    {
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si un type avec le même nom existe déjà
            $existingType = $typeRepository->findOneBy(['type' => $type->getType()]);
            if ($existingType && $existingType !== $type) {
                $form->get('type')->addError(new FormError('Un type avec le même nom existe déjà.'));
            } else {
                $em->flush();
                return $this->redirectToRoute('app_type');
            }
        }

        return $this->render('type/edit.html.twig', ['form_type' => $form->createView()]);
    }

    #[Route('/type/{id<[0-9]+>}/show', name: 'type_show')]
    public function show(Type $type)
    {
        // Afficher les détails du type
        return $this->render('type/show.html.twig', ['type' => $type]);
    }

    #[Route('/type/{id<[0-9]+>}/delete', name: 'type_delete')]
    public function delete(Type $type, EntityManagerInterface $em)
    {
        $em->remove($type);
        $em->flush();
        $this->addFlash('success', 'Le type a été supprimé.');
        return $this->redirectToRoute('app_type');
    }
}
