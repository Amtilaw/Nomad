<?php

namespace App\Entity;

use App\Repository\NmdPointStepsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdPointStepsRepository::class)
 */
class NmdPointSteps
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operatorId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $stepStartAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $stepEndAt;

    /**
     * @ORM\Column(type="float", options={"default" : 1}, nullable=false)
     */
    private $coefficient;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $role;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stepStartValue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stepEndValue;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $stepName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $textStimulation;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $picto;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $color;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOperatorId(): ?int
    {
        return $this->operatorId;
    }

    public function setOperatorId(?int $operatorId): self
    {
        $this->operatorId = $operatorId;

        return $this;
    }

    public function getStepStartAt(): ?\DateTimeInterface
    {
        return $this->stepStartAt;
    }

    public function setStepStartAt(?\DateTimeInterface $stepStartAt): self
    {
        $this->stepStartAt = $stepStartAt;

        return $this;
    }

    public function getStepEndAt(): ?\DateTimeInterface
    {
        return $this->stepEndAt;
    }

    public function setStepEndAt(?\DateTimeInterface $stepEndAt): self
    {
        $this->stepEndAt = $stepEndAt;

        return $this;
    }

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(?float $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getStepStartValue(): ?int
    {
        return $this->stepStartValue;
    }

    public function setStepStartValue(int $stepStartValue): self
    {
        $this->stepStartValue = $stepStartValue;

        return $this;
    }

    public function getStepEndValue(): ?int
    {
        return $this->stepEndValue;
    }

    public function setStepEndValue(int $stepEndValue): self
    {
        $this->stepEndValue = $stepEndValue;

        return $this;
    }

    public function getStepName(): ?string
    {
        return $this->stepName;
    }

    public function setStepName(?string $stepName): self
    {
        $this->stepName = $stepName;

        return $this;
    }

    public function getTextStimulation(): ?string
    {
        return $this->textStimulation;
    }

    public function setTextStimulation(?string $textStimulation): self
    {
        $this->textStimulation = $textStimulation;

        return $this;
    }

    public function getPicto(): ?string
    {
        return $this->picto;
    }

    public function setPicto(?string $picto): self
    {
        $this->picto = $picto;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
