<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource as MetadataApiResource;

#[ORM\Entity(repositoryClass: QuestionsRepository::class)]
#[MetadataApiResource()]
class Questions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'id_question', targetEntity: Answers::class, orphanRemoval: true)]
    private Collection $answers;

    #[ORM\OneToOne(inversedBy: 'questions', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Answers $id_success = null;

    #[ORM\OneToMany(mappedBy: 'id_question', targetEntity: HistoricsQuestions::class)]
    private Collection $historicsQuestions;

    #[ORM\ManyToMany(targetEntity: Categories::class, mappedBy: 'question')]
    private Collection $categories;

    // #[ORM\ManyToMany(targetEntity: Historics::class, mappedBy: 'id_question')]
    // private Collection $historics;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        // $this->historics = new ArrayCollection();
        $this->historicsQuestions = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Answers>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answers $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers->add($answer);
            $answer->setIdQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answers $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getIdQuestion() === $this) {
                $answer->setIdQuestion(null);
            }
        }

        return $this;
    }

    public function getIdSuccess(): ?Answers
    {
        return $this->id_success;
    }

    public function setIdSuccess(Answers $id_success): self
    {
        $this->id_success = $id_success;

        return $this;
    }

    // /**
    //  * @return Collection<int, Historics>
    //  */
    // public function getHistorics(): Collection
    // {
    //     return $this->historics;
    // }

    // public function addHistoric(Historics $historic): self
    // {
    //     if (!$this->historics->contains($historic)) {
    //         $this->historics->add($historic);
    //         $historic->addIdQuestion($this);
    //     }

    //     return $this;
    // }

    // public function removeHistoric(Historics $historic): self
    // {
    //     if ($this->historics->removeElement($historic)) {
    //         $historic->removeIdQuestion($this);
    //     }

    //     return $this;
    // }

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
            $historicsQuestion->setIdQuestion($this);
        }

        return $this;
    }

    public function removeHistoricsQuestion(HistoricsQuestions $historicsQuestion): self
    {
        if ($this->historicsQuestions->removeElement($historicsQuestion)) {
            // set the owning side to null (unless already changed)
            if ($historicsQuestion->getIdQuestion() === $this) {
                $historicsQuestion->setIdQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Categories>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addQuestion($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeQuestion($this);
        }

        return $this;
    }
}
