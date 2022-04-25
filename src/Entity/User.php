<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const OWNER = '["ROLE_OWNER"]';
    public const TENANT = '["ROLE_TENANT"]';
    public const REPRESENTATIVE = '["ROLE_REPRESENTATIVE"]';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'smallint')]
    private $isVerified;

    #[ORM\Column(type: 'string')]
    private $name;

    #[ORM\Column(type: 'string')]
    private $lastName;

    #[ORM\OneToMany(mappedBy: 'Owner', targetEntity: Residence::class)]
    private $ResidenceOwner;

    #[ORM\OneToMany(mappedBy: 'Represntative', targetEntity: Residence::class)]
    private $ResidenceRepresentative;

    #[ORM\OneToMany(mappedBy: 'Representative', targetEntity: Rent::class)]
    private $Representative;

    #[ORM\OneToOne(mappedBy: 'tenant', targetEntity: Address::class, cascade: ['persist', 'remove'])]
    private $address;


    public function __construct()
    {
        $this->representativeResidences = new ArrayCollection();
        $this->ownedResidences = new ArrayCollection();
        $this->ResidenceOwner = new ArrayCollection();
        $this->ResidenceRepresentative = new ArrayCollection();
        $this->rents = new ArrayCollection();
        $this->Representative = new ArrayCollection();
    }


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
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

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
     * @return mixed
     */
    public function getIsVerified()
    {
        return $this->isVerified;
    }

    /**
     * @param mixed $isVerified
     */
    public function setIsVerified($isVerified): void
    {
        $this->isVerified = $isVerified;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    /**
     * @return Collection<int, Residence>
     */
    public function getResidenceOwner(): Collection
    {
        return $this->ResidenceOwner;
    }

    public function addResidenceOwner(Residence $residenceOwner): self
    {
        if (!$this->ResidenceOwner->contains($residenceOwner)) {
            $this->ResidenceOwner[] = $residenceOwner;
            $residenceOwner->setOwner($this);
        }

        return $this;
    }

    public function removeResidenceOwner(Residence $residenceOwner): self
    {
        if ($this->ResidenceOwner->removeElement($residenceOwner)) {
            // set the owning side to null (unless already changed)
            if ($residenceOwner->getOwner() === $this) {
                $residenceOwner->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Residence>
     */
    public function getResidenceRepresentative(): Collection
    {
        return $this->ResidenceRepresentative;
    }

    public function addResidenceRepresentative(Residence $residenceRepresentative): self
    {
        if (!$this->ResidenceRepresentative->contains($residenceRepresentative)) {
            $this->ResidenceRepresentative[] = $residenceRepresentative;
            $residenceRepresentative->setRepresntative($this);
        }

        return $this;
    }

    public function removeResidenceRepresentative(Residence $residenceRepresentative): self
    {
        if ($this->ResidenceRepresentative->removeElement($residenceRepresentative)) {
            // set the owning side to null (unless already changed)
            if ($residenceRepresentative->getRepresntative() === $this) {
                $residenceRepresentative->setRepresntative(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rent>
     */
    public function getRepresentative(): Collection
    {
        return $this->Representative;
    }

    public function addRepresentative(Rent $representative): self
    {
        if (!$this->Representative->contains($representative)) {
            $this->Representative[] = $representative;
            $representative->setRepresentative($this);
        }

        return $this;
    }

    public function removeRepresentative(Rent $representative): self
    {
        if ($this->Representative->removeElement($representative)) {
            // set the owning side to null (unless already changed)
            if ($representative->getRepresentative() === $this) {
                $representative->setRepresentative(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        // unset the owning side of the relation if necessary
        if ($address === null && $this->address !== null) {
            $this->address->setTenant(null);
        }

        // set the owning side of the relation if necessary
        if ($address !== null && $address->getTenant() !== $this) {
            $address->setTenant($this);
        }

        $this->address = $address;

        return $this;
    }




}
