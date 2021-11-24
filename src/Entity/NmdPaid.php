<?php

namespace App\Entity;

use App\Repository\NmdPaidRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdPaidRepository::class)
 */
class NmdPaid
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $PaymentDeadline;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $mobileFixe;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $commandNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $commandCreatedAt;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $SalesPointCode;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $SalesPointLibelle;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $SalesType;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $techno;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $OfferType;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $OfferLibelle;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $Status;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $failureComments;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $SalesEffectiveAt;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $InseeCode;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $HorsZone;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $CustomerFirstName;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $CustomerLastName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $PaymentStatus;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $Derogation;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $MotifDerogation;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $PaymentType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $PaymentAmount;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $PaymentDeadlineDateFormat;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $typeVente;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentDeadline(): ?string
    {
        return $this->PaymentDeadline;
    }

    public function setPaymentDeadline(?string $PaymentDeadline): self
    {
        $this->PaymentDeadline = $PaymentDeadline;

        return $this;
    }

    public function getMobileFixe(): ?string
    {
        return $this->mobileFixe;
    }

    public function setMobileFixe(?string $mobileFixe): self
    {
        $this->mobileFixe = $mobileFixe;

        return $this;
    }

    public function getCommandNumber(): ?string
    {
        return $this->commandNumber;
    }

    public function setCommandNumber(?string $commandNumber): self
    {
        $this->commandNumber = $commandNumber;

        return $this;
    }

    public function getCommandCreatedAt(): ?\DateTimeInterface
    {
        return $this->commandCreatedAt;
    }

    public function setCommandCreatedAt(?\DateTimeInterface $commandCreatedAt): self
    {
        $this->commandCreatedAt = $commandCreatedAt;

        return $this;
    }

    public function getSalesPointCode(): ?string
    {
        return $this->SalesPointCode;
    }

    public function setSalesPointCode(?string $SalesPointCode): self
    {
        $this->SalesPointCode = $SalesPointCode;

        return $this;
    }

    public function getSalesPointLibelle(): ?string
    {
        return $this->SalesPointLibelle;
    }

    public function setSalesPointLibelle(?string $SalesPointLibelle): self
    {
        $this->SalesPointLibelle = $SalesPointLibelle;

        return $this;
    }

    public function getSalesType(): ?string
    {
        return $this->SalesType;
    }

    public function setSalesType(?string $SalesType): self
    {
        $this->SalesType = $SalesType;

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

    public function getOfferType(): ?string
    {
        return $this->OfferType;
    }

    public function setOfferType(?string $OfferType): self
    {
        $this->OfferType = $OfferType;

        return $this;
    }

    public function getOfferLibelle(): ?string
    {
        return $this->OfferLibelle;
    }

    public function setOfferLibelle(?string $OfferLibelle): self
    {
        $this->OfferLibelle = $OfferLibelle;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->Status;
    }

    public function setStatus(?string $Status): self
    {
        $this->Status = $Status;

        return $this;
    }

    public function getFailureComments(): ?string
    {
        return $this->failureComments;
    }

    public function setFailureComments(?string $failureComments): self
    {
        $this->failureComments = $failureComments;

        return $this;
    }

    public function getSalesEffectiveAt(): ?\DateTimeInterface
    {
        return $this->SalesEffectiveAt;
    }

    public function setSalesEffectiveAt(?\DateTimeInterface $SalesEffectiveAt): self
    {
        $this->SalesEffectiveAt = $SalesEffectiveAt;

        return $this;
    }

    public function getInseeCode(): ?string
    {
        return $this->InseeCode;
    }

    public function setInseeCode(?string $InseeCode): self
    {
        $this->InseeCode = $InseeCode;

        return $this;
    }

    public function getHorsZone(): ?string
    {
        return $this->HorsZone;
    }

    public function setHorsZone(?string $HorsZone): self
    {
        $this->HorsZone = $HorsZone;

        return $this;
    }

    public function getCustomerFirstName(): ?string
    {
        return $this->CustomerFirstName;
    }

    public function setCustomerFirstName(?string $CustomerFirstName): self
    {
        $this->CustomerFirstName = $CustomerFirstName;

        return $this;
    }

    public function getCustomerLastName(): ?string
    {
        return $this->CustomerLastName;
    }

    public function setCustomerLastName(?string $CustomerLastName): self
    {
        $this->CustomerLastName = $CustomerLastName;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->PaymentStatus;
    }

    public function setPaymentStatus(?string $PaymentStatus): self
    {
        $this->PaymentStatus = $PaymentStatus;

        return $this;
    }

    public function getDerogation(): ?string
    {
        return $this->Derogation;
    }

    public function setDerogation(?string $Derogation): self
    {
        $this->Derogation = $Derogation;

        return $this;
    }

    public function getMotifDerogation(): ?string
    {
        return $this->MotifDerogation;
    }

    public function setMotifDerogation(?string $MotifDerogation): self
    {
        $this->MotifDerogation = $MotifDerogation;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->PaymentType;
    }

    public function setPaymentType(?string $PaymentType): self
    {
        $this->PaymentType = $PaymentType;

        return $this;
    }

    public function getPaymentAmount(): ?int
    {
        return $this->PaymentAmount;
    }

    public function setPaymentAmount(?int $PaymentAmount): self
    {
        $this->PaymentAmount = $PaymentAmount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(?\DateTimeInterface $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

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

    public function getPaymentDeadlineDateFormat(): ?\DateTimeInterface
    {
        return $this->PaymentDeadlineDateFormat;
    }

    public function setPaymentDeadlineDateFormat(?\DateTimeInterface $PaymentDeadlineDateFormat): self
    {
        $this->PaymentDeadlineDateFormat = $PaymentDeadlineDateFormat;

        return $this;
    }

    public function getTypeVente(): ?string
    {
        return $this->typeVente;
    }

    public function setTypeVente(?string $typeVente): self
    {
        $this->typeVente = $typeVente;

        return $this;
    }
}
