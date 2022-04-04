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

    #[ORM\OneToMany(mappedBy: 'representative', targetEntity: Residence::class, cascade: ['persist'])]
    private Collection $representativeResidences;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Residence::class, cascade: ['persist'])]
    private Collection $ownedResidences;

    public function __construct()
    {
        $this->representativeResidences = new ArrayCollection();
        $this->ownedResidences = new ArrayCollection();
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
     * @return mixed
     */
    public function getRepresentativeResidences(): Collection
    {
        return $this->representativeResidences;
    }

    /**
     * @param mixed $representativeResidences
     */
    public function setRepresentativeResidences(Collection $representativeResidences): void
    {
        $this->representativeResidences = $representativeResidences;
    }

    /**
     * @return ArrayCollection
     */
    public function getOwnedResidences(): Collection
    {
        return $this->ownedResidences;
    }

    /**
     * @param ArrayCollection $ownedResidences
     */
    public function setOwnedResidences(ArrayCollection $ownedResidences): void
    {
        $this->ownedResidences = $ownedResidences;
    }


}
