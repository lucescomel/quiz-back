<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoricsQuestionsController extends AbstractController
{
    #[Route('/historics/questions', name: 'app_historics_questions')]
    public function index(): Response
    {
        return $this->render('historics_questions/index.html.twig', [
            'controller_name' => 'HistoricsQuestionsController',
        ]);
    }
}
