<?php

namespace App\Entity;

use App\Repository\HistoricsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoricsRepository::class)]
class Historics
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $note = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $history_date = null;

    #[ORM\ManyToMany(targetEntity: Questions::class, inversedBy: 'historics')]
    private Collection $id_question;

    public function __construct()
    {
        $this->id_question = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getHistoryDate(): ?\DateTimeInterface
    {
        return $this->history_date;
    }

    public function setHistoryDate(?\DateTimeInterface $history_date): self
    {
        $this->history_date = $history_date;

        return $this;
    }

    /**
     * @return Collection<int, Questions>
     */
    public function getIdQuestion(): Collection
    {
        return $this->id_question;
    }

    public function addIdQuestion(Questions $idQuestion): self
    {
        if (!$this->id_question->contains($idQuestion)) {
            $this->id_question->add($idQuestion);
        }

        return $this;
    }

    public function removeIdQuestion(Questions $idQuestion): self
    {
        $this->id_question->removeElement($idQuestion);

        return $this;
    }
}
