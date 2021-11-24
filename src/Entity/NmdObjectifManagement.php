<?php

namespace App\Entity;

use App\Repository\NmdObjectifManagementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdObjectifManagementRepository::class)
 */
class NmdObjectifManagement
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
    private $userId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $monthAt;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $bm;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $codeCluster;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $objectivesVv;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $objectivesVr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operatorId;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $userRoles;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMonthAt(): ?\DateTimeInterface
    {
        return $this->monthAt;
    }

    public function setMonthAt(?\DateTimeInterface $monthAt): self
    {
        $this->monthAt = $monthAt;

        return $this;
    }

    public function getBm(): ?string
    {
        return $this->bm;
    }

    public function setBm(?string $bm): self
    {
        $this->bm = $bm;

        return $this;
    }

    public function getRefCluster(): ?string
    {
        return $this->codeCluster;
    }

    public function setRefCluster(?string $codeCluster): self
    {
        $this->codeCluster = $codeCluster;

        return $this;
    }

    public function getObjectivesVv(): ?int
    {
        return $this->objectivesVv;
    }

    public function setObjectivesVv(?int $objectivesVv): self
    {
        $this->objectivesVv = $objectivesVv;

        return $this;
    }

    public function getObjectivesVr(): ?int
    {
        return $this->objectivesVr;
    }

    public function setObjectivesVr(?int $objectivesVr): self
    {
        $this->objectivesVr = $objectivesVr;

        return $this;
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

    public function getUserRoles(): ?string
    {
        return $this->userRoles;
    }

    public function setUserRoles(?string $userRoles): self
    {
        $this->userRoles = $userRoles;

        return $this;
    }

}
