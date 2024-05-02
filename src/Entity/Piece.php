<?php

namespace App\Entity;

use App\Repository\PieceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PieceRepository::class)]
class Piece
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $surface = null;

    #[ORM\ManyToOne(inversedBy: 'piece')]
    private ?BienImmobilier $bienImmobilier = null;

    #[ORM\ManyToOne(inversedBy: 'pieces')]
    private ?TypePiece $typePiece = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getSurface(): ?string
    {
        return $this->surface;
    }

    public function setSurface(string $surface): static
    {
        $this->surface = $surface;

        return $this;
    }

    public function getBienImmobilier(): ?BienImmobilier
    {
        return $this->bienImmobilier;
    }

    public function setBienImmobilier(?BienImmobilier $bienImmobilier): static
    {
        $this->bienImmobilier = $bienImmobilier;

        return $this;
    }

    public function getTypePiece(): ?TypePiece
    {
        return $this->typePiece;
    }

    public function setTypePiece(?TypePiece $typePiece): static
    {
        $this->typePiece = $typePiece;

        return $this;
    }

}
