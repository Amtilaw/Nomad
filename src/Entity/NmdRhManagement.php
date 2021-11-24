<?php

namespace App\Entity;

use App\Repository\NmdRhManagementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdRhManagementRepository::class)
 */
class NmdRhManagement
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
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $iban;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $swift;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $bankName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $bankAdress;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $carteVitalePicture;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $ribPicture;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cniPicture;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $contractType;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $contractSignedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $contractStartAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $contractEndAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $testPeriodStartAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $testPeriodEndAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $integrationStartAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $integrationEndAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $contractHoursByweek;

    /**
     * @ORM\Column(type="float" , nullable=true)
     */
    private $salaryAmountBrut;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fraisAmount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $AutresAvantagesAmount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $driverLicencePicture;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vehiculeId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAccessSalaire;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $commision;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $testPeriod2StartAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $testPeriod2EndAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $notifierFinTestPeriod1At;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $notifierFinTestPeriod2At;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $signatureContrat;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthDayAt;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $nationalite;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $numeroSecuriteSociale;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tauxChargesPatronales;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $compteurCpAnneeN;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $compteurCpAnneeNMoinsun;

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

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(?string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getSwift(): ?string
    {
        return $this->swift;
    }

    public function setSwift(?string $swift): self
    {
        $this->swift = $swift;

        return $this;
    }

    public function getBankName(): ?string
    {
        return $this->bankName;
    }

    public function setBankName(?string $bankName): self
    {
        $this->bankName = $bankName;

        return $this;
    }

    public function getBankAdress(): ?string
    {
        return $this->bankAdress;
    }

    public function setBankAdress(?string $bankAdress): self
    {
        $this->bankAdress = $bankAdress;

        return $this;
    }

    public function getCarteVitalePicture(): ?string
    {
        return $this->carteVitalePicture;
    }

    public function setCarteVitalePicture(?string $carteVitalePicture): self
    {
        $this->carteVitalePicture = $carteVitalePicture;

        return $this;
    }

    public function getCniPicture(): ?string
    {
        return $this->cniPicture;
    }

    public function setCniPicture(?string $cniPicture): self
    {
        $this->cniPicture = $cniPicture;

        return $this;
    }

    public function getRibPicture(): ?string
    {
        return $this->ribPicture;
    }

    public function setRibPicture(?string $ribPicture): self
    {
        $this->ribPicture = $ribPicture;

        return $this;
    }

    public function getContractType(): ?string
    {
        return $this->contractType;
    }

    public function setContractType(?string $contractType): self
    {
        $this->contractType = $contractType;

        return $this;
    }

    public function getContractSignedAt(): ?\DateTimeInterface
    {
        return $this->contractSignedAt;
    }

    public function setContractSignedAt(?\DateTimeInterface $contractSignedAt): self
    {
        $this->contractSignedAt = $contractSignedAt;

        return $this;
    }

    public function getContractStartAt(): ?\DateTimeInterface
    {
        return $this->contractStartAt;
    }

    public function setContractStartAt(?\DateTimeInterface $contractStartAt): self
    {
        $this->contractStartAt = $contractStartAt;

        return $this;
    }

    public function getContractEndAt(): ?\DateTimeInterface
    {
        return $this->contractEndAt;
    }

    public function setContractEndAt(?\DateTimeInterface $contractEndAt): self
    {
        $this->contractEndAt = $contractEndAt;

        return $this;
    }

    public function getTestPeriodStartAt(): ?\DateTimeInterface
    {
        return $this->testPeriodStartAt;
    }

    public function setTestPeriodStartAt(?\DateTimeInterface $testPeriodStartAt): self
    {
        $this->testPeriodStartAt = $testPeriodStartAt;

        return $this;
    }

    public function getTestPeriodEndAt(): ?\DateTimeInterface
    {
        return $this->testPeriodEndAt;
    }

    public function setTestPeriodEndAt(?\DateTimeInterface $testPeriodEndAt): self
    {
        $this->testPeriodEndAt = $testPeriodEndAt;

        return $this;
    }

    public function getIntegrationStartAt(): ?\DateTimeInterface
    {
        return $this->integrationStartAt;
    }

    public function setIntegrationStartAt(?\DateTimeInterface $integrationStartAt): self
    {
        $this->integrationStartAt = $integrationStartAt;

        return $this;
    }

    public function getIntegrationEndAt(): ?\DateTimeInterface
    {
        return $this->integrationEndAt;
    }

    public function setIntegrationEndAt(?\DateTimeInterface $integrationEndAt): self
    {
        $this->integrationEndAt = $integrationEndAt;

        return $this;
    }

    public function getContractHoursByweek(): ?int
    {
        return $this->contractHoursByweek;
    }

    public function setContractHoursByweek(?int $contractHoursByweek): self
    {
        $this->contractHoursByweek = $contractHoursByweek;

        return $this;
    }

    public function getSalaryAmountBrut(): ?float
    {
        return $this->salaryAmountBrut;
    }

    public function setSalaryAmountBrut(?float $salaryAmountBrut): self
    {
        $this->salaryAmountBrut = $salaryAmountBrut;

        return $this;
    }

    public function getFraisAmount(): ?int
    {
        return $this->fraisAmount;
    }

    public function setFraisAmount(?int $fraisAmount): self
    {
        $this->fraisAmount = $fraisAmount;

        return $this;
    }

    public function getAutresAvantagesAmount(): ?int
    {
        return $this->AutresAvantagesAmount;
    }

    public function setAutresAvantagesAmount(?int $AutresAvantagesAmount): self
    {
        $this->AutresAvantagesAmount = $AutresAvantagesAmount;

        return $this;
    }

    public function getDriverLicencePicture(): ?string
    {
        return $this->driverLicencePicture;
    }

    public function setDriverLicencePicture(?string $driverLicencePicture): self
    {
        $this->driverLicencePicture = $driverLicencePicture;

        return $this;
    }

    public function getVehiculeId()
    {
        return $this->vehiculeId;
    }

    public function setVehiculeId($vehiculeId): self
    {
        $this->vehiculeId = $vehiculeId;

        return $this;
    }

    public function getIsAccessSalaire(): ?bool
    {
        return $this->isAccessSalaire;
    }

    public function setIsAccessSalaire(?bool $isAccessSalaire): self
    {
        $this->isAccessSalaire = $isAccessSalaire;

        return $this;
    }

    public function getCommision(): ?float
    {
        return $this->commision;
    }

    public function setCommision(?float $commision): self
    {
        $this->commision = $commision;

        return $this;
    }

    public function getTestPeriod2StartAt(): ?\DateTimeInterface
    {
        return $this->testPeriod2StartAt;
    }

    public function setTestPeriod2StartAt(?\DateTimeInterface $testPeriod2StartAt): self
    {
        $this->testPeriod2StartAt = $testPeriod2StartAt;

        return $this;
    }

    public function getTestPeriod2EndAt(): ?\DateTimeInterface
    {
        return $this->testPeriod2EndAt;
    }

    public function setTestPeriod2EndAt(?\DateTimeInterface $testPeriod2EndAt): self
    {
        $this->testPeriod2EndAt = $testPeriod2EndAt;

        return $this;
    }

    public function getNotifierFinTestPeriod1At(): ?\DateTimeInterface
    {
        return $this->notifierFinTestPeriod1At;
    }

    public function setNotifierFinTestPeriod1At(?\DateTimeInterface $notifierFinTestPeriod1At): self
    {
        $this->notifierFinTestPeriod1At = $notifierFinTestPeriod1At;

        return $this;
    }

    public function getNotifierFinTestPeriod2At(): ?\DateTimeInterface
    {
        return $this->notifierFinTestPeriod2At;
    }

    public function setNotifierFinTestPeriod2At(?\DateTimeInterface $notifierFinTestPeriod2At): self
    {
        $this->notifierFinTestPeriod2At = $notifierFinTestPeriod2At;

        return $this;
    }

    public function getSignatureContrat(): ?string
    {
        return $this->signatureContrat;
    }

    public function setSignatureContrat(?string $signatureContrat): self
    {
        $this->signatureContrat = $signatureContrat;

        return $this;
    }

    public function getBirthDayAt(): ?\DateTimeInterface
    {
        return $this->birthDayAt;
    }

    public function setBirthDayAt(?\DateTimeInterface $birthDayAt): self
    {
        $this->birthDayAt = $birthDayAt;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(?string $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getNumeroSecuriteSociale(): ?string
    {
        return $this->numeroSecuriteSociale;
    }

    public function setNumeroSecuriteSociale(?string $numeroSecuriteSociale): self
    {
        $this->numeroSecuriteSociale = $numeroSecuriteSociale;

        return $this;
    }

    public function getTauxChargesPatronales(): ?float
    {
        return $this->tauxChargesPatronales;
    }

    public function setTauxChargesPatronales(?float $tauxChargesPatronales): self
    {
        $this->tauxChargesPatronales = $tauxChargesPatronales;

        return $this;
    }

    public function getCompteurCpAnneeN(): ?float
    {
        return $this->compteurCpAnneeN;
    }

    public function setCompteurCpAnneeN(?float $compteurCpAnneeN): self
    {
        $this->compteurCpAnneeN = $compteurCpAnneeN;

        return $this;
    }

    public function getCompteurCpAnneeNMoinsun(): ?float
    {
        return $this->compteurCpAnneeNMoinsun;
    }

    public function setCompteurCpAnneeNMoinsun(?float $compteurCpAnneeNMoinsun): self
    {
        $this->compteurCpAnneeNMoinsun = $compteurCpAnneeNMoinsun;

        return $this;
    }
}
