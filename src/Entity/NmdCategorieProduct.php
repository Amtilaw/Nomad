<?php

namespace App\Entity;

use App\Repository\NmdCategorieProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdCategorieProductRepository::class)
 */
class NmdCategorieProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operator_id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAdditionalProduct;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $parent;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $internName;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActivePaySimulator;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOperatorId(): ?int
    {
        return $this->operator_id;
    }

    public function setOperatorId(?int $operator_id): self
    {
        $this->operator_id = $operator_id;

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

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function setParent(?string $parent): self
    {
        $this->parent = $parent;

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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getInternName(): ?string
    {
        return $this->internName;
    }

    public function setInternName(?string $internName): self
    {
        $this->internName = $internName;

        return $this;
    }

    public function getIsActivePaySimulator(): ?bool
    {
        return $this->isActivePaySimulator;
    }

    public function setIsActivePaySimulator(?bool $isActivePaySimulator): self
    {
        $this->isActivePaySimulator = $isActivePaySimulator;

        return $this;
    }
}
