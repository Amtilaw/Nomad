<?php

namespace App\Entity;

use App\Repository\NmdRemunerationStatusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdRemunerationStatusRepository::class)
 */
class NmdRemunerationStatus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $remStatus;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $remPercent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $remOperatorId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $remUserId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRemStatus(): ?string
    {
        return $this->remStatus;
    }

    public function setRemStatus(?string $remStatus): self
    {
        $this->remStatus = $remStatus;

        return $this;
    }

    public function getRemPercent(): ?int
    {
        return $this->remPercent;
    }

    public function setRemPercent(?int $remPercent): self
    {
        $this->remPercent = $remPercent;

        return $this;
    }

    public function getRemOperatorId(): ?int
    {
        return $this->remOperatorId;
    }

    public function setRemOperatorId(?int $remOperatorId): self
    {
        $this->remOperatorId = $remOperatorId;

        return $this;
    }

    public function getRemUserId(): ?int
    {
        return $this->remUserId;
    }

    public function setRemUserId(?int $remUserId): self
    {
        $this->remUserId = $remUserId;

        return $this;
    }
}
