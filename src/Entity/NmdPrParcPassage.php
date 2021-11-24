<?php

namespace App\Entity;

use App\Repository\NmdPrParcPassageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdPrParcPassageRepository::class)
 */
class NmdPrParcPassage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $codHexc;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $tech;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $refrNro;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $gestInfr;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $typLogm;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nbLogm;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $refrPm;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $numrVoie;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $ssNumrVoie;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $nomVoie;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $vill;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $codInsee;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datPremCommAdrs;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datDernCommAdrs;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $debitNet;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $codeCluster;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $lblCluster;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $cpv;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $lblCpv;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $villeGoogle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lng;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $userId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsAffected;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $affectedAt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $affected_by;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $affected_by_infos;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $beneficiary_roles;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isFlyers;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $managerId;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $directorId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodHexc(): ?string
    {
        return $this->codHexc;
    }

    public function setCodHexc(?string $codHexc): self
    {
        $this->codHexc = $codHexc;

        return $this;
    }

    public function getTech(): ?string
    {
        return $this->tech;
    }

    public function setTech(?string $tech): self
    {
        $this->tech = $tech;

        return $this;
    }

    public function getRefrNro(): ?string
    {
        return $this->refrNro;
    }

    public function setRefrNro(?string $refrNro): self
    {
        $this->refrNro = $refrNro;

        return $this;
    }

    public function getGestInfr(): ?string
    {
        return $this->gestInfr;
    }

    public function setGestInfr(?string $gestInfr): self
    {
        $this->gestInfr = $gestInfr;

        return $this;
    }

    public function getTypLogm(): ?string
    {
        return $this->typLogm;
    }

    public function setTypLogm(?string $typLogm): self
    {
        $this->typLogm = $typLogm;

        return $this;
    }

    public function getNbLogm(): ?string
    {
        return $this->nbLogm;
    }

    public function setNbLogm(?string $nbLogm): self
    {
        $this->nbLogm = $nbLogm;

        return $this;
    }

    public function getRefrPm(): ?string
    {
        return $this->refrPm;
    }

    public function setRefrPm(?string $refrPm): self
    {
        $this->refrPm = $refrPm;

        return $this;
    }

    public function getNumrVoie(): ?string
    {
        return $this->numrVoie;
    }

    public function setNumrVoie(?string $numrVoie): self
    {
        $this->numrVoie = $numrVoie;

        return $this;
    }

    public function getSsNumrVoie(): ?string
    {
        return $this->ssNumrVoie;
    }

    public function setSsNumrVoie(?string $ssNumrVoie): self
    {
        $this->ssNumrVoie = $ssNumrVoie;

        return $this;
    }

    public function getNomVoie(): ?string
    {
        return $this->nomVoie;
    }

    public function setNomVoie(?string $nomVoie): self
    {
        $this->nomVoie = $nomVoie;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVill(): ?string
    {
        return $this->vill;
    }

    public function setVill(?string $vill): self
    {
        $this->vill = $vill;

        return $this;
    }

    public function getCodInsee(): ?string
    {
        return $this->codInsee;
    }

    public function setCodInsee(?string $codInsee): self
    {
        $this->codInsee = $codInsee;

        return $this;
    }

    public function getDatPremCommAdrs(): ?\DateTimeInterface
    {
        return $this->datPremCommAdrs;
    }

    public function setDatPremCommAdrs(?\DateTimeInterface $datPremCommAdrs): self
    {
        $this->datPremCommAdrs = $datPremCommAdrs;

        return $this;
    }

    public function getDatDernCommAdrs(): ?\DateTimeInterface
    {
        return $this->datDernCommAdrs;
    }

    public function setDatDernCommAdrs(?\DateTimeInterface $datDernCommAdrs): self
    {
        $this->datDernCommAdrs = $datDernCommAdrs;

        return $this;
    }

    public function getDebitNet(): ?string
    {
        return $this->debitNet;
    }

    public function setDebitNet(?string $debitNet): self
    {
        $this->debitNet = $debitNet;

        return $this;
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

    public function getLblCluster(): ?string
    {
        return $this->lblCluster;
    }

    public function setLblCluster(?string $lblCluster): self
    {
        $this->lblCluster = $lblCluster;

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

    public function getLblCpv(): ?string
    {
        return $this->lblCpv;
    }

    public function setLblCpv(?string $lblCpv): self
    {
        $this->lblCpv = $lblCpv;

        return $this;
    }

    public function getVilleGoogle(): ?string
    {
        return $this->villeGoogle;
    }

    public function setVilleGoogle(?string $villeGoogle): self
    {
        $this->villeGoogle = $villeGoogle;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(?string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(?string $lng): self
    {
        $this->lng = $lng;

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

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getIsAffected(): ?bool
    {
        return $this->IsAffected;
    }

    public function setIsAffected(?bool $IsAffected): self
    {
        $this->IsAffected = $IsAffected;

        return $this;
    }

    public function getAffectedAt(): ?\DateTimeInterface
    {
        return $this->affectedAt;
    }

    public function setAffectedAt(?\DateTimeInterface $affectedAt): self
    {
        $this->affectedAt = $affectedAt;

        return $this;
    }

    public function getAffectedBy(): ?string
    {
        return $this->affected_by;
    }

    public function setAffectedBy(?string $affected_by): self
    {
        $this->affected_by = $affected_by;

        return $this;
    }

    public function getAffectedByInfos(): ?string
    {
        return $this->affected_by_infos;
    }

    public function setAffectedByInfos(?string $affected_by_infos): self
    {
        $this->affected_by_infos = $affected_by_infos;

        return $this;
    }

    public function getBeneficiaryRoles(): ?string
    {
        return $this->beneficiary_roles;
    }

    public function setBeneficiaryRoles(?string $beneficiary_roles): self
    {
        $this->beneficiary_roles = $beneficiary_roles;

        return $this;
    }

    public function getIsFlyers(): ?bool
    {
        return $this->isFlyers;
    }

    public function setIsFlyers(?bool $isFlyers): self
    {
        $this->isFlyers = $isFlyers;

        return $this;
    }

    public function getManagerId(): ?string
    {
        return $this->managerId;
    }

    public function setManagerId(?string $managerId): self
    {
        $this->managerId = $managerId;

        return $this;
    }

    public function getDirectorId(): ?string 
    {
        return $this->directorId;
    }

    public function setDirectorId(?string $directorId): self
    {
        $this->directorId = $directorId;

        return $this;
    }
}
