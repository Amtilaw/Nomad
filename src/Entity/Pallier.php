<?php

namespace App\Entity;

use App\Repository\PallierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PallierRepository::class)
 */
class Pallier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $timecode;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="id_pallier")
     */
    private $questions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre_groupe_question;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimecode(): ?string
    {
        return $this->timecode;
    }

    public function setTimecode(string $timecode): self
    {
        $this->timecode = $timecode;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setIdPallier($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getIdPallier() === $this) {
                $question->setIdPallier(null);
            }
        }

        return $this;
    }

    public function getTitreGroupeQuestion(): ?string
    {
        return $this->titre_groupe_question;
    }

    public function setTitreGroupeQuestion(?string $titre_groupe_question): self
    {
        $this->titre_groupe_question = $titre_groupe_question;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
