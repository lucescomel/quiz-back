<?php

namespace App\DataFixtures;

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
        $user->setEmail("user@gmail.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "123456"));
        $user->setName("user");

        $manager->persist($user);

        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("aurore@gmail.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "123456"));
        $userAdmin->setName("aurore");
        $manager->persist($userAdmin);

        //Creation categorie
        $listCategory = [];
        for ($i = 0; $i < 5; $i++) {
            $categorie = new Categories();
            $categorie->setName("nom de la categorie" . $i);
            $manager->persist($categorie);
            $listCategory[] = $categorie;
        }
        //Creation question et réponses
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
                //$question->addAnswer($answers);
            }
            $question->setIdSuccess($listResponse[array_rand($listResponse)]);
            $question->addCategory($listCategory[array_rand($listCategory)]);
            $question->addCategory($listCategory[array_rand($listCategory)]);
            $manager->persist($question);
            $listQuestion[] = $question;
        }

        //creation d'un historique
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

