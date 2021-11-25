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

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getReponseExact(): ?string
    {
        return $this->reponse_exact;
    }

    public function setReponseExact(string $reponse_exact): self
    {
        $this->reponse_exact = $reponse_exact;

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
