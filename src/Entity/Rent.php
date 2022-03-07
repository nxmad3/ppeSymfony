<?php

namespace App\Entity;

use App\Repository\RentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RentRepository::class)]
class Rent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(targetEntity: User::class, cascade: ['persist', 'remove'])]
    private $tenant;

    #[ORM\OneToOne(targetEntity: Residence::class, cascade: ['persist', 'remove'])]
    private $residence;

    #[ORM\OneToOne(targetEntity: User::class, cascade: ['persist', 'remove'])]
    private $owner;


    #[ORM\Column(type: 'string', length: 255)]
    private $inventory_file;

    #[ORM\Column(type: 'datetime')]
    private $arrival_date;

    #[ORM\Column(type: 'datetime')]
    private $departure_date;

    #[ORM\Column(type: 'text')]
    private $tenant_cillents;

    #[ORM\Column(type: 'string', length: 255)]
    private $tenant_signature;

    #[ORM\Column(type: 'string', length: 45)]
    private $tenant_validated_at;

    #[ORM\Column(type: 'text')]
    private $represntative_comments;

    #[ORM\Column(type: 'string', length: 255)]
    private $represntative_signature;

    #[ORM\Column(type: 'datetime')]
    private $represntative_validated_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $available;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTenantId(): ?User
    {
        return $this->tenant_id;
    }

    public function setTenantId(?User $tenant): self
    {
        $this->tenant_id = $tenant;

        return $this;
    }

    public function getResidenceId(): ?Residence
    {
        return $this->residence_id;
    }

    public function setResidenceId(?Residence $residence): self
    {
        $this->residence_id = $residence;

        return $this;
    }

    public function getInventoryFile(): ?string
    {
        return $this->inventory_file;
    }

    public function setInventoryFile(string $inventory_file): self
    {
        $this->inventory_file = $inventory_file;

        return $this;
    }

    public function getArrivalDate(): ?\DateTimeInterface
    {
        return $this->arrival_date;
    }

    public function setArrivalDate(\DateTimeInterface $arrival_date): self
    {
        $this->arrival_date = $arrival_date;

        return $this;
    }

    public function getDepartureDate(): ?\DateTimeInterface
    {
        return $this->departure_date;
    }

    public function setDepartureDate(\DateTimeInterface $departure_date): self
    {
        $this->departure_date = $departure_date;

        return $this;
    }

    public function getTenantCillents(): ?string
    {
        return $this->tenant_cillents;
    }

    public function setTenantCillents(string $tenant_cillents): self
    {
        $this->tenant_cillents = $tenant_cillents;

        return $this;
    }

    public function getTenantSignature(): ?string
    {
        return $this->tenant_signature;
    }

    public function setTenantSignature(string $tenant_signature): self
    {
        $this->tenant_signature = $tenant_signature;

        return $this;
    }

    public function getTenantValidatedAt(): ?string
    {
        return $this->tenant_validated_at;
    }

    public function setTenantValidatedAt(string $tenant_validated_at): self
    {
        $this->tenant_validated_at = $tenant_validated_at;

        return $this;
    }

    public function getRepresntativeComments(): ?string
    {
        return $this->represntative_comments;
    }

    public function setRepresntativeComments(string $represntative_comments): self
    {
        $this->represntative_comments = $represntative_comments;

        return $this;
    }

    public function getRepresntativeSignature(): ?string
    {
        return $this->represntative_signature;
    }

    public function setRepresntativeSignature(string $represntative_signature): self
    {
        $this->represntative_signature = $represntative_signature;

        return $this;
    }

    public function getRepresntativeValidatedAt(): ?\DateTimeInterface
    {
        return $this->represntative_validated_at;
    }

    public function setRepresntativeValidatedAt(\DateTimeInterface $represntative_validated_at): self
    {
        $this->represntative_validated_at = $represntative_validated_at;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResidence()
    {
        return $this->residence;
    }

    /**
     * @param mixed $residence
     */
    public function setResidence($residence): void
    {
        $this->residence = $residence;
    }

    /**
     * @return mixed
     */
    public function getTenant()
    {
        return $this->tenant;
    }

    /**
     * @param mixed $tenant
     */
    public function setTenant($tenant): void
    {
        $this->tenant = $tenant;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }

    public function getAvailable(): ?\DateTimeInterface
    {
        return $this->available;
    }

    public function setAvailable(?\DateTimeInterface $available): self
    {
        $this->available = $available;

        return $this;
    }
}
