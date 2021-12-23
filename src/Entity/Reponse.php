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
     * @ORM\Column(type="smallint")
     */
    private $result_intermediaire;

    /**
     * @ORM\Column(type="integer")
     */
    private $question_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $proposition_id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $proposition_value;

    /**
     * @ORM\Column(type="varchar")
     */
    private $answer;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResultIntermediaire(): ?int
    {
        return $this->result_intermediaire;
    }

    public function setResultIntermediaire(int $result_intermediaire): self
    {
        $this->result_intermediaire = $result_intermediaire;

        return $this;
    }

    public function getQuestionId(): ?int
    {
        return $this->question_id;
    }

    public function setQuestionId(int $question_id): self
    {
        $this->question_id = $question_id;

        return $this;
    }

    public function getPropositionId(): ?int
    {
        return $this->proposition_id;
    }

    public function setPropositionId(int $proposition_id): self
    {
        $this->proposition_id = $proposition_id;

        return $this;
    }

    public function getPropositionValue(): ?int
    {
        return $this->proposition_value;
    }

    public function setPropositionValue(int $proposition_value): self
    {
        $this->proposition_value = $proposition_value;

        return $this;
    }

    public function getAnswer(): ?int
    {
        return $this->answer;
    }

    public function setAnswer(int $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
