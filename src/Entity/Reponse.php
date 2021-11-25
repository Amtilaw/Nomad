<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
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
    private $reponse;

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

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="reponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_question;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $id_user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
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

    public function getIdQuestion(): ?Question
    {
        return $this->id_question;
    }

    public function setIdQuestion(?Question $id_question): self
    {
        $this->id_question = $id_question;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }
}
