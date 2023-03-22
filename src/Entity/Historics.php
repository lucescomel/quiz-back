<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\HistoricsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HistoricsRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'id_user' => 'exact'])]

class Historics
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read'])]
    private ?int $note = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['read'])]
    private ?\DateTimeInterface $history_date = null;

    // #[ORM\ManyToMany(targetEntity: Questions::class, inversedBy: 'historics')]
    // private Collection $id_question;

    #[ORM\ManyToOne(inversedBy: 'historics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $id_user = null;

    #[ORM\OneToMany(mappedBy: 'id_historic', targetEntity: HistoricsQuestions::class)]
    #[Groups(['read'])]
    private Collection $historicsQuestions;

    public function __construct()
    {
        // $this->id_question = new ArrayCollection();
        $this->historicsQuestions = new ArrayCollection();
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

    // /**
    //  * @return Collection<int, Questions>
    //  */
    // public function getIdQuestion(): Collection
    // {
    //     return $this->id_question;
    // }

    // public function addIdQuestion(Questions $idQuestion): self
    // {
    //     if (!$this->id_question->contains($idQuestion)) {
    //         $this->id_question->add($idQuestion);
    //     }

    //     return $this;
    // }

    // public function removeIdQuestion(Questions $idQuestion): self
    // {
    //     $this->id_question->removeElement($idQuestion);

    //     return $this;
    // }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    /**
     * @return Collection<int, HistoricsQuestions>
     */
    public function getHistoricsQuestions(): Collection
    {
        return $this->historicsQuestions;
    }

    public function addHistoricsQuestion(HistoricsQuestions $historicsQuestion): self
    {
        if (!$this->historicsQuestions->contains($historicsQuestion)) {
            $this->historicsQuestions->add($historicsQuestion);
            $historicsQuestion->setIdHistoric($this);
        }

        return $this;
    }

    public function removeHistoricsQuestion(HistoricsQuestions $historicsQuestion): self
    {
        if ($this->historicsQuestions->removeElement($historicsQuestion)) {
            // set the owning side to null (unless already changed)
            if ($historicsQuestion->getIdHistoric() === $this) {
                $historicsQuestion->setIdHistoric(null);
            }
        }

        return $this;
    }
}
