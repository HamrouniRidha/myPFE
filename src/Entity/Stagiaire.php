<?php

namespace App\Entity;

use App\Repository\StagiaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: StagiaireRepository::class)]
class Stagiaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8, nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^\d{8}$/',
        message: "Le champ 'cin' doit contenir exactement 8 chiffres."
    )]
    private ?string $cin = null;
    #[Assert\NotBlank]

    #[ORM\Column(length: 255)]
    private ?string $nom = null;
    #[Assert\NotBlank]

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email(
        message: "Veuillez entrer une adresse e-mail valide."
    )]
    private ?string $mail = null;

    #[ORM\Column(length: 8, nullable: true)]
    #[Assert\Regex(
        pattern: '/^\d{8}$/',
        message: "Le champ 'tel' doit contenir  8 chiffres."
    )]
    private ?string $tel = null;

    #[ORM\OneToMany(mappedBy: 'stagiaire', targetEntity: Stage::class)]
    private Collection $stage;

    #[ORM\OneToMany(mappedBy: 'stagiaire', targetEntity: Historique::class)]
    private Collection $historique;


    public function __construct()
    {
        $this->stage = new ArrayCollection();
        $this->historique = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * @return Collection<int, Stage>
     */
    public function getStage(): Collection
    {
        return $this->stage;
    }

    public function addStage(Stage $stage): self
    {
        if (!$this->stage->contains($stage)) {
            $this->stage->add($stage);
            // $stage->setStagiaire($this);
        }

        return $this;
    }

    public function removeStage(Stage $stage): self
    {
        if ($this->stage->removeElement($stage)) {
            // set the owning side to null (unless already changed)
            if ($stage->getStagiaire() === $this) {
                $stage->setStagiaire(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->cin;
    }

    /**
     * @return Collection<int, Historique>
     */
    public function getHistorique(): Collection
    {
        return $this->historique;
    }

    public function addHistorique(Historique $historique): self
    {
        if (!$this->historique->contains($historique)) {
            $this->historique->add($historique);
            $historique->setStagiaire($this);
        }

        return $this;
    }

    public function removeHistorique(Historique $historique): self
    {
        if ($this->historique->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getStagiaire() === $this) {
                $historique->setStagiaire(null);
            }
        }

        return $this;
    }
}
