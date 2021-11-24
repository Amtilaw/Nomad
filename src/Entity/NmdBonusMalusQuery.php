<?php

namespace App\Entity;

use App\Repository\NmdBonusMalusQueryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdBonusMalusQueryRepository::class)
 */
class NmdBonusMalusQuery
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sqlQuery;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $tableName;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operatorId;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sqlRows;

    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function getSqlQuery(): ?string
    {
        return $this->sqlQuery;
    }

    public function setSqlQuery(?string $sqlQuery): self
    {
        $this->sqlQuery = $sqlQuery;

        return $this;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function setTableName(?string $tableName): self
    {
        $this->tableName = $tableName;

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

    public function getOperatorId(): ?int
    {
        return $this->operatorId;
    }

    public function setOperatorId(?int $operatorId): self
    {
        $this->operatorId = $operatorId;

        return $this;
    }

    public function getSqlRows(): ?string
    {
        return $this->sqlRows;
    }

    public function setSqlRows(?string $sqlRows): self
    {
        $this->sqlRows = $sqlRows;

        return $this;
    }
}
