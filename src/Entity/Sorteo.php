<?php

namespace App\Entity;

use App\Repository\SorteoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SorteoRepository::class)]
class Sorteo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaHora = null;

    #[ORM\Column]
    private ?int $precioNumero = null;

    #[ORM\Column]
    private ?int $numsAVender = null;

    #[ORM\Column]
    private ?int $premio = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'sorteos')]
    private Collection $user;

    #[ORM\OneToMany(mappedBy: 'sorteo', targetEntity: Compra::class)]
    private Collection $compra;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->compra = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getFechaHora(): ?\DateTimeInterface
    {
        return $this->fechaHora;
    }

    public function setFechaHora(\DateTimeInterface $fechaHora): static
    {
        $this->fechaHora = $fechaHora;

        return $this;
    }

    public function getPrecioNumero(): ?int
    {
        return $this->precioNumero;
    }

    public function setPrecioNumero(int $precioNumero): static
    {
        $this->precioNumero = $precioNumero;

        return $this;
    }

    public function getNumsAVender(): ?int
    {
        return $this->numsAVender;
    }

    public function setNumsAVender(int $numsAVender): static
    {
        $this->numsAVender = $numsAVender;

        return $this;
    }

    public function getPremio(): ?int
    {
        return $this->premio;
    }

    public function setPremio(int $premio): static
    {
        $this->premio = $premio;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->user->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, Compra>
     */
    public function getCompra(): Collection
    {
        return $this->compra;
    }

    public function addCompra(Compra $compra): static
    {
        if (!$this->compra->contains($compra)) {
            $this->compra->add($compra);
            $compra->setSorteo($this);
        }

        return $this;
    }

    public function removeCompra(Compra $compra): static
    {
        if ($this->compra->removeElement($compra)) {
            // set the owning side to null (unless already changed)
            if ($compra->getSorteo() === $this) {
                $compra->setSorteo(null);
            }
        }

        return $this;
    }
}
