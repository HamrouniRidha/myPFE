<?php

namespace App\Entity;

use App\Repository\StageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThan;

#[ORM\Entity(repositoryClass: StageRepository::class)]
class Stage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $sujet = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan(propertyPath: "date_debut", message: "La date de fin doit être postérieure à la date de début.")]

    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $documentFileName = [];

    #[ORM\Column(nullable: true)]
    private ?float $note1 = null;

    #[ORM\Column(nullable: true)]
    private ?float $note2 = null;

    #[ORM\Column(nullable: true)]
    private ?float $note3 = null;

    #[ORM\Column(nullable: true)]
    private ?float $note4 = null;

    #[ORM\Column(nullable: true)]
    private ?float $note5 = null;

    #[ORM\Column(nullable: true)]
    private ?float $moyenne = null;

    #[ORM\ManyToOne(inversedBy: 'stage')]
    private ?Type $type = null;

    // #[ORM\ManyToOne(inversedBy: 'stage')]
    // private ?Stagiaire $stagiaire = null;

    #[ORM\ManyToOne(inversedBy: 'stages')]
    private ?Encadrant $encadrant = null;

    #[ORM\ManyToOne(targetEntity: Historique::class, inversedBy: "historique", cascade: ['persist'])]
    private ?Historique $historique = null;

    #[ORM\ManyToOne(inversedBy: 'stages')]
    private ?Departement $departement = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $etat = null;





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }



    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getDocumentFileName(): array
    {
        return $this->documentFileName;
    }

    public function setDocumentFileName(array $documentFileName): self
    {
        $this->documentFileName = $documentFileName;

        return $this;
    }

    public function getNote1(): ?float
    {
        return $this->note1;
    }

    public function setNote1(?float $note1): self
    {
        $this->note1 = $note1;

        return $this;
    }

    public function getNote2(): ?float
    {
        return $this->note2;
    }

    public function setNote2(?float $note2): self
    {
        $this->note2 = $note2;

        return $this;
    }

    public function getNote3(): ?float
    {
        return $this->note3;
    }

    public function setNote3(?float $note3): self
    {
        $this->note3 = $note3;

        return $this;
    }

    public function getNote4(): ?float
    {
        return $this->note4;
    }

    public function setNote4(?float $note4): self
    {
        $this->note4 = $note4;

        return $this;
    }

    public function getNote5(): ?float
    {
        return $this->note5;
    }

    public function setNote5(?float $note5): self
    {
        $this->note5 = $note5;

        return $this;
    }

    public function getMoyenne(): ?float
    {
        return $this->moyenne;
    }

    public function setMoyenne(?float $moyenne): self
    {
        $this->moyenne = $moyenne;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    // public function getStagiaire(): ?Stagiaire
    // {
    //     return $this->stagiaire;
    // }

    // public function setStagiaire(?Stagiaire $stagiaire): self
    // {
    //     $this->stagiaire = $stagiaire;

    //     return $this;
    // }

    public function getEncadrant(): ?Encadrant
    {
        return $this->encadrant;
    }

    public function setEncadrant(?Encadrant $encadrant): self
    {
        $this->encadrant = $encadrant;

        return $this;
    }

    public function getHistorique(): ?Historique
    {
        return $this->historique;
    }

    public function setHistorique(?Historique $historique): self
    {
        $this->historique = $historique;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
    public function __construct()
    {
        $this->etat = 'en_cours'; // Défaut: en cours
    }
    public function calculateAverage(): float
    {
        $notes = [$this->note1, $this->note2, $this->note3, $this->note4, $this->note5];
        $notes = array_filter($notes, function ($note) {
            return $note !== null;
        });

        $count = count($notes);
        if ($count === 0) {
            return 0;
        }

        $sum = array_sum($notes);
        return $sum / $count;
    }
}
