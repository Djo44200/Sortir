<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SortieRepository")
 */
class Sortie
{
    //constantes d'etat
    const ETAT_CREE = 'CRE';
    const ETAT_OUVERTE = 'OUV';
    const ETAT_EN_COURS = 'ENC';
    const ETAT_PASSEE = 'PAS';
    const ETAT_ANNULLE = 'ANN';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom de la sortie doit être renseigné.")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $motif;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de début doit être renseignée.")
     * @Assert\DateTime
     * @Assert\GreaterThanOrEqual("today", message="La date doit être supérieur ou égale à la date du jour.")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type("integer", message="La durée doit être indiquée en chiffres.")
     * @Assert\NotBlank(message="La durée doit être renseignée.")
     * @Assert\GreaterThan(value=0, message="La durée, si elle est indiquée, doit être supérieur à 0 minutes.")
     */
    private $duree;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La date de clôture doit être renseignée.")
     * @Assert\DateTime
     * @Assert\GreaterThanOrEqual("today", message="La date doit être supérieur ou égale à la date du jour.")
     */
    private $dateCloture;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le champ doit être renseigné.")
     * @Assert\Type("integer", message="Le nombre de participants maximum doit être indiqué en chiffre.")
     * @Assert\GreaterThan(value=0, message="Le nombre de participants maximum doit être supérieur à 0.")
     */
    private $nbInscriptionsMax;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank(message="La description doit être renseignée.")
     */
    private $descriptionInfos;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlPhoto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orgaSortie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lieu", inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieu;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inscription", mappedBy="sortie")
     */
    private $inscriptions;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
        $this->setEtat('CRE');
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = mb_strtoupper($nom);

        return $this;
    }

    public function getDateDebut(): ?DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateCloture(): ?DateTimeInterface
    {
        return $this->dateCloture;
    }

    public function setDateCloture(DateTimeInterface $dateCloture): self
    {
        $this->dateCloture = $dateCloture;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getDescriptionInfos(): ?string
    {
        return $this->descriptionInfos;
    }

    public function setDescriptionInfos(string $descriptionInfos): self
    {
        $this->descriptionInfos = $descriptionInfos;

        return $this;
    }


    public function getUrlPhoto(): ?string
    {
        return $this->urlPhoto;
    }

    public function setUrlPhoto(?string $urlPhoto): self
    {
        $this->urlPhoto = $urlPhoto;

        return $this;
    }

    public function getOrganisateur(): ?User
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?User $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        if (!in_array($etat, array(self::ETAT_ANNULLE, self::ETAT_CREE, self::ETAT_EN_COURS, self::ETAT_OUVERTE, self::ETAT_PASSEE))) {
            throw new \InvalidArgumentException("Etat invalide");
        }
        $this->etat = $etat;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * @return Collection|Inscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setSortie($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->contains($inscription)) {
            $this->inscriptions->removeElement($inscription);
            // set the owning side to null (unless already changed)
            if ($inscription->getSortie() === $this) {
                $inscription->setSortie(null);
            }
        }

        return $this;
    }

    public function getnbInscriptions(){

        if (!$this->getInscriptions()->isEmpty()) {
            $count = $this->getInscriptions()->indexOf($this->getInscriptions()->last()) + 1;
        }else{
            $count = 0;
        }

        return $count;
    }

    public function estInscris($user)
    {
        $inscris = false;
        foreach($this->getInscriptions() as $ins){
            if ($ins->getParticipant() === $user){
                $inscris = true;
                break;
            }
        }
        return $inscris;
    }

    /**
     * @return mixed
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * @param mixed $motif
     */
    public function setMotif($motif): void
    {
        $this->motif = $motif;
    }





    public function __toString(): ?string
    {
       return $this->getNom();
    }


}
