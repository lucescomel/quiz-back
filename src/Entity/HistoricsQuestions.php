<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HistoricsQuestionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HistoricsQuestionsRepository::class)]
#[ApiResource()]
class HistoricsQuestions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $success = null;

    #[ORM\ManyToOne(inversedBy: 'historicsQuestions')]
    #[Groups(['read'])]
    private ?Questions $id_question = null;

    #[ORM\ManyToOne(inversedBy: 'historicsQuestions')]
    private ?Historics $id_historic = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isSuccess(): ?bool
    {
        return $this->success;
    }

    public function setSuccess(?bool $success): self
    {
        $this->success = $success;

        return $this;
    }

    public function getIdQuestion(): ?Questions
    {
        return $this->id_question;
    }

    public function setIdQuestion(?Questions $id_question): self
    {
        $this->id_question = $id_question;

        return $this;
    }

    public function getIdHistoric(): ?Historics
    {
        return $this->id_historic;
    }

    public function setIdHistoric(?Historics $id_historic): self
    {
        $this->id_historic = $id_historic;

        return $this;
    }
}
