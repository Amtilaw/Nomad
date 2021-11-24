<?php

namespace App\Entity;

use App\Repository\NmdOuvertureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdOuvertureRepository::class)
 */
class NmdOuverture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

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
    private $codeHexc;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $clusterCode;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $clusterLibelle;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $cpvCourtier;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $libelleCourtier;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $codeInsee;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reouv;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $flagPreco;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCdviKo;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $codeCdviKo;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $libelleCdviKo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEligibiOk;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $gestInfr;

    public function getId(): ?int
    {
        return $this->id;
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

    
    public function getCodeHexc(): ?string
    {
        return $this->codeHexc;
    }

    public function setCodeHexc(?string $codeHexc): self
    {
        $this->codeHexc = $codeHexc;

        return $this;
    }

    public function getClusterCode(): ?string
    {
        return $this->clusterCode;
    }

    public function setClusterCode(?string $clusterCode): self
    {
        $this->clusterCode = $clusterCode;

        return $this;
    }

    public function getClusterLibelle(): ?string
    {
        return $this->clusterLibelle;
    }

    public function setClusterLibelle(?string $clusterLibelle): self
    {
        $this->clusterLibelle = $clusterLibelle;

        return $this;
    }

    public function getCpvCourtier(): ?string
    {
        return $this->cpvCourtier;
    }

    public function setCpvCourtier(?string $cpvCourtier): self
    {
        $this->cpvCourtier = $cpvCourtier;

        return $this;
    }

    public function getLibelleCourtier(): ?string
    {
        return $this->libelleCourtier;
    }

    public function setLibelleCourtier(?string $libelleCourtier): self
    {
        $this->libelleCourtier = $libelleCourtier;

        return $this;
    }

    public function getCodeInsee(): ?string
    {
        return $this->codeInsee;
    }

    public function setCodeInsee(?string $codeInsee): self
    {
        $this->codeInsee = $codeInsee;

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

    public function getFlagPreco(): ?string
    {
        return $this->flagPreco;
    }

    public function setFlagPreco(?string $flagPreco): self
    {
        $this->flagPreco = $flagPreco;

        return $this;
    }

    public function getDateCdviKo(): ?\DateTimeInterface
    {
        return $this->dateCdviKo;
    }

    public function setDateCdviKo(?\DateTimeInterface $dateCdviKo): self
    {
        $this->dateCdviKo = $dateCdviKo;

        return $this;
    }

    public function getCodeCdviKo(): ?string
    {
        return $this->codeCdviKo;
    }

    public function setCodeCdviKo(?string $codeCdviKo): self
    {
        $this->codeCdviKo = $codeCdviKo;

        return $this;
    }

    public function getLibelleCdviKo(): ?string
    {
        return $this->libelleCdviKo;
    }

    public function setLibelleCdviKo(?string $libelleCdviKo): self
    {
        $this->libelleCdviKo = $libelleCdviKo;

        return $this;
    }

    public function getDateEligibiOk(): ?\DateTimeInterface
    {
        return $this->dateEligibiOk;
    }

    public function setDateEligibiOk(?\DateTimeInterface $dateEligibiOk): self
    {
        $this->dateEligibiOk = $dateEligibiOk;

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

    public function getGestInfr(): ?string
    {
        return $this->gestInfr;
    }

    public function setGestInfr(?string $gestInfr): self
    {
        $this->gestInfr = $gestInfr;

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
}
