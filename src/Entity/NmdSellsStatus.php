<?php

namespace App\Entity;

use App\Repository\NmdSellsStatusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdSellsStatusRepository::class)
 */
class NmdSellsStatus
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
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $statusExternName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $statusInternName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $codeCourtier;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $statusDateStartAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $statusDateEndAt;

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

    public function getStatusExternName(): ?string
    {
        return $this->statusExternName;
    }

    public function setStatusExternName(?string $statusExternName): self
    {
        $this->statusExternName = $statusExternName;

        return $this;
    }

    public function getStatusInternName(): ?string
    {
        return $this->statusInternName;
    }

    public function setStatusInternName(?string $statusInternName): self
    {
        $this->statusInternName = $statusInternName;

        return $this;
    }

    public function getCodeCourtier(): ?string
    {
        return $this->codeCourtier;
    }

    public function setCodeCourtier(?string $codeCourtier): self
    {
        $this->codeCourtier = $codeCourtier;

        return $this;
    }

    public function getStatusDateStartAt(): ?\DateTimeInterface
    {
        return $this->statusDateStartAt;
    }

    public function setStatusDateStartAt(?\DateTimeInterface $statusDateStartAt): self
    {
        $this->statusDateStartAt = $statusDateStartAt;

        return $this;
    }

    public function getStatusDateEndAt(): ?\DateTimeInterface
    {
        return $this->statusDateEndAt;
    }

    public function setStatusDateEndAt(?\DateTimeInterface $statusDateEndAt): self
    {
        $this->statusDateEndAt = $statusDateEndAt;

        return $this;
    }
}
