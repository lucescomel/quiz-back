<?php

namespace App\DataFixtures;

use App\Entity\Answers;
use App\Entity\Categories;
use App\Entity\Historics;
use App\Entity\HistoricsQuestions;
use App\Entity\Questions;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }


    public function load(ObjectManager $manager)
    {
        // Création d'un user "normal"
        $user = new User();
        $user->setEmail("user@quiz.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
        $user->setName('user');
        $manager->persist($user);


        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("admin@quiz.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
        $userAdmin->setName('admin');
        $manager->persist($userAdmin);


        //Création d'une catégorie
        $listCategory = [];
        for ($i = 0; $i < 5; $i++) {
            $categorie = new Categories();
            $categorie->setName("catégorie n°" . $i);
            $manager->persist($categorie);
            $listCategory[] = $categorie;
        }

        // Création d'une question/réponse
        $listQuestion = [];
        for ($i = 0; $i < 5; $i++) {
            $question = new Questions();
            $question->setTitle("Ceci est la question n°" . $i);
            $manager->persist($question);
            $listResponse = [];
            for ($j = 0; $j < 4; $j++) {
                $answers = new Answers();
                $answers->setTitle("reponse N°" . $j);
                $answers->setIdQuestion($question);
                $manager->persist($answers);
                $listResponse[] = $answers;
            }
            $question->setIdSuccess($listResponse[array_rand($listResponse)]);
            $question->addCategory($listCategory[array_rand($listCategory)]);
            $manager->persist($question);
            $listQuestion[] = $question;
        }

        // Création d'un historique
        $listHistoric = [];
        for ($i = 0; $i < 5; $i++) {
            $historic = new Historics();
            $historic->setHistoryDate(new DateTime());
            $historic->setIdUser($userAdmin);
            $manager->persist($historic);
            $listHistoric[] = $historic;

            $listSuccess = [];
            for ($k = 0; $k < 10; $k++) {
                $quiz = new HistoricsQuestions();
                $quiz->setSuccess(true);
                if ($quiz->isSuccess() === true) {
                    $listSuccess[] = $quiz;
                }
                $quiz->setIdQuestion($listQuestion[array_rand($listQuestion)]);
                $quiz->setIdHistoric($listHistoric[array_rand($listHistoric)]);
                $manager->persist($quiz);
            }
            $historic->setNote(count($listSuccess));
            $manager->persist($historic);
        }

        $manager->flush();
    }
}
