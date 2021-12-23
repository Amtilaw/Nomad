<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModuleRepository::class)
 */
class Module
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
    private $nom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifyAt;

    /**
     * @ORM\ManyToOne(targetEntity=Level::class, inversedBy="modules")
     */
    private $id_lvl;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="id_module")
     */
    private $questions;

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="modules")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $id_formation;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifyAt(): ?\DateTime
    {
        return $this->modifyAt;
    }

    public function setModifyAt(\DateTime $modifyAt): self
    {
        $this->modifyAt = $modifyAt;

        return $this;
    }

    public function getIdLvl(): ?Level
    {
        return $this->id_lvl;
    }

    public function setIdLvl(?Level $id_lvl): self
    {
        $this->id_lvl = $id_lvl;

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
            $question->setIdModule($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getIdModule() === $this) {
                $question->setIdModule(null);
            }
        }

        return $this;
    }

    public function getIdFormation(): ?Formation
    {
        return $this->id_formation;
    }

    public function setIdFormation(?Formation $id_formation): self
    {
        $this->id_formation = $id_formation;

        return $this;
    }

    public function getSelected()
    {
        if (isset($this->selected)) return "selected";
    }
}
