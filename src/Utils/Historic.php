<?php

namespace App\Utils;

use App\Entity\Historics;
use App\Entity\HistoricsQuestions;
use Doctrine\ORM\EntityManagerInterface;

class Historic
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getCatByHistoric($idHistoric)
    {
        $listCat = array();
        $historic = $this->em->getRepository(Historics::class)->find($idHistoric);
        $historicsQuestions = $this->em->getRepository(HistoricsQuestions::class)
            ->findBy(
                ["id_historic" => "$idHistoric"]
            );
        foreach ($historicsQuestions as $question) {

            foreach ($question->getIdQuestion()->getCategories() as $cat) {
                $listCat[$cat->getId()] = $cat->getName();
            }
        }

        $result['historic'] = $historic;
        $result['categories'] = $listCat;
        return $result;
    }
}
