<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean" , nullable=true)
     */
    private $IsEnabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sector;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $zone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $pic;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ape;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siren;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $departement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $societeId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dashRights;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ordre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $myIp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $countryNameIp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $countryCodeIp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $regionCodeIp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $regionNameIp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cityNameIp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $latitudeIp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longitudeIp;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $prestataireId;

    /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $partnerId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parent;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $confirmationToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $passwordRequestedAt;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $cpvCourtierExploitant;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $auteur;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $phrase;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ticketAuth;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $operatorId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $userCreatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $userUpdatedAt;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $userCreatedBy;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $userUpdatedBy;

    /**
     * @ORM\Column(type="float", options={"default" : 0}, nullable=false)
     */
    private $tvaAmount;

    /**
     * @ORM\Column(type="float", options={"default" : 1}, nullable=false)
     */
    private $pointValue;

    /**
     * @ORM\Column(type="integer",  options={"default" : 0}, nullable=false)
     */
    private $fileAttente;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isOnBreak;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $IsAuthorizedToMigrables;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $disabledAt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $disabledBy;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $disabledWhy;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isIncomingCall;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isOutGoingCall;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isEditUserInView;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $emailBoCompany;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAuthorizedToExportPrisesNeuves;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAuthorizedToExportPrisesParc;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $isValidationBoRequiredByoperators;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAuthorizedToCreateUser;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAuthorizedToEditUser;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $isBtobByoperators;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $isBtocByoperators;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAllowedToSendMessageToSuperior;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAllowedToSendMessageToFacturation;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isEditOperatorInView;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPushBankInfos;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPushJustificatifsInfos;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isAllowedAccessSalaire;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $emailComptaCompany;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $signature;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActived;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastActionAt;


    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $streetListAffectation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $errorConnectionCounter;






    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(?string $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(?string $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getPic(): ?string
    {
        return $this->pic;
    }

    public function setPic(?string $pic): self
    {
        $this->pic = $pic;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getApe(): ?string
    {
        return $this->ape;
    }

    public function setApe(?string $ape): self
    {
        $this->ape = $ape;

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(?string $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getSocieteId(): ?int
    {
        return $this->societeId;
    }

    public function setSocieteId(?int $societeId): self
    {
        $this->societeId = $societeId;

        return $this;
    }

    public function getDashRights(): ?string
    {
        return $this->dashRights;
    }

    public function setDashRights(?string $dashRights): self
    {
        $this->dashRights = $dashRights;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getMyIp(): ?string
    {
        return $this->myIp;
    }

    public function setMyIp(?string $myIp): self
    {
        $this->myIp = $myIp;

        return $this;
    }

    public function getCountryNameIp(): ?string
    {
        return $this->countryNameIp;
    }

    public function setCountryNameIp(?string $countryNameIp): self
    {
        $this->countryNameIp = $countryNameIp;

        return $this;
    }

    public function getCountryCodeIp(): ?string
    {
        return $this->countryCodeIp;
    }

    public function setCountryCodeIp(?string $countryCodeIp): self
    {
        $this->countryCodeIp = $countryCodeIp;

        return $this;
    }

    public function getRegionCodeIp(): ?string
    {
        return $this->regionCodeIp;
    }

    public function setRegionCodeIp(?string $regionCodeIp): self
    {
        $this->regionCodeIp = $regionCodeIp;

        return $this;
    }

    public function getRegionNameIp(): ?string
    {
        return $this->regionNameIp;
    }

    public function setRegionNameIp(?string $regionNameIp): self
    {
        $this->regionNameIp = $regionNameIp;

        return $this;
    }

    public function getCityNameIp(): ?string
    {
        return $this->cityNameIp;
    }

    public function setCityNameIp(?string $cityNameIp): self
    {
        $this->cityNameIp = $cityNameIp;

        return $this;
    }

    public function getLatitudeIp(): ?string
    {
        return $this->latitudeIp;
    }

    public function setLatitudeIp(?string $latitudeIp): self
    {
        $this->latitudeIp = $latitudeIp;

        return $this;
    }

    public function getLongitudeIp(): ?string
    {
        return $this->longitudeIp;
    }

    public function setLongitudeIp(?string $longitudeIp): self
    {
        $this->longitudeIp = $longitudeIp;

        return $this;
    }

    public function getPrestataireId(): ?string
    {
        return $this->prestataireId;
    }

    public function setPrestataireId(?string $prestataireId): self
    {
        $this->prestataireId = $prestataireId;

        return $this;
    }

    public function getPartnerId(): ?int
    {
        return $this->partnerId;
    }

    public function setPartnerId(int $partnerId): self
    {
        $this->partnerId = $partnerId;

        return $this;
    }

    public function getParent(): ?int
    {
        return $this->parent;
    }

    public function setParent(?int $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->IsEnabled;
    }

    public function setIsEnabled(bool $IsEnabled): self
    {
        $this->IsEnabled = $IsEnabled;

        return $this;
    }


    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    public function getPasswordRequestedAt(): ?\DateTimeInterface
    {
        return $this->passwordRequestedAt;
    }

    public function setPasswordRequestedAt(?\DateTimeInterface $passwordRequestedAt): self
    {
        $this->passwordRequestedAt = $passwordRequestedAt;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getCpvCourtierExploitant(): ?string
    {
        return $this->cpvCourtierExploitant;
    }

    /**
     * @see UserInterface
     */
    public function setCpvCourtierExploitant(?string $cpvCourtierExploitant): self
    {
        $this->cpvCourtierExploitant = $cpvCourtierExploitant;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(?string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getPhrase(): ?string
    {
        return $this->phrase;
    }

    public function setPhrase(?string $phrase): self
    {
        $this->phrase = $phrase;

        return $this;
    }

    public function getTicketAuth(): ?bool
    {
        return $this->ticketAuth;
    }

    public function setTicketAuth(?bool $ticketAuth): self
    {
        $this->ticketAuth = $ticketAuth;

        return $this;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->userCreatedAt;
    }

    public function setCreatedAt(?\DateTimeInterface $userCreatedAt): self
    {
        $this->userCreatedAt = $userCreatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->userUpdatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $userUpdatedAt): self
    {
        $this->userUpdatedAt = $userUpdatedAt;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->userCreatedBy;
    }

    public function setCreatedBy(?string $userCreatedBy): self
    {
        $this->userCreatedBy = $userCreatedBy;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->userUpdatedBy;
    }

    public function setUpdatedBy(?string $userUpdatedBy): self
    {
        $this->userUpdatedBy = $userUpdatedBy;

        return $this;
    }

    public function getTvaAmount(): ?float
    {
        return $this->tvaAmount;
    }

    public function setTvaAmount(?float $tvaAmount): self
    {
        $this->tvaAmount = $tvaAmount;

        return $this;
    }

    public function getPointValue(): ?float
    {
        return $this->pointValue;
    }

    public function setPointValue(?float $pointValue): self
    {
        $this->pointValue = $pointValue;

        return $this;
    }

    public function getFileAttente(): ?int
    {
        return $this->fileAttente;
    }

    public function setFileAttente(int $fileAttente): self
    {
        $this->fileAttente = $fileAttente;

        return $this;
    }

    public function getIsOnBreak(): ?bool
    {
        return $this->isOnBreak;
    }

    public function setIsOnBreak(?bool $isOnBreak): self
    {
        $this->isOnBreak = $isOnBreak;

        return $this;
    }

    public function getIsAuthorizedToMigrables(): ?bool
    {
        return $this->IsAuthorizedToMigrables;
    }

    public function setIsAuthorizedToMigrables(?bool $IsAuthorizedToMigrables): self
    {
        $this->IsAuthorizedToMigrables = $IsAuthorizedToMigrables;

        return $this;
    }

    public function getDisabledAt(): ?\DateTimeInterface
    {
        return $this->disabledAt;
    }

    public function setDisabledAt(?\DateTimeInterface $disabledAt): self
    {
        $this->disabledAt = $disabledAt;

        return $this;
    }

    public function getDisabledBy(): ?string
    {
        return $this->disabledBy;
    }

    public function setDisabledBy(?string $disabledBy): self
    {
        $this->disabledBy = $disabledBy;

        return $this;
    }

    public function getDisabledWhy(): ?string
    {
        return $this->disabledWhy;
    }

    public function setDisabledWhy(?string $disabledWhy): self
    {
        $this->disabledWhy = $disabledWhy;

        return $this;
    }

    public function getIsIncomingCall(): ?bool
    {
        return $this->isIncomingCall;
    }

    public function setIsIncomingCall(?bool $isIncomingCall): self
    {
        $this->isIncomingCall = $isIncomingCall;

        return $this;
    }

    public function getIsOutGoingCall(): ?bool
    {
        return $this->isOutGoingCall;
    }

    public function setIsOutGoingCall(?bool $isOutGoingCall): self
    {
        $this->isOutGoingCall = $isOutGoingCall;

        return $this;
    }

    public function getIsEditUserInView(): ?bool
    {
        return $this->isEditUserInView;
    }

    public function setIsEditUserInView(?bool $isEditUserInView): self
    {
        $this->isEditUserInView = $isEditUserInView;

        return $this;
    }

    public function getEmailBoCompany(): ?string
    {
        return $this->emailBoCompany;
    }

    public function setEmailBoCompany(?string $emailBoCompany): self
    {
        $this->emailBoCompany = $emailBoCompany;

        return $this;
    }

    public function getIsAuthorizedToExportPrisesNeuves(): ?bool
    {
        return $this->isAuthorizedToExportPrisesNeuves;
    }

    public function setIsAuthorizedToExportPrisesNeuves(?bool $isAuthorizedToExportPrisesNeuves): self
    {
        $this->isAuthorizedToExportPrisesNeuves = $isAuthorizedToExportPrisesNeuves;

        return $this;
    }

    public function getIsAuthorizedToExportPrisesParc(): ?bool
    {
        return $this->isAuthorizedToExportPrisesParc;
    }

    public function setIsAuthorizedToExportPrisesParc(?bool $isAuthorizedToExportPrisesParc): self
    {
        $this->isAuthorizedToExportPrisesParc = $isAuthorizedToExportPrisesParc;

        return $this;
    }

    public function getIsValidationBoRequiredByoperators(): ?string
    {
        return $this->isValidationBoRequiredByoperators;
    }

    public function setIsValidationBoRequiredByoperators(?string $isValidationBoRequiredByoperators): self
    {
        $this->isValidationBoRequiredByoperators = $isValidationBoRequiredByoperators;

        return $this;
    }

    public function getIsAuthorizedToCreateUser(): ?bool
    {
        return $this->isAuthorizedToCreateUser;
    }

    public function setIsAuthorizedToCreateUser(?bool $isAuthorizedToCreateUser): self
    {
        $this->isAuthorizedToCreateUser = $isAuthorizedToCreateUser;

        return $this;
    }

    public function getIsAuthorizedToEditUser(): ?bool
    {
        return $this->isAuthorizedToEditUser;
    }

    public function setIsAuthorizedToEditUser(?bool $isAuthorizedToEditUser): self
    {
        $this->isAuthorizedToEditUser = $isAuthorizedToEditUser;

        return $this;
    }

    public function getIsBtobByoperators(): ?string
    {
        return $this->isBtobByoperators;
    }

    public function setIsBtobByoperators(?string $isBtobByoperators): self
    {
        $this->isBtobByoperators = $isBtobByoperators;

        return $this;
    }

    public function getIsBtocByoperators(): ?string
    {
        return $this->isBtocByoperators;
    }

    public function setIsBtocByoperators(?string $isBtocByoperators): self
    {
        $this->isBtocByoperators = $isBtocByoperators;

        return $this;
    }

    public function getIsAllowedToSendMessageToSuperior(): ?bool
    {
        return $this->isAllowedToSendMessageToSuperior;
    }

    public function setIsAllowedToSendMessageToSuperior(?bool $isAllowedToSendMessageToSuperior): self
    {
        $this->isAllowedToSendMessageToSuperior = $isAllowedToSendMessageToSuperior;

        return $this;
    }

    public function getIsAllowedToSendMessageToFacturation(): ?bool
    {
        return $this->isAllowedToSendMessageToFacturation;
    }

    public function setIsAllowedToSendMessageToFacturation(?bool $isAllowedToSendMessageToFacturation): self
    {
        $this->isAllowedToSendMessageToFacturation = $isAllowedToSendMessageToFacturation;

        return $this;
    }

    public function getIsEditOperatorInView(): ?bool
    {
        return $this->isEditOperatorInView;
    }

    public function setIsEditOperatorInView(?bool $isEditOperatorInView): self
    {
        $this->isEditOperatorInView = $isEditOperatorInView;

        return $this;
    }

    public function getIsPushBankInfos(): ?bool
    {
        return $this->isPushBankInfos;
    }

    public function setIsPushBankInfos(?bool $isPushBankInfos): self
    {
        $this->isPushBankInfos = $isPushBankInfos;

        return $this;
    }

    public function getIsPushJustificatifsInfos(): ?bool
    {
        return $this->isPushJustificatifsInfos;
    }

    public function setIsPushJustificatifsInfos(?bool $isPushJustificatifsInfos): self
    {
        $this->isPushJustificatifsInfos = $isPushJustificatifsInfos;

        return $this;
    }

    public function getIsAllowedAccessSalaire(): ?bool
    {
        return $this->isAllowedAccessSalaire;
    }

    public function setIsAllowedAccessSalaire(?bool $isAllowedAccessSalaire): self
    {
        $this->isAllowedAccessSalaire = $isAllowedAccessSalaire;

        return $this;
    }

    public function getEmailComptaCompany(): ?string
    {
        return $this->emailComptaCompany;
    }

    public function setEmailComptaCompany(?string $emailComptaCompany): self
    {
        $this->emailComptaCompany = $emailComptaCompany;

        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(?string $signature): self
    {
        $this->signature = $signature;

        return $this;
    }

    public function getIsActived(): ?bool
    {
        return $this->isActived;
    }

    public function setIsActived(?bool $isActived): self
    {
        $this->isActived = $isActived;

        return $this;
    }

    public function getLastActionAt(): ?\DateTimeInterface
    {
        return $this->lastActionAt;
    }

    public function setLastActionAt(?\DateTimeInterface $lastActionAt): self
    {
        $this->lastActionAt = $lastActionAt;

        return $this;
    }

    public function getStreetListAffectation(): ?string
    {
        return $this->streetListAffectation;
    }

    public function setStreetListAffectation(?string $streetListAffectation): self
    {
        $this->streetListAffectation = $streetListAffectation;

        return $this;
    }

    public function getErrorConnectionCounter(): ?int
    {
        return $this->errorConnectionCounter;
    }

    public function setErrorConnectionCounter(?int $errorConnectionCounter): self
    {
        $this->errorConnectionCounter = $errorConnectionCounter;

        return $this;
    }
}
