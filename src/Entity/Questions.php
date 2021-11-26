<?php

namespace App\Entity;

use App\Repository\QuestionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionsRepository::class)
 */
class Questions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $id_question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdQuestion(): ?string
    {
        return $this->id_question;
    }

    public function setIdQuestion(string $id_question): self
    {
        $this->id_question = $id_question;

        return $this;
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
}
