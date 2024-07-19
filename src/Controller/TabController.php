<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TabController extends AbstractController
{
    #[Route('/tab/users', name: 'tab.users')]
    public function users(): Response
    {
        $users = [
            ['Matricule' => '190725', 'firstName' => 'firas', 'Name' => 'boudhief'],
            ['Matricule' => '190726', 'firstName' => 'mohamed', 'Name' => 'ali'],
            ['Matricule' => '190725', 'firstName' => 'hana', 'Name' => 'hadfi'],
        ];
        return $this->render('tab/users.html.twig', [
            'users' => $users
        ]);
    }
}
