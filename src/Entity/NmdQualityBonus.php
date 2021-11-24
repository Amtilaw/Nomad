<?php

namespace App\Entity;

use App\Repository\NmdQualityBonusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdQualityBonusRepository::class)
 */
class NmdQualityBonus
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
    private $codeCourtier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stepStart;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stepEnd;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $coefficient;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $Sellstatus;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operatorId;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $qualityColor;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $qualityPicto;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $eligibilityRules;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bonusMalusQueryId;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $title;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStepStart(): ?int
    {
        return $this->stepStart;
    }

    public function setStepStart(?int $stepStart): self
    {
        $this->stepStart = $stepStart;

        return $this;
    }

    public function getStepEnd(): ?int
    {
        return $this->stepEnd;
    }

    public function setStepEnd(?int $stepEnd): self
    {
        $this->stepEnd = $stepEnd;

        return $this;
    }

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(?float $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getSellstatus(): ?string
    {
        return $this->Sellstatus;
    }

    public function setSellstatus(?string $Sellstatus): self
    {
        $this->Sellstatus = $Sellstatus;

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

    public function getQualityColor(): ?string
    {
        return $this->qualityColor;
    }

    public function setQualityColor(?string $qualityColor): self
    {
        $this->qualityColor = $qualityColor;

        return $this;
    }

    public function getQualityPicto(): ?string
    {
        return $this->qualityPicto;
    }

    public function setQualityPicto(?string $qualityPicto): self
    {
        $this->qualityPicto = $qualityPicto;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getEligibilityRules(): ?string
    {
        return $this->eligibilityRules;
    }

    public function setEligibilityRules(?string $eligibilityRules): self
    {
        $this->eligibilityRules = $eligibilityRules;

        return $this;
    }

    public function getBonusMalusQueryId(): ?int
    {
        return $this->bonusMalusQueryId;
    }

    public function setBonusMalusQueryId(?int $bonusMalusQueryId): self
    {
        $this->bonusMalusQueryId = $bonusMalusQueryId;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
