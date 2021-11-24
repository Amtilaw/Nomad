<?php

namespace App\Entity;

use App\Repository\NmdObjectifClustersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdObjectifClustersRepository::class)
 */
class NmdObjectifClusters
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codeCluster;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mois;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vv;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vr;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $courtier1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $courtier2;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bm;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $partnerId;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $passeRepasse;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $cpv;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $libelleCluster;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vMobiles;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vItem1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCluster(): ?string
    {
        return $this->codeCluster;
    }

    public function setCodeCluster(?string $codeCluster): self
    {
        $this->codeCluster = $codeCluster;

        return $this;
    }

    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(?string $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    public function getVv(): ?int
    {
        return $this->vv;
    }

    public function setVv(?int $vv): self
    {
        $this->vv = $vv;

        return $this;
    }

    public function getVr(): ?int
    {
        return $this->vr;
    }

    public function setVr(?int $vr): self
    {
        $this->vr = $vr;

        return $this;
    }

    public function getCourtier1(): ?string
    {
        return $this->courtier1;
    }

    public function setCourtier1(?string $courtier1): self
    {
        $this->courtier1 = $courtier1;

        return $this;
    }

    public function getCourtier2(): ?string
    {
        return $this->courtier2;
    }

    public function setCourtier2(?string $courtier2): self
    {
        $this->courtier2 = $courtier2;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getBm(): ?int
    {
        return $this->bm;
    }

    public function setBm(?int $bm): self
    {
        $this->bm = $bm;

        return $this;
    }

    public function getPartnerId(): ?int
    {
        return $this->partnerId;
    }

    public function setPartnerId(?int $partnerId): self
    {
        $this->partnerId = $partnerId;

        return $this;
    }

    public function getPasseRepasse(): ?string
    {
        return $this->passeRepasse;
    }

    public function setPasseRepasse(?string $passeRepasse): self
    {
        $this->passe_repasse = $passeRepasse;

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

    public function getLibelleCluster(): ?string
    {
        return $this->libelleCluster;
    }

    public function setLibelleCluster(?string $libelleCluster): self
    {
        $this->libelleCluster = $libelleCluster;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getVMobiles(): ?int
    {
        return $this->vMobiles;
    }

    public function setVMobiles(?int $vMobiles): self
    {
        $this->vMobiles = $vMobiles;

        return $this;
    }

    public function getVItem1(): ?int
    {
        return $this->vItem1;
    }

    public function setVItem1(?int $vItem1): self
    {
        $this->vItem1 = $vItem1;

        return $this;
    }

}
