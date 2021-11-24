<?php

namespace App\Entity;

use App\Repository\NmdProductPointsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdProductPointsRepository::class)
 */
class NmdProductPoints
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
    private $productId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $userId;

    /**
     * @ORM\Column(type="float", options={"default" : 0}, nullable=false)
     */
    private $points;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endAt;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $cpv;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cumulSteps;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $remunerationName;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $coefficient;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(?int $productId): self
    {
        $this->productId = $productId;

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

    public function getPoints(): ?float
    {
        return $this->points;
    }

    public function setPoints(?float $points): self
    {
        $this->points = $points;

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

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

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

    public function getCpv(): ?string
    {
        return $this->cpv;
    }

    public function setCpv(?string $cpv): self
    {
        $this->cpv = $cpv;

        return $this;
    }

    public function getCumulSteps(): ?float
    {
        return $this->cumulSteps;
    }

    public function setCumulSteps(?float $cumulSteps): self
    {
        $this->cumulSteps = $cumulSteps;

        return $this;
    }

    public function getRemunerationName(): ?string
    {
        return $this->remunerationName;
    }

    public function setRemunerationName(?string $remunerationName): self
    {
        $this->remunerationName = $remunerationName;

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
}
