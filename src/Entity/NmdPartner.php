<?php

namespace App\Entity;

use App\Repository\NmdPartnerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdPartnerRepository::class)
 */
class NmdPartner
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
    private $myaccountId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */

    private $partner;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mail1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mail2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mail3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $responsable1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $responsable2;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $cpvCourtier;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $operatorId;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tauxChargesPatronalesByCompany;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apiToken;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $openAt;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $closeAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMyaccountId(): ?int
    {
        return $this->myaccountId;
    }

    public function setMyaccountId(?int $myaccountId): self
    {
        $this->myaccountId = $myaccountId;

        return $this;
    }

    public function getPartner(): ?string
    {
        return $this->partner;
    }

    public function setPartner(?string $partner): self
    {
        $this->partner = $partner;

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

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;
        
        return $this;
    }

    public function getMail1(): ?string
    {
        return $this->mail1;
    }

    public function setMail1(?string $mail1): self
    {
        $this->mail1 = $mail1;

        return $this;
    }

    public function getMail2(): ?string
    {
        return $this->mail2;
    }

    public function setMail2(?string $mail2): self
    {
        $this->mail2 = $mail2;

        return $this;
    }

    public function getMail3(): ?string
    {
        return $this->mail3;
    }

    public function setMail3(?string $mail3): self
    {
        $this->mail3 = $mail3;

        return $this;
    }

    public function getResponsable1(): ?string
    {
        return $this->responsable1;
    }

    public function setResponsable1(?string $responsable1): self
    {
        $this->responsable1 = $responsable1;

        return $this;
    }

    public function getResponsable2(): ?string
    {
        return $this->responsable2;
    }

    public function setResponsable2(?string $responsable2): self
    {
        $this->responsable2 = $responsable2;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

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

    public function getOperatorId(): ?string
    {
        return $this->operatorId;
    }

    public function setOperatorId(?string $operatorId): self
    {
        $this->operatorId = $operatorId;

        return $this;
    }

    public function getTauxChargesPatronalesByCompany(): ?float
    {
        return $this->tauxChargesPatronalesByCompany;
    }

    public function setTauxChargesPatronalesByCompany(?float $tauxChargesPatronalesByCompany): self
    {
        $this->tauxChargesPatronalesByCompany = $tauxChargesPatronalesByCompany;

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    public function getOpenAt(): ?\DateTimeInterface
    {
        return $this->openAt;
    }

    public function setOpenAt(?\DateTimeInterface $openAt): self
    {
        $this->openAt = $openAt;

        return $this;
    }

    public function getCloseAt(): ?\DateTimeInterface
    {
        return $this->closeAt;
    }

    public function setCloseAt(?\DateTimeInterface $closeAt): self
    {
        $this->closeAt = $closeAt;

        return $this;
    }
}
