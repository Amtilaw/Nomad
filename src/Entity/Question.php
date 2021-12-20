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
    private $libelle;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modifyAt;

    /**
     * @ORM\ManyToOne(targetEntity=Pallier::class, inversedBy="questions")
     */
    private $id_pallier;

    /**
     * @ORM\ManyToOne(targetEntity=Level::class, inversedBy="questions")
     */
    private $id_lvl;

    /**
     * @ORM\ManyToOne(targetEntity=Module::class, inversedBy="questions")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $id_module;

    /**
     * @ORM\Column(type="integer")
     */
    private $ordering;

    /**
     * @ORM\ManyToOne(targetEntity=Video::class, inversedBy="questions")
     */
    private $id_video;

    /**
     * @ORM\ManyToOne(targetEntity=Pdf::class, inversedBy="questions")
     */
    private $id_pdf;

    /**
     * @ORM\OneToMany(targetEntity=ReponseText::class, mappedBy="id_question")
     */
    private $reponseTexts;

    /**
     * @ORM\OneToMany(targetEntity=Proposition::class, mappedBy="id_question")
     */
    private $propositions;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="questions")
     */
    private $id_type;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
        $this->id_chapitre = new ArrayCollection();
        $this->reponseTexts = new ArrayCollection();
        $this->propositions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getModifyAt(): ?\DateTimeInterface
    {
        return $this->modifyAt;
    }

    public function setModifyAt(\DateTimeInterface $modifyAt): self
    {
        $this->modifyAt = $modifyAt;

        return $this;
    }

    public function getIdPallier(): ?Pallier
    {
        return $this->id_pallier;
    }

    public function setIdPallier(?Pallier $id_pallier): self
    {
        $this->id_pallier = $id_pallier;

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

    public function getIdModule(): ?Module
    {
        return $this->id_module;
    }

    public function setIdModule(?Module $id_module): self
    {
        $this->id_module = $id_module;

        return $this;
    }

    public function getOrdering(): ?int
    {
        return $this->ordering;
    }

    public function setOrdering(int $ordering): self
    {
        $this->ordering = $ordering;

        return $this;
    }

    public function getIdVideo(): ?Video
    {
        return $this->id_video;
    }

    public function setIdVideo(?Video $id_video): self
    {
        $this->id_video = $id_video;

        return $this;
    }

    public function getIdPdf(): ?Pdf
    {
        return $this->id_pdf;
    }

    public function setIdPdf(?Pdf $id_pdf): self
    {
        $this->id_pdf = $id_pdf;

        return $this;
    }

    public function removeProposition(Proposition $proposition): self
    {
        if ($this->propositions->removeElement($proposition)) {
            // set the owning side to null (unless already changed)
            if ($proposition->getIdQuestion() === $this) {
                $proposition->setIdQuestion(null);
            }
        }

        return $this;
    }

    public function getIdType(): ?Type
    {
        return $this->id_type;
    }

    public function setIdType(?Type $id_type): self
    {
        $this->id_type = $id_type;

        return $this;
    }
}
