<?php

namespace App\Entity;

use App\Repository\NmdValorisationObjectifRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdValorisationObjectifRepository::class)
 */
class NmdValorisationObjectif
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
    private $cpv;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_user;

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
    private $moneyValue;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $percentValue;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $profondeur_action;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $productId;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(?int $id_user): self
    {
        $this->id_user = $id_user;

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

    public function getMoneyValue(): ?float
    {
        return $this->moneyValue;
    }

    public function setMoneyValue(?float $moneyValue): self
    {
        $this->moneyValue = $moneyValue;

        return $this;
    }

    public function getPercentValue(): ?float
    {
        return $this->percentValue;
    }

    public function setPercentValue(?float $percentValue): self
    {
        $this->percentValue = $percentValue;

        return $this;
    }

    public function getProfondeurAction(): ?string
    {
        return $this->profondeur_action;
    }

    public function setProfondeurAction(?string $profondeur_action): self
    {
        $this->profondeur_action = $profondeur_action;

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
}
