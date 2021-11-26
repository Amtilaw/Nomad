<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reponse_exact;

    /**
     * @ORM\OneToMany(targetEntity=Reponse::class, mappedBy="id_question")
     */
    private $reponses;

    /**
     * @ORM\ManyToMany(targetEntity=Chapitre::class, inversedBy="questions")
     */
    private $id_chapitre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question_1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question_2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question_3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question_4;


    public function __construct()
    {
        $this->reponses = new ArrayCollection();
        $this->id_chapitre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }
    public function getQuestion1(): ?string
    {
        return $this->question_1;
    }

    public function setQuestion1(string $question_1): self
    {
        $this->question_1 = $question_1;

        return $this;
    }

    public function getQuestion2(): ?string
    {
        return $this->question_2;
    }

    public function setQuestion2(string $question_2): self
    {
        $this->question_2 = $question_2;

        return $this;
    }

    public function getQuestion3(): ?string
    {
        return $this->question_3;
    }

    public function setQuestion3(string $question_3): self
    {
        $this->question_3 = $question_3;

        return $this;
    }

    public function getQuestion4(): ?string
    {
        return $this->question_4;
    }

    public function setQuestion4(string $question_4): self
    {
        $this->question_4 = $question_4;

        return $this;
    }
    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }



    /**
     * @return Collection|Reponse[]
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setIdQuestion($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getIdQuestion() === $this) {
                $reponse->setIdQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Chapitre[]
     */
    public function getIdChapitre(): Collection
    {
        return $this->id_chapitre;
    }

    public function addIdChapitre(Chapitre $idChapitre): self
    {
        if (!$this->id_chapitre->contains($idChapitre)) {
            $this->id_chapitre[] = $idChapitre;
        }

        return $this;
    }

    public function removeIdChapitre(Chapitre $idChapitre): self
    {
        $this->id_chapitre->removeElement($idChapitre);

        return $this;
    }
}
