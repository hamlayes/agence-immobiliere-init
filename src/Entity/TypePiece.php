<?php

namespace App\Entity;

use App\Repository\TypePieceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypePieceRepository::class)]
class TypePiece
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?bool $surfaceHabitable = null;

    /**
     * @var Collection<int, Piece>
     */
    #[ORM\OneToMany(mappedBy: 'typePiece', targetEntity: Piece::class)]
    private Collection $pieces;

    public function __construct()
    {
        $this->pieces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function isSurfaceHabitable(): ?bool
    {
        return $this->surfaceHabitable;
    }

    public function setSurfaceHabitable(bool $surfaceHabitable): static
    {
        $this->surfaceHabitable = $surfaceHabitable;

        return $this;
    }

    /**
     * @return Collection<int, Piece>
     */
    public function getPieces(): Collection
    {
        return $this->pieces;
    }

    public function addPiece(Piece $piece): static
    {
        if (!$this->pieces->contains($piece)) {
            $this->pieces->add($piece);
            $piece->setTypePiece($this);
        }

        return $this;
    }

    public function removePiece(Piece $piece): static
    {
        if ($this->pieces->removeElement($piece)) {
            // set the owning side to null (unless already changed)
            if ($piece->getTypePiece() === $this) {
                $piece->setTypePiece(null);
            }
        }

        return $this;
    }
}
