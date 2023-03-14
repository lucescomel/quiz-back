<?php

namespace App\DataFixtures;

use App\Entity\Answers;
use App\Entity\Historics;
use App\Entity\Questions;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $question = new Questions();
        $question->setTitle('What is the capital of France?');

        $answer1 = new Answers();
        $answer1->setText('New York');
        $answer1->setIsCorrect(false);
        $answer1->setIdQuestion($question);

        $answer2 = new Answers();
        $answer2->setText('London');
        $answer2->setIsCorrect(false);
        $answer2->setIdQuestion($question);

        $answer3 = new Answers();
        $answer3->setText('Paris');
        $answer3->setIsCorrect(true);
        $answer3->setIdQuestion($question);

        $question->setAnswers([$answer1, $answer2, $answer3]);

        $historic = new Historics();
        $historic->setPlayedAt(new \DateTime('2022-03-10'));
        $historic->addIdQuestion($question);

        $manager->persist($question);
        $manager->persist($answer1);
        $manager->persist($answer2);
        $manager->persist($answer3);
        $manager->persist($historic);

        $manager->flush();
    }
}
