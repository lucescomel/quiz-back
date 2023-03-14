<?php

namespace App\DataFixtures;

use App\Entity\Answers;
use App\Entity\Historics;
use App\Entity\Questions;
use App\Entity\User;
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





        // $question = new Questions();
        // $question->setTitle('What is the capital of France?');

        // $answer1 = new Answers();
        // $answer1->setTitle('New York');
        // $answer1->setIsCorrect(false);
        // $answer1->setIdQuestion($question);

        // $answer2 = new Answers();
        // $answer2->setTitle('London');
        // $answer2->setIsCorrect(false);
        // $answer2->setIdQuestion($question);

        // $answer3 = new Answers();
        // $answer3->setTitle('Paris');
        // $answer3->setIsCorrect(true);
        // $answer3->setIdQuestion($question);

        // $question->setAnswers([$answer1, $answer2, $answer3]);

        // $historic = new Historics();
        // $historic->setHistoryDate(new \DateTime('2022-03-10'));
        // $historic->addIdQuestion($question);

        // $manager->persist($question);
        // $manager->persist($answer1);
        // $manager->persist($answer2);
        // $manager->persist($answer3);
        // $manager->persist($historic);

        $manager->flush();
    }
}
