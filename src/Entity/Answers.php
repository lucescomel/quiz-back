<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AnswersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnswersRepository::class)]
#[ApiResource()]
class Answers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read_cat'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read_cat'])]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Questions $id_question = null;

    #[ORM\OneToOne(mappedBy: 'id_success', cascade: ['persist', 'remove'])]
    private ?Questions $questions = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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
    public function getQuestions(): ?Questions
    {
        return $this->questions;
    }

    public function setQuestions(Questions $questions): self
    {
        // set the owning side of the relation if necessary
        if ($questions->getIdSuccess() !== $this) {
            $questions->setIdSuccess($this);
        }

        $this->questions = $questions;

        return $this;
    }
}
