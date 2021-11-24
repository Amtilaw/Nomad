<?php

namespace App\Entity;

use App\Repository\NmdConfigEtlRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdConfigEtlRepository::class)
 */
class NmdConfigEtl
{
/**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $driver;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $host;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $port;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $EtlDatabase;

    /**
     * @ORM\Column(type="string", length=0, nullable=true)
     */
    private $EtlUsername;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $charset;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $collation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDriver(): ?string
    {
        return $this->driver;
    }

    public function setDriver(?string $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(?string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getPort(): ?string
    {
        return $this->port;
    }

    public function setPort(?string $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function getEtlDatabase(): ?string
    {
        return $this->EtlDatabase;
    }

    public function setEtlDatabase(?string $EtlDatabase): self
    {
        $this->EtlDatabase = $EtlDatabase;

        return $this;
    }

    public function getEtlUsername(): ?string
    {
        return $this->EtlUsername;
    }

    public function setEtlUsername(?string $EtlUsername): self
    {
        $this->EtlUsername = $EtlUsername;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCharset(): ?string
    {
        return $this->charset;
    }

    public function setCharset(?string $charset): self
    {
        $this->charset = $charset;

        return $this;
    }

    public function getCollation(): ?string
    {
        return $this->collation;
    }

    public function setCollation(?string $collation): self
    {
        $this->collation = $collation;

        return $this;
    }
}
