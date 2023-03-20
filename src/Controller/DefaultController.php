<?php

namespace App\Controller;

use App\Repository\HistoricsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractController
{
    #[Route('/apip/user_connect', name: 'user_connect')]
    public function getUserByConnect(SerializerInterface $serializer): JsonResponse
    {
        $userConnected = $this->getUser();
        $jsonUser = $serializer->serialize($userConnected, 'json');

        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    #[Route('/apip/historics_users', name: 'historicsByUser')]
    public function getUserTest(SerializerInterface $serializer, HistoricsRepository $historics): JsonResponse
    {
        $userConnected = $this->getUser();
        $idUser = $userConnected->getId();

        $listHistorics = $historics->findBy(
            ["id_user" => "$idUser"],
            ["history_date" => "desc"
        ],
        );

        $jsonUser = $serializer->serialize($listHistorics, 'json');

        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }
}
