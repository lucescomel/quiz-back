<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoricsController extends AbstractController
{
    #[Route('/historics', name: 'app_historics')]
    public function index(): Response
    {
        return $this->render('historics/index.html.twig', [
            'controller_name' => 'HistoricsController',
        ]);
    }
}
