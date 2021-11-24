<?php

namespace App\Entity;

use App\Repository\NmdProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdProductRepository::class)
 */
class NmdProduct
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
    private $product;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $categoryId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $organizationPrice;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $organizationNaming;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $organizationCategory;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $pictoMenu;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operatorId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAdditionalProduct;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $openingServiceCharges;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $closingServiceCharges;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price2;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $packageCode;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $remunerationCategorieName;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $remunerationProductName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(?string $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): self
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getOrganizationPrice(): ?int
    {
        return $this->organizationPrice;
    }

    public function setOrganizationPrice(?int $organizationPrice): self
    {
        $this->organizationPrice = $organizationPrice;

        return $this;
    }

    public function getOrganizationNaming(): ?string
    {
        return $this->organizationNaming;
    }

    public function setOrganizationNaming(?string $organizationNaming): self
    {
        $this->organizationNaming = $organizationNaming;

        return $this;
    }

    public function getOrganizationCategory(): ?string
    {
        return $this->organizationCategory;
    }

    public function setOrganizationCategory(?string $organizationCategory): self
    {
        $this->organizationCategory = $organizationCategory;

        return $this;
    }

    public function getPictoMenu(): ?string
    {
        return $this->pictoMenu;
    }

    public function setPictoMenu(?string $pictoMenu): self
    {
        $this->pictoMenu = $pictoMenu;

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

    public function getIsAdditionalProduct(): ?bool
    {
        return $this->isAdditionalProduct;
    }

    public function setIsAdditionalProduct(?bool $isAdditionalProduct): self
    {
        $this->isAdditionalProduct = $isAdditionalProduct;

        return $this;
    }

    public function getOpeningServiceCharges(): ?float
    {
        return $this->openingServiceCharges;
    }

    public function setOpeningServiceCharges(?float $openingServiceCharges): self
    {
        $this->openingServiceCharges = $openingServiceCharges;

        return $this;
    }

    public function getClosingServiceCharges(): ?float
    {
        return $this->closingServiceCharges;
    }

    public function setClosingServiceCharges(?float $closingServiceCharges): self
    {
        $this->closingServiceCharges = $closingServiceCharges;

        return $this;
    }

    public function getPrice2(): ?float
    {
        return $this->price2;
    }

    public function setPrice2(?float $price2): self
    {
        $this->price2 = $price2;

        return $this;
    }

    public function getPackageCode(): ?string
    {
        return $this->packageCode;
    }

    public function setPackageCode(?string $packageCode): self
    {
        $this->packageCode = $packageCode;

        return $this;
    }

    public function getRemunerationCategorieName(): ?string
    {
        return $this->remunerationCategorieName;
    }

    public function setRemunerationCategorieName(?string $remunerationCategorieName): self
    {
        $this->remunerationCategorieName = $remunerationCategorieName;

        return $this;
    }

    public function getRemunerationProductName(): ?string
    {
        return $this->remunerationProductName;
    }

    public function setRemunerationProductName(?string $remunerationProductName): self
    {
        $this->remunerationProductName = $remunerationProductName;

        return $this;
    }
}
