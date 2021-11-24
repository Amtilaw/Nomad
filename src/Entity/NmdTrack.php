<?php

namespace App\Entity;

use App\Repository\NmdTrackRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdTrackRepository::class)
 */
class NmdTrack
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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $productId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fdrId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numCBL;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $sousEtat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numApt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $backgroundColor;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $argu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cp;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $valid;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DateRdv;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isNewCustomer;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $SexGenre;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $BirthDate;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $birthLocation;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $FixPhone;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $contactEmail;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $identityDocumentType;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $identityDocumentNumber;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isNewPhoneNumber;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ptoNumber;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isKnowPtoNumber;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isHavePtoNumber;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAnnuaireUniverselInscription;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isParentalActivation;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAntiProspection;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSansAdressesComplete;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSansPrenomComplet;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAntiAnnuaireInverse;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $statusBo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $teleproId;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $phoneNumberToKeep;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $rioCode;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $concurrentName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $EngagementEndAt;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $operatorId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ouvertureBoAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $appelBoAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $statusBoAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $affectationBoAt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $streetNumber;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $streetNumbercomplement;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $apartmentNumber;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $apartmentFloor;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $streetName;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPieceValidated;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPaid;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $productOptionId;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $numPanier;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $typLogm;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $refusBoStatus;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isListeRouge;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $bundleId;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $mobilePhone2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $refusBo;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $locked;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $noteRessentiProspection;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaireProspection;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $gesteCo;


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

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(?int $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getFdrId(): ?int
    {
        return $this->fdrId;
    }

    public function setFdrId(?int $fdrId): self
    {
        $this->fdrId = $fdrId;

        return $this;
    }

    public function getNumCBL(): ?string
    {
        return $this->numCBL;
    }

    public function setNumCBL(?string $numCBL): self
    {
        $this->numCBL = $numCBL;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(?int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getSousEtat(): ?string
    {
        return $this->sousEtat;
    }

    public function setSousEtat(?string $sousEtat): self
    {
        $this->sousEtat = $sousEtat;

        return $this;
    }

    public function getEtage(): ?string
    {
        return $this->etage;
    }

    public function setEtage(?string $etage): self
    {
        $this->etage = $etage;

        return $this;
    }

    public function getNumApt(): ?string
    {
        return $this->numApt;
    }

    public function setNumApt(?string $numApt): self
    {
        $this->numApt = $numApt;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(?\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(?string $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getArgu(): ?bool
    {
        return $this->argu;
    }

    public function setArgu(?bool $argu): self
    {
        $this->argu = $argu;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(?string $rue): self
    {
        $this->rue = $rue;

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

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getDateRdv(): ?\DateTimeInterface
    {
        return $this->DateRdv;
    }

    public function setDateRdv(?\DateTimeInterface $DateRdv): self
    {
        $this->DateRdv = $DateRdv;

        return $this;
    }

    public function getIsNewCustomer(): ?bool
    {
        return $this->isNewCustomer;
    }

    public function setIsNewCustomer(bool $isNewCustomer): self
    {
        $this->isNewCustomer = $isNewCustomer;

        return $this;
    }

    public function getSexGenre(): ?string
    {
        return $this->SexGenre;
    }

    public function setSexGenre(?string $SexGenre): self
    {
        $this->SexGenre = $SexGenre;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->BirthDate;
    }

    public function setBirthDate(?\DateTimeInterface $BirthDate): self
    {
        $this->BirthDate = $BirthDate;

        return $this;
    }

    public function getBirthLocation(): ?string
    {
        return $this->birthLocation;
    }

    public function setBirthLocation(?string $birthLocation): self
    {
        $this->birthLocation = $birthLocation;

        return $this;
    }

    public function getFixPhone(): ?string
    {
        return $this->FixPhone;
    }

    public function setFixPhone(?string $FixPhone): self
    {
        $this->FixPhone = $FixPhone;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): self
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getIdentityDocumentType(): ?string
    {
        return $this->identityDocumentType;
    }

    public function setIdentityDocumentType(?string $identityDocumentType): self
    {
        $this->identityDocumentType = $identityDocumentType;

        return $this;
    }

    public function getIdentityDocumentNumber(): ?string
    {
        return $this->identityDocumentNumber;
    }

    public function setIdentityDocumentNumber(?string $identityDocumentNumber): self
    {
        $this->identityDocumentNumber = $identityDocumentNumber;

        return $this;
    }

    public function getIsNewPhoneNumber(): ?bool
    {
        return $this->isNewPhoneNumber;
    }

    public function setIsNewPhoneNumber(?bool $isNewPhoneNumber): self
    {
        $this->isNewPhoneNumber = $isNewPhoneNumber;

        return $this;
    }

    public function getPtoNumber(): ?string
    {
        return $this->ptoNumber;
    }

    public function setPtoNumber(?string $ptoNumber): self
    {
        $this->ptoNumber = $ptoNumber;

        return $this;
    }

    public function getIsKnowPtoNumber(): ?bool
    {
        return $this->isKnowPtoNumber;
    }

    public function setIsKnowPtoNumber(?bool $isKnowPtoNumber): self
    {
        $this->isKnowPtoNumber = $isKnowPtoNumber;

        return $this;
    }

    public function getIsHavePtoNumber(): ?bool
    {
        return $this->isHavePtoNumber;
    }

    public function setIsHavePtoNumber(?bool $isHavePtoNumber): self
    {
        $this->isHavePtoNumber = $isHavePtoNumber;

        return $this;
    }

    public function getIsAnnuaireUniverselInscription(): ?bool
    {
        return $this->isAnnuaireUniverselInscription;
    }

    public function setIsAnnuaireUniverselInscription(?bool $isAnnuaireUniverselInscription): self
    {
        $this->isAnnuaireUniverselInscription = $isAnnuaireUniverselInscription;

        return $this;
    }

    public function getIsParentalActivation(): ?bool
    {
        return $this->isParentalActivation;
    }

    public function setIsParentalActivation(?bool $isParentalActivation): self
    {
        $this->isParentalActivation = $isParentalActivation;

        return $this;
    }

    public function getIsAntiProspection(): ?bool
    {
        return $this->isAntiProspection;
    }

    public function setIsAntiProspection(?bool $isAntiProspection): self
    {
        $this->isAntiProspection = $isAntiProspection;

        return $this;
    }

    public function getIsSansAdressesComplete(): ?bool
    {
        return $this->isSansAdressesComplete;
    }

    public function setIsSansAdressesComplete(?bool $isSansAdressesComplete): self
    {
        $this->isSansAdressesComplete = $isSansAdressesComplete;

        return $this;
    }

    public function getIsSansPrenomComplet(): ?bool
    {
        return $this->isSansPrenomComplet;
    }

    public function setIsSansPrenomComplet(?bool $isSansPrenomComplet): self
    {
        $this->isSansPrenomComplet = $isSansPrenomComplet;

        return $this;
    }

    public function getIsAntiAnnuaireInverse(): ?bool
    {
        return $this->isAntiAnnuaireInverse;
    }

    public function setIsAntiAnnuaireInverse(?bool $isAntiAnnuaireInverse): self
    {
        $this->isAntiAnnuaireInverse = $isAntiAnnuaireInverse;

        return $this;
    }

    public function getStatusBo(): ?int
    {
        return $this->statusBo;
    }

    public function setStatusBo(?int $statusBo): self
    {
        $this->statusBo = $statusBo;

        return $this;
    }

    public function getTeleproId(): ?int
    {
        return $this->teleproId;
    }

    public function setTeleproId(?int $teleproId): self
    {
        $this->teleproId = $teleproId;

        return $this;
    }

    public function getPhoneNumberToKeep(): ?string
    {
        return $this->phoneNumberToKeep;
    }

    public function setPhoneNumberToKeep(?string $phoneNumberToKeep): self
    {
        $this->phoneNumberToKeep = $phoneNumberToKeep;

        return $this;
    }

    public function getRioCode(): ?string
    {
        return $this->rioCode;
    }

    public function setRioCode(?string $rioCode): self
    {
        $this->rioCode = $rioCode;

        return $this;
    }

    public function getConcurrentName(): ?string
    {
        return $this->concurrentName;
    }

    public function setConcurrentName(?string $concurrentName): self
    {
        $this->concurrentName = $concurrentName;

        return $this;
    }

    public function getEngagementEndAt(): ?\DateTimeInterface
    {
        return $this->EngagementEndAt;
    }

    public function setEngagementEndAt(?\DateTimeInterface $EngagementEndAt): self
    {
        $this->EngagementEndAt = $EngagementEndAt;

        return $this;
    }

    public function getOperatorId(): ?string
    {
        return $this->operatorId;
    }

    public function setOperatorId(?string $operatorId): self
    {
        $this->operatorId = $operatorId;

        return $this;
    }

    public function getOuvertureBoAt(): ?\DateTimeInterface
    {
        return $this->ouvertureBoAt;
    }

    public function setOuvertureBoAt(?\DateTimeInterface $ouvertureBoAt): self
    {
        $this->ouvertureBoAt = $ouvertureBoAt;

        return $this;
    }

    public function getAppelBoAt(): ?\DateTimeInterface
    {
        return $this->appelBoAt;
    }

    public function setAppelBoAt(?\DateTimeInterface $appelBoAt): self
    {
        $this->appelBoAt = $appelBoAt;

        return $this;
    }

    public function getStatusBoAt(): ?\DateTimeInterface
    {
        return $this->statusBoAt;
    }

    public function setStatusBoAt(?\DateTimeInterface $statusBoAt): self
    {
        $this->statusBoAt = $statusBoAt;

        return $this;
    }

    public function getAffectationBoAt(): ?\DateTimeInterface
    {
        return $this->affectationBoAt;
    }

    public function setAffectationBoAt(?\DateTimeInterface $affectationBoAt): self
    {
        $this->affectationBoAt = $affectationBoAt;

        return $this;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(?string $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreetNumbercomplement(): ?string
    {
        return $this->streetNumbercomplement;
    }

    public function setStreetNumbercomplement(?string $streetNumbercomplement): self
    {
        $this->streetNumbercomplement = $streetNumbercomplement;

        return $this;
    }

    public function getApartmentNumber(): ?string
    {
        return $this->apartmentNumber;
    }

    public function setApartmentNumber(?string $apartmentNumber): self
    {
        $this->apartmentNumber = $apartmentNumber;

        return $this;
    }

    public function getApartmentFloor(): ?string
    {
        return $this->apartmentFloor;
    }

    public function setApartmentFloor(?string $apartmentFloor): self
    {
        $this->apartmentFloor = $apartmentFloor;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(?string $streetName): self
    {
        $this->streetName = $streetName;

        return $this;
    }

    public function getIsPieceValidated(): ?bool
    {
        return $this->isPieceValidated;
    }

    public function setIsPieceValidated(?bool $isPieceValidated): self
    {
        $this->isPieceValidated = $isPieceValidated;

        return $this;
    }

    public function getIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(?bool $isPaid): self
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    public function getProductOptionId(): ?string
    {
        return $this->productOptionId;
    }

    public function setProductOptionId(?string $productOptionId): self
    {
        $this->productOptionId = $productOptionId;

        return $this;
    }

    public function getNumPanier(): ?string
    {
        return $this->numPanier;
    }

    public function setNumPanier(?string $numPanier): self
    {
        $this->numPanier = $numPanier;

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

    public function getRefusBoStatus(): ?string
    {
        return $this->refusBoStatus;
    }

    public function setRefusBoStatus(?string $refusBoStatus): self
    {
        $this->refusBoStatus = $refusBoStatus;

        return $this;
    }

    public function getIsListeRouge(): ?bool
    {
        return $this->isListeRouge;
    }

    public function setIsListeRouge(?bool $isListeRouge): self
    {
        $this->isListeRouge = $isListeRouge;

        return $this;
    }

    public function getBundleId(): ?string
    {
        return $this->bundleId;
    }

    public function setBundleId(?string $bundleId): self
    {
        $this->bundleId = $bundleId;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getMobilePhone2(): ?string
    {
        return $this->mobilePhone2;
    }

    public function setMobilePhone2(?string $mobilePhone2): self
    {
        $this->mobilePhone2 = $mobilePhone2;

        return $this;
    }

    public function getRefusBo(): ?int
    {
        return $this->refusBo;
    }

    public function setRefusBo(?int $refusBo): self
    {
        $this->refusBo = $refusBo;

        return $this;
    }

    public function getLocked(): ?string
    {
        return $this->locked;
    }

    public function setLocked(?string $locked): self
    {
        $this->locked = $locked;

        return $this;
    }

    public function getNoteRessentiProspection(): ?string
    {
        return $this->noteRessentiProspection;
    }

    public function setNoteRessentiProspection(?string $noteRessentiProspection): self
    {
        $this->noteRessentiProspection = $noteRessentiProspection;

        return $this;
    }

    public function getCommentaireProspection(): ?string
    {
        return $this->commentaireProspection;
    }

    public function setCommentaireProspection(?string $commentaireProspection): self
    {
        $this->commentaireProspection = $commentaireProspection;

        return $this;
    }

    public function getGesteCo(): ?string
    {
        return $this->gesteCo;
    }

    public function setGesteCo(?string $gesteCo): self
    {
        $this->gesteCo = $gesteCo;

        return $this;
    }

}
