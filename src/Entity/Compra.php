<?php

namespace App\Entity;

use App\Repository\CompraRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompraRepository::class)]
class Compra
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numeroLoteria = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaCompra = null;

    #[ORM\ManyToOne(inversedBy: 'compras')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'compra')]
    private ?Sorteo $sorteo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroLoteria(): ?int
    {
        return $this->numeroLoteria;
    }

    public function setNumeroLoteria(int $numeroLoteria): static
    {
        $this->numeroLoteria = $numeroLoteria;

        return $this;
    }

    public function getFechaCompra(): ?\DateTimeInterface
    {
        return $this->fechaCompra;
    }

    public function setFechaCompra(\DateTimeInterface $fechaCompra): static
    {
        $this->fechaCompra = $fechaCompra;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSorteo(): ?Sorteo
    {
        return $this->sorteo;
    }

    public function setSorteo(?Sorteo $sorteo): static
    {
        $this->sorteo = $sorteo;

        return $this;
    }
}
