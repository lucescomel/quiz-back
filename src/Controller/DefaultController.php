<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\User;
use App\Repository\AnswersRepository;
use App\Repository\CategoriesRepository;
use App\Repository\HistoricsQuestionsRepository;
use App\Repository\HistoricsRepository;
use App\Repository\QuestionsRepository;
use App\Repository\UserRepository;
use App\Utils\Historic;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
    public function getHistoricsAndCategorieByUser(QuestionsRepository $questionsRepository, SerializerInterface $serializer, HistoricsRepository $historics, Historic $historic, HistoricsQuestionsRepository $histquest, CategoriesRepository $categoriesRepository): JsonResponse
    {
        //recupération de l'utilisateur courant
        $userConnected = $this->getUser();
        $idUser = $userConnected->getId();

        //récupération de l'historique suivant l'utilisateur
        $listHistorics = $historics->findBy(
            ["id_user" => "$idUser"],
            ["history_date" => "desc"],
        );
        $listGlobal = [];
        //recupération des historique_question suivant les historique
        $listHistoricsId = [];
        $listHistoriqueQuestion = [];
        //pour chaque historique on récupere la liste des historique_question
        foreach ($listHistorics as $row) {
            //listHistoricsId => list des id des historiques correspondant à l'user courant
            array_push($listHistoricsId, $row->getId());
            $listIdHistoricQuestion = $histquest->findBy(
                ["id_historic" => $row->getId()]
            );
            //list des historique_question en fonction des historique
            array_push($listHistoriqueQuestion, $listIdHistoricQuestion);
        }
        foreach ($listHistoricsId as $raw) {
            $historicWithCat = $historic->getCatByHistoric($raw);
            array_push($listGlobal, $historicWithCat);
        }
        $jsonUser = $serializer->serialize($listGlobal, 'json');

        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    #[Route('/apip/cat_questions/{id}', name: 'catQuestions')]
    public function getQuestionsByCategory(int $id, SerializerInterface $serializer, QuestionsRepository $questionsRepository, AnswersRepository $answersRepository, CategoriesRepository $categoriesRepository, Categories $categories): JsonResponse
    {
        $category = $categoriesRepository->find($id);
        $idCat = $category->getId();

        $listQuestion = [];
        if ($idCat === 1) {
            $questions = $questionsRepository->findAll();
            $questionsRand = array_rand($questions, 10);
            foreach ($questionsRand as $key) {
                array_push($listQuestion, $questions[$key]);
            }
        } else {
            $questions = $category->getQuestion()->toArray();
            $questionsRand = array_rand($questions, 10);
            foreach ($questionsRand as $key) {
                array_push($listQuestion, $questions[$key]);
            }
        }

        $jsonUser = $serializer->serialize($listQuestion, 'json', ['groups' => 'read_cat']);

        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }
}
    // #[Route('/apip/historics_users', name: 'historicsByUser')]
    // public function getUserTest(SerializerInterface $serializer, HistoricsRepository $historics): JsonResponse
    // {
    //     $userConnected = $this->getUser();
    //     $idUser = $userConnected->getId();

    //     $listHistorics = $historics->findBy(
    //         ["id_user" => "$idUser"],
    //         ["history_date" => "desc"
    //     ],
    //     );

    //     $jsonUser = $serializer->serialize($listHistorics, 'json');

    //     return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    // }
