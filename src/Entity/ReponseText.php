<?php

namespace App\Entity;

use App\Repository\ReponseTextRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseTextRepository::class)
 */
class ReponseText
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="reponseTexts")
     */
    private $id_question;

    public function getId(): ?int
    {
        return $this->id;
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
}
