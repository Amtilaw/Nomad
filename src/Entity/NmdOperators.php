<?php

namespace App\Entity;

use App\Repository\NmdOperatorsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdOperatorsRepository::class)
 */
class NmdOperators
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operatorId;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $pict;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlSouscription;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isValidationBoRequired;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isBtoB;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isBtoC;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operatorObjectif;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isContractIdentifierRequired;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPanierIdentifierRequiredBo;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $pathToProdEtl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pathToPrisesEtl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

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

    public function getPict(): ?string
    {
        return $this->pict;
    }

    public function setPict(?string $pict): self
    {
        $this->pict = $pict;

        return $this;
    }

    public function getUrlSouscription(): ?string
    {
        return $this->urlSouscription;
    }

    public function setUrlSouscription(?string $urlSouscription): self
    {
        $this->urlSouscription = $urlSouscription;

        return $this;
    }

    public function getIsValidationBoRequired(): ?bool
    {
        return $this->isValidationBoRequired;
    }

    public function setIsValidationBoRequired(?bool $isValidationBoRequired): self
    {
        $this->isValidationBoRequired = $isValidationBoRequired;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getIsBtoB(): ?bool
    {
        return $this->isBtoB;
    }

    public function setIsBtoB(?bool $isBtoB): self
    {
        $this->isBtoB = $isBtoB;

        return $this;
    }

    public function getIsBtoC(): ?bool
    {
        return $this->isBtoC;
    }

    public function setIsBtoC(?bool $isBtoC): self
    {
        $this->isBtoC = $isBtoC;

        return $this;
    }

    public function getOperatorObjectif(): ?int
    {
        return $this->operatorObjectif;
    }

    public function setOperatorObjectif(?int $operatorObjectif): self
    {
        $this->operatorObjectif = $operatorObjectif;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsContractIdentifierRequired(): ?bool
    {
        return $this->isContractIdentifierRequired;
    }

    public function setIsContractIdentifierRequired(?bool $isContractIdentifierRequired): self
    {
        $this->isContractIdentifierRequired = $isContractIdentifierRequired;

        return $this;
    }

    public function getIsPanierIdentifierRequiredBo(): ?bool
    {
        return $this->isPanierIdentifierRequiredBo;
    }

    public function setIsPanierIdentifierRequiredBo(?bool $isPanierIdentifierRequiredBo): self
    {
        $this->isPanierIdentifierRequiredBo = $isPanierIdentifierRequiredBo;

        return $this;
    }

    public function getPathToProdEtl(): ?string
    {
        return $this->pathToProdEtl;
    }

    public function setPathToProdEtl(?string $pathToProdEtl): self
    {
        $this->pathToProdEtl = $pathToProdEtl;

        return $this;
    }

    public function getPathToPrisesEtl(): ?string
    {
        return $this->pathToPrisesEtl;
    }

    public function setPathToPrisesEtl(?string $pathToPrisesEtl): self
    {
        $this->pathToPrisesEtl = $pathToPrisesEtl;

        return $this;
    }
}
