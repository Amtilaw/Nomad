<?php

namespace App\Entity;

use App\Repository\NmdCblRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NmdCblRepository::class)
 */
class NmdCbl
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
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $EtatDeLaVente;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_CMD_A;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $NUM_COMMANDE;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_REM;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $LIB_PDV;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $SIGNUP_TYPE_ECOM;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $LBL_OFFRE;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $STATUT_GLOBAL_RACC;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $LIBL_ZDV;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $NOM_USER;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $PRENOM_USER;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $MOIS_CMD;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $IDNT_SIEB_CLNT;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_VENTE_VALID_B;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_ANNU;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ModeDInstallation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $CODE_POINT_VENTE;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $CODE_POST_TITU;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $EMAIL_TITU;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $MARQUE_FIXE;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $NUM_CONTACT;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    private $REGION_TITU;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $STATUT_ANNU_ECOM;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $DERNIER_MOTIF_RACC;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $MOTIF_ANNULATION_RACC;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_ACTIV_COM_H;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_MAJ_MOTIF;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_ENVOI_1;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateReception_1;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $statutLivraison_1;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_1ER_RDV_DEB;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_1ER_RDV_FIN;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $MODE_LIVRAISON;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_PROCHAIN_RDV_NC;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $STATUT_KO_VV_SBL;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_STATUT_KO_VV_SBL;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $NomPrenomDuPayeur;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_PROVISIONING_F;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $Processus;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_CMD_SAP_D;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_RECEPTION_x_G;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_ENVOI_PLUS_RECENTE;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $STREET_NUMBER;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $STREET_NAME;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $LIBELLE_COMM;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $RAC_PLANIFIE;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $ValidationVente;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_CMD_semaine;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $ID_RA;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_DMNGT;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $Switch_FTTH_FTTB;

    /**
     * @ORM\Column(type="string", length=75, nullable=true)
     */
    private $Repli_FTTH_FTTB;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $NomPrenomDe_l_utilisateur;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $CODE_INSEE;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $CODE_CLUSTER;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $LIBELLE_CLUSTER;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_RACCO_NC_I;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $villeGoogle;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $boxV8;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaireCri;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $operatorId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_ENVOI_2;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateReception_2;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $statutLivraison_2;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DATE_ENVOI_3;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateReception_3;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $statutLivraison_3;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $selfInstall;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $packageCode;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $codePanier;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $cblCodeHexc;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $cblPanier;

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

    public function getEtatDeLaVente(): ?string
    {
        return $this->EtatDeLaVente;
    }

    public function setEtatDeLaVente(?string $EtatDeLaVente): self
    {
        $this->EtatDeLaVente = $EtatDeLaVente;

        return $this;
    }

    public function getDATECMDA(): ?\DateTimeInterface
    {
        return $this->DATE_CMD_A;
    }

    public function setDATECMDA(?\DateTimeInterface $DATE_CMD_A): self
    {
        $this->DATE_CMD_A = $DATE_CMD_A;

        return $this;
    }

    public function getNUMCOMMANDE(): ?string
    {
        return $this->NUM_COMMANDE;
    }

    public function setNUMCOMMANDE(?string $NUM_COMMANDE): self
    {
        $this->NUM_COMMANDE = $NUM_COMMANDE;

        return $this;
    }

    public function getDATEREM(): ?\DateTimeInterface
    {
        return $this->DATE_REM;
    }

    public function setDATEREM(?\DateTimeInterface $DATE_REM): self
    {
        $this->DATE_REM = $DATE_REM;

        return $this;
    }

    public function getLIBPDV(): ?string
    {
        return $this->LIB_PDV;
    }

    public function setLIBPDV(?string $LIB_PDV): self
    {
        $this->LIB_PDV = $LIB_PDV;

        return $this;
    }

    public function getSIGNUPTYPEECOM(): ?string
    {
        return $this->SIGNUP_TYPE_ECOM;
    }

    public function setSIGNUPTYPEECOM(?string $SIGNUP_TYPE_ECOM): self
    {
        $this->SIGNUP_TYPE_ECOM = $SIGNUP_TYPE_ECOM;

        return $this;
    }

    public function getLBLOFFRE(): ?string
    {
        return $this->LBL_OFFRE;
    }

    public function setLBLOFFRE(?string $LBL_OFFRE): self
    {
        $this->LBL_OFFRE = $LBL_OFFRE;

        return $this;
    }

    public function getSTATUTGLOBALRACC(): ?string
    {
        return $this->STATUT_GLOBAL_RACC;
    }

    public function setSTATUTGLOBALRACC(?string $STATUT_GLOBAL_RACC): self
    {
        $this->STATUT_GLOBAL_RACC = $STATUT_GLOBAL_RACC;

        return $this;
    }

    public function getLIBLZDV(): ?string
    {
        return $this->LIBL_ZDV;
    }

    public function setLIBLZDV(?string $LIBL_ZDV): self
    {
        $this->LIBL_ZDV = $LIBL_ZDV;

        return $this;
    }

    public function getNOMUSER(): ?string
    {
        return $this->NOM_USER;
    }

    public function setNOMUSER(?string $NOM_USER): self
    {
        $this->NOM_USER = $NOM_USER;

        return $this;
    }

    public function getPRENOMUSER(): ?string
    {
        return $this->PRENOM_USER;
    }

    public function setPRENOMUSER(?string $PRENOM_USER): self
    {
        $this->PRENOM_USER = $PRENOM_USER;

        return $this;
    }

    public function getMOISCMD(): ?string
    {
        return $this->MOIS_CMD;
    }

    public function setMOISCMD(?string $MOIS_CMD): self
    {
        $this->MOIS_CMD = $MOIS_CMD;

        return $this;
    }

    public function getIDNTSIEBCLNT(): ?string
    {
        return $this->IDNT_SIEB_CLNT;
    }

    public function setIDNTSIEBCLNT(?string $IDNT_SIEB_CLNT): self
    {
        $this->IDNT_SIEB_CLNT = $IDNT_SIEB_CLNT;

        return $this;
    }

    public function getDATEVENTEVALIDB(): ?\DateTimeInterface
    {
        return $this->DATE_VENTE_VALID_B;
    }

    public function setDATEVENTEVALIDB(?\DateTimeInterface $DATE_VENTE_VALID_B): self
    {
        $this->DATE_VENTE_VALID_B = $DATE_VENTE_VALID_B;

        return $this;
    }

    public function getDATEANNU(): ?\DateTimeInterface
    {
        return $this->DATE_ANNU;
    }

    public function setDATEANNU(\DateTimeInterface $DATE_ANNU): self
    {
        $this->DATE_ANNU = $DATE_ANNU;

        return $this;
    }

    public function getModeDInstallation(): ?string
    {
        return $this->ModeDInstallation;
    }

    public function setModeDInstallation(?string $ModeDInstallation): self
    {
        $this->ModeDInstallation = $ModeDInstallation;

        return $this;
    }

    public function getCODEPOINTVENTE(): ?int
    {
        return $this->CODE_POINT_VENTE;
    }

    public function setCODEPOINTVENTE(?int $CODE_POINT_VENTE): self
    {
        $this->CODE_POINT_VENTE = $CODE_POINT_VENTE;

        return $this;
    }

    public function getCODEPOSTTITU(): ?int
    {
        return $this->CODE_POST_TITU;

    }

    public function setCODEPOSTTITU(?int $CODE_POST_TITU): self
    {
        $this->CODE_POST_TITU = $CODE_POST_TITU;

        return $this;
    }

    public function getEMAILTITU(): ?string
    {
        return $this->EMAIL_TITU;
    }

    public function setEMAILTITU(?string $EMAIL_TITU): self
    {
        $this->EMAIL_TITU = $EMAIL_TITU;

        return $this;
    }

    public function getMARQUEFIXE(): ?string
    {
        return $this->MARQUE_FIXE;
    }

    public function setMARQUEFIXE(?string $MARQUE_FIXE): self
    {
        $this->MARQUE_FIXE = $MARQUE_FIXE;

        return $this;
    }

    public function getNUMCONTACT(): ?int
    {
        return $this->NUM_CONTACT;
    }

    public function setNUMCONTACT(?int $NUM_CONTACT): self
    {
        $this->NUM_CONTACT = $NUM_CONTACT;

        return $this;
    }

    public function getREGIONTITU(): ?string
    {
        return $this->REGION_TITU;
    }

    public function setREGIONTITU(?string $REGION_TITU): self
    {
        $this->REGION_TITU = $REGION_TITU;

        return $this;
    }

    public function getSTATUTANNUECOM(): ?string
    {
        return $this->STATUT_ANNU_ECOM;
    }

    public function setSTATUTANNUECOM(?string $STATUT_ANNU_ECOM): self
    {
        $this->STATUT_ANNU_ECOM = $STATUT_ANNU_ECOM;

        return $this;
    }

    public function getDERNIERMOTIFRACC(): ?string
    {
        return $this->DERNIER_MOTIF_RACC;
    }

    public function setDERNIERMOTIFRACC(?string $DERNIER_MOTIF_RACC): self
    {
        $this->DERNIER_MOTIF_RACC = $DERNIER_MOTIF_RACC;

        return $this;
    }

    public function getMOTIFANNULATIONRACC(): ?string
    {
        return $this->MOTIF_ANNULATION_RACC;
    }

    public function setMOTIFANNULATIONRACC(?string $MOTIF_ANNULATION_RACC): self
    {
        $this->MOTIF_ANNULATION_RACC = $MOTIF_ANNULATION_RACC;

        return $this;
    }

    public function getDATEACTIVCOMH(): ?string
    {
        return $this->DATE_ACTIV_COM_H;
    }

    public function setDATEACTIVCOMH(?\DateTimeInterface $DATE_ACTIV_COM_H): self
    {
        $this->DATE_ACTIV_COM_H = $DATE_ACTIV_COM_H;

        return $this;
    }

    public function getDATEMAJMOTIF(): ?\DateTimeInterface
    {
        return $this->DATE_MAJ_MOTIF;
    }

    public function setDATEMAJMOTIF(?\DateTimeInterface $DATE_MAJ_MOTIF): self
    {
        $this->DATE_MAJ_MOTIF = $DATE_MAJ_MOTIF;

        return $this;
    }

    public function getDATEENVOI1(): ?\DateTimeInterface
    {
        return $this->DATE_ENVOI_1;
    }

    public function setDATEENVOI1(?\DateTimeInterface $DATE_ENVOI_1): self
    {
        $this->DATE_ENVOI_1 = $DATE_ENVOI_1;

        return $this;
    }

    public function getDateReception1(): ?\DateTimeInterface
    {
        return $this->dateReception_1;
    }

    public function setDateReception1(?\DateTimeInterface $dateReception_1): self
    {
        $this->dateReception_1 = $dateReception_1;

        return $this;
    }

    public function getStatutLivraison1(): ?string
    {
        return $this->statutLivraison_1;
    }

    public function setStatutLivraison1(?string $statutLivraison_1): self
    {
        $this->statutLivraison_1 = $statutLivraison_1;

        return $this;
    }

    public function getDATE1ERRDVDEB(): ?\DateTimeInterface
    {
        return $this->DATE_1ER_RDV_DEB;
    }

    public function setDATE1ERRDVDEB(\DateTimeInterface $DATE_1ER_RDV_DEB): self
    {
        $this->DATE_1ER_RDV_DEB = $DATE_1ER_RDV_DEB;

        return $this;
    }

    public function getDATE1ERRDVFIN(): ?string
    {
        return $this->DATE_1ER_RDV_FIN;
    }

    public function setDATE1ERRDVFIN(?string $DATE_1ER_RDV_FIN): self
    {
        $this->DATE_1ER_RDV_FIN = $DATE_1ER_RDV_FIN;

        return $this;
    }

    public function getMODELIVRAISON(): ?string
    {
        return $this->MODE_LIVRAISON;
    }

    public function setMODELIVRAISON(?string $MODE_LIVRAISON): self
    {
        $this->MODE_LIVRAISON = $MODE_LIVRAISON;

        return $this;
    }

    public function getDATEPROCHAINRDVNC(): ?\DateTimeInterface
    {
        return $this->DATE_PROCHAIN_RDV_NC;
    }

    public function setDATEPROCHAINRDVNC(?\DateTimeInterface $DATE_PROCHAIN_RDV_NC): self
    {
        $this->DATE_PROCHAIN_RDV_NC = $DATE_PROCHAIN_RDV_NC;

        return $this;
    }

    public function getSTATUTKOVVSBL(): ?string
    {
        return $this->STATUT_KO_VV_SBL;
    }

    public function setSTATUTKOVVSBL(?string $STATUT_KO_VV_SBL): self
    {
        $this->STATUT_KO_VV_SBL = $STATUT_KO_VV_SBL;

        return $this;
    }

    public function getDATESTATUTKOVVSBL(): ?\DateTimeInterface
    {
        return $this->DATE_STATUT_KO_VV_SBL;
    }

    public function setDATESTATUTKOVVSBL(?\DateTimeInterface $DATE_STATUT_KO_VV_SBL): self
    {
        $this->DATE_STATUT_KO_VV_SBL = $DATE_STATUT_KO_VV_SBL;

        return $this;
    }

    public function getNomPrenomDuPayeur(): ?string
    {
        return $this->NomPrenomDuPayeur;
    }

    public function setNomPrenomDuPayeur(?string $NomPrenomDuPayeur): self
    {
        $this->NomPrenomDuPayeur = $NomPrenomDuPayeur;

        return $this;
    }

    public function getDATEPROVISIONINGF(): ?\DateTimeInterface
    {
        return $this->DATE_PROVISIONING_F;
    }

    public function setDATEPROVISIONINGF(?\DateTimeInterface $DATE_PROVISIONING_F): self
    {
        $this->DATE_PROVISIONING_F = $DATE_PROVISIONING_F;

        return $this;
    }

    public function getProcessus(): ?string
    {
        return $this->Processus;
    }

    public function setProcessus(?string $Processus): self
    {
        $this->Processus = $Processus;

        return $this;
    }

    public function getDATECMDSAPD(): ?\DateTimeInterface
    {
        return $this->DATE_CMD_SAP_D;
    }

    public function setDATECMDSAPD(?\DateTimeInterface $DATE_CMD_SAP_D): self
    {
        $this->DATE_CMD_SAP_D = $DATE_CMD_SAP_D;

        return $this;
    }

    public function getDATERECEPTIONXG(): ?\DateTimeInterface
    {
        return $this->DATE_RECEPTION_x_G;
    }

    public function setDATERECEPTIONXG(?\DateTimeInterface $DATE_RECEPTION_x_G): self
    {
        $this->DATE_RECEPTION_x_G = $DATE_RECEPTION_x_G;

        return $this;
    }

    public function getDATEENVOIPLUSRECENTE(): ?string
    {
        return $this->DATE_ENVOI_PLUS_RECENTE;
    }

    public function setDATEENVOIPLUSRECENTE(?string $DATE_ENVOI_PLUS_RECENTE): self
    {
        $this->DATE_ENVOI_PLUS_RECENTE = $DATE_ENVOI_PLUS_RECENTE;

        return $this;
    }

    public function getSTREETNUMBER(): ?int
    {
        return $this->STREET_NUMBER;
    }

    public function setSTREETNUMBER(?int $STREET_NUMBER): self
    {
        $this->STREET_NUMBER = $STREET_NUMBER;

        return $this;
    }

    public function getSTREETNAME(): ?string
    {
        return $this->STREET_NAME;
    }

    public function setSTREETNAME(?string $STREET_NAME): self
    {
        $this->STREET_NAME = $STREET_NAME;

        return $this;
    }

    public function getLIBELLECOMM(): ?string
    {
        return $this->LIBELLE_COMM;
    }

    public function setLIBELLECOMM(?string $LIBELLE_COMM): self
    {
        $this->LIBELLE_COMM = $LIBELLE_COMM;

        return $this;
    }

    public function getRACPLANIFIE(): ?string
    {
        return $this->RAC_PLANIFIE;
    }

    public function setRACPLANIFIE(?string $RAC_PLANIFIE): self
    {
        $this->RAC_PLANIFIE = $RAC_PLANIFIE;

        return $this;
    }

    public function getValidationVente(): ?string
    {
        return $this->ValidationVente;
    }

    public function setValidationVente(?string $ValidationVente): self
    {
        $this->ValidationVente = $ValidationVente;

        return $this;
    }

    public function getDATECMDSemaine(): ?\DateTimeInterface
    {
        return $this->DATE_CMD_semaine;
    }

    public function setDATECMDSemaine(?\DateTimeInterface $DATE_CMD_semaine): self
    {
        $this->DATE_CMD_semaine = $DATE_CMD_semaine;

        return $this;
    }

    public function getIDRA(): ?string
    {
        return $this->ID_RA;
    }

    public function setIDRA(?string $ID_RA): self
    {
        $this->ID_RA = $ID_RA;

        return $this;
    }

    public function getDATEDMNGT(): ?string
    {
        return $this->DATE_DMNGT;
    }

    public function setDATEDMNGT(?string $DATE_DMNGT): self
    {
        $this->DATE_DMNGT = $DATE_DMNGT;

        return $this;
    }

    public function getSwitchFTTHFTTB(): ?string
    {
        return $this->Switch_FTTH_FTTB;
    }

    public function setSwitchFTTHFTTB(?string $Switch_FTTH_FTTB): self
    {
        $this->Switch_FTTH_FTTB = $Switch_FTTH_FTTB;

        return $this;
    }

    public function getRepliFTTHFTTB(): ?string
    {
        return $this->Repli_FTTH_FTTB;
    }

    public function setRepliFTTHFTTB(?string $Repli_FTTH_FTTB): self
    {
        $this->Repli_FTTH_FTTB = $Repli_FTTH_FTTB;

        return $this;
    }

    public function getNomPrenomDeLUtilisateur(): ?string
    {
        return $this->NomPrenomDe_l_utilisateur;
    }

    public function setNomPrenomDeLUtilisateur(?string $NomPrenomDe_l_utilisateur): self
    {
        $this->NomPrenomDe_l_utilisateur = $NomPrenomDe_l_utilisateur;

        return $this;
    }

    public function getCODEINSEE(): ?string
    {
        return $this->CODE_INSEE;
    }

    public function setCODEINSEE(?string $CODE_INSEE): self
    {
        $this->CODE_INSEE = $CODE_INSEE;

        return $this;
    }

    public function getCODECLUSTER(): ?string
    {
        return $this->CODE_CLUSTER;
    }

    public function setCODECLUSTER(?string $CODE_CLUSTER): self
    {
        $this->CODE_CLUSTER = $CODE_CLUSTER;

        return $this;
    }

    public function getLIBELLECLUSTER(): ?string
    {
        return $this->LIBELLE_CLUSTER;
    }

    public function setLIBELLECLUSTER(?string $LIBELLE_CLUSTER): self
    {
        $this->LIBELLE_CLUSTER = $LIBELLE_CLUSTER;

        return $this;
    }

    public function getDATERACCONCI(): ?\DateTimeInterface
    {
        return $this->DATE_RACCO_NC_I;
    }

    public function setDATERACCONCI(?\DateTimeInterface $DATE_RACCO_NC_I): self
    {
        $this->DATE_RACCO_NC_I = $DATE_RACCO_NC_I;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getVilleGoogle(): ?string
    {
        return $this->villeGoogle;
    }

    public function setVilleGoogle(?string $villeGoogle): self
    {
        $this->villeGoogle = $villeGoogle;

        return $this;
    }

    public function getBoxV8(): ?int
    {
        return $this->boxV8;
    }

    public function setBoxV8(?int $boxV8): self
    {
        $this->boxV8 = $boxV8;

        return $this;
    }

    public function getCommentaireCri(): ?string
    {
        return $this->commentaireCri;
    }

    public function setCommentaireCri(?string $commentaireCri): self
    {
        $this->commentaireCri = $commentaireCri;

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

    public function getDATEENVOI2(): ?\DateTimeInterface
    {
        return $this->DATE_ENVOI_2;
    }

    public function setDATEENVOI2(?\DateTimeInterface $DATE_ENVOI_2): self
    {
        $this->DATE_ENVOI_2 = $DATE_ENVOI_2;

        return $this;
    }

    public function getDateReception2(): ?\DateTimeInterface
    {
        return $this->dateReception_2;
    }

    public function setDateReception2(?\DateTimeInterface $dateReception_2): self
    {
        $this->dateReception_2 = $dateReception_2;

        return $this;
    }

    public function getStatutLivraison2(): ?\DateTimeInterface
    {
        return $this->statutLivraison_2;
    }

    public function setStatutLivraison2(?\DateTimeInterface $statutLivraison_2): self
    {
        $this->statutLivraison_2 = $statutLivraison_2;

        return $this;
    }

    public function getDATEENVOI3(): ?\DateTimeInterface
    {
        return $this->DATE_ENVOI_3;
    }

    public function setDATEENVOI3(?\DateTimeInterface $DATE_ENVOI_3): self
    {
        $this->DATE_ENVOI_3 = $DATE_ENVOI_3;

        return $this;
    }

    public function getDateReception3(): ?\DateTimeInterface
    {
        return $this->dateReception_3;
    }

    public function setDateReception3(?\DateTimeInterface $dateReception_3): self
    {
        $this->dateReception_3 = $dateReception_3;

        return $this;
    }

    public function getStatutLivraison3(): ?\DateTimeInterface
    {
        return $this->statutLivraison_3;
    }

    public function setStatutLivraison3(?\DateTimeInterface $statutLivraison_3): self
    {
        $this->statutLivraison_3 = $statutLivraison_3;

        return $this;
    }

    public function getSelfInstall(): ?string
    {
        return $this->selfInstall;
    }

    public function setSelfInstall(?string $selfInstall): self
    {
        $this->selfInstall = $selfInstall;

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

    public function getCodePanier(): ?string
    {
        return $this->codePanier;
    }

    public function setCodePanier(?string $codePanier): self
    {
        $this->codePanier = $codePanier;

        return $this;
    }

    public function getCblCodeHexc(): ?string
    {
        return $this->cblCodeHexc;
    }

    public function setCblCodeHexc(?string $cblCodeHexc): self
    {
        $this->cblCodeHexc = $cblCodeHexc;

        return $this;
    }

    public function getCblPanier(): ?string
    {
        return $this->cblPanier;
    }

    public function setCblPanier(?string $cblPanier): self
    {
        $this->cblPanier = $cblPanier;

        return $this;
    }
}
