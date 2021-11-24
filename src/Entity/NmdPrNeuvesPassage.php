<?php

namespace App\Entity;

use App\Repository\NmdPrNeuvesPassageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdPrNeuvesPassageRepository::class)
 */
class NmdPrNeuvesPassage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $numrVoie;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $ssNumrVoie;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nomVoie;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $villeGoogle;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $lat;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $moisLivraison;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $semLivraison;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $techno;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $codeHexc;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $refrNro;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $gestInfr;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbLogt;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $typLogm;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $refrPm;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nomDeZdv;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $region;

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
    private $nbFyrThd;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nbFyrAdsl;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nbFyrMobMono;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nbFyrMobMultiThd;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nbFyrMobMultiAdsl;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $debitNet;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $anciennete;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $datDebtAvtprem;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $preIncription;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $codInsee;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $reouv;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $parc_neuves;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $etat;

        /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $codeCluster;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $lblCluster;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $cpv;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $lbl_cpv;

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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $managerId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $directorId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $jourLivraison;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isOpenClose;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ouvertAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fermeAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isOnCurrentObjectifs;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

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

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(?string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(string $lng): self
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

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getMoisLivraison(): ?\DateTimeInterface
    {
        return $this->moisLivraison;
    }

    public function setMoisLivraison(?\DateTimeInterface $moisLivraison): self
    {
        $this->moisLivraison = $moisLivraison;

        return $this;
    }

    public function getSemLivraison(): ?string
    {
        return $this->semLivraison;
    }

    public function setSemLivraison(?string $semLivraison): self
    {
        $this->semLivraison = $semLivraison;

        return $this;
    }

    public function getTechno(): ?string
    {
        return $this->techno;
    }

    public function setTechno(?string $techno): self
    {
        $this->techno = $techno;

        return $this;
    }

    public function getCodeHexc(): ?string
    {
        return $this->codeHexc;
    }

    public function setCodeHexc(?string $codeHexc): self
    {
        $this->codeHexc = $codeHexc;

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

    public function getNbLogt(): ?int
    {
        return $this->nbLogt;
    }

    public function setNbLogt(?int $nbLogt): self
    {
        $this->nbLogt = $nbLogt;

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

    public function getRefrPm(): ?string
    {
        return $this->refrPm;
    }

    public function setRefrPm(?string $refrPm): self
    {
        $this->refrPm = $refrPm;

        return $this;
    }

    public function getNomDeZdv(): ?string
    {
        return $this->nomDeZdv;
    }

    public function setNomDeZdv(?string $nomDeZdv): self
    {
        $this->nomDeZdv = $nomDeZdv;

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

    public function getNbFyrThd(): ?string
    {
        return $this->nbFyrThd;
    }

    public function setNbFyrThd(?string $nbFyrThd): self
    {
        $this->nbFyrThd = $nbFyrThd;

        return $this;
    }

    public function getNbFyrAdsl(): ?string
    {
        return $this->nbFyrAdsl;
    }

    public function setNbFyrAdsl(?string $nbFyrAdsl): self
    {
        $this->nbFyrAdsl = $nbFyrAdsl;

        return $this;
    }

    public function getNbFyrMobMono(): ?string
    {
        return $this->nbFyrMobMono;
    }

    public function setNbFyrMobMono(?string $nbFyrMobMono): self
    {
        $this->nbFyrMobMono = $nbFyrMobMono;

        return $this;
    }

    public function getNbFyrMobMultiThd(): ?string
    {
        return $this->nbFyrMobMultiThd;
    }

    public function setNbFyrMobMultiThd(?string $nbFyrMobMultiThd): self
    {
        $this->nbFyrMobMultiThd = $nbFyrMobMultiThd;

        return $this;
    }

    public function getNbFyrMobMultiAdsl(): ?string
    {
        return $this->nbFyrMobMultiAdsl;
    }

    public function setNbFyrMobMultiAdsl(?string $nbFyrMobMultiAdsl): self
    {
        $this->nbFyrMobMultiAdsl = $nbFyrMobMultiAdsl;

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

    public function getAnciennete(): ?int
    {
        return $this->anciennete;
    }

    public function setAnciennete(?int $anciennete): self
    {
        $this->anciennete = $anciennete;

        return $this;
    }

    public function getDatDebtAvtprem(): ?string
    {
        return $this->datDebtAvtprem;
    }

    public function setDatDebtAvtprem(?string $datDebtAvtprem): self
    {
        $this->datDebtAvtprem = $datDebtAvtprem;

        return $this;
    }

    public function getPreIncription(): ?string
    {
        return $this->preIncription;
    }

    public function setPreIncription(?string $preIncription): self
    {
        $this->preIncription = $preIncription;

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

    public function getReouv(): ?string
    {
        return $this->reouv;
    }

    public function setReouv(?string $reouv): self
    {
        $this->reouv = $reouv;

        return $this;
    }

    public function getParcNeuves(): ?bool
    {
        return $this->parc_neuves;
    }

    public function setParcNeuves(?bool $parc_neuves): self
    {
        $this->parc_neuves = $parc_neuves;

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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

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
        return $this->lbl_cpv;
    }

    public function setLblCpv(?string $lbl_cpv): self
    {
        $this->lbl_cpv = $lbl_cpv;

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

    public function getManagerId(): ?int
    {
        return $this->managerId;
    }

    public function setManagerId(?int $managerId): self
    {
        $this->managerId = $managerId;

        return $this;
    }

    public function getDirectorId(): ?int
    {
        return $this->directorId;
    }

    public function setDirectorId(?int $directorId): self
    {
        $this->directorId = $directorId;

        return $this;
    }

    public function getJourLivraison(): ?\DateTimeInterface
    {
        return $this->jourLivraison;
    }

    public function setJourLivraison(?\DateTimeInterface $jourLivraison): self
    {
        $this->jourLivraison = $jourLivraison;

        return $this;
    }

    public function getIsOpenClose(): ?bool
    {
        return $this->isOpenClose;
    }

    public function setIsOpenClose(?bool $isOpenClose): self
    {
        $this->isOpenClose = $isOpenClose;

        return $this;
    }

    public function getOuvertAt(): ?\DateTimeInterface
    {
        return $this->ouvertAt;
    }

    public function setOuvertAt(?\DateTimeInterface $ouvertAt): self
    {
        $this->ouvertAt = $ouvertAt;

        return $this;
    }

    public function getFermeAt(): ?\DateTimeInterface
    {
        return $this->fermeAt;
    }

    public function setFermeAt(?\DateTimeInterface $fermeAt): self
    {
        $this->fermeAt = $fermeAt;

        return $this;
    }

    public function getIsOnCurrentObjectifs(): ?bool
    {
        return $this->isOnCurrentObjectifs;
    }

    public function setIsOnCurrentObjectifs(?bool $isOnCurrentObjectifs): self
    {
        $this->isOnCurrentObjectifs = $isOnCurrentObjectifs;

        return $this;
    }
}
