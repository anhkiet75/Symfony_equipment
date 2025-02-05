<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ObjectManager;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;
    /**
     * @ORM\Column(type="json")
     */
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;
    /**
     * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="users")
     */
    private $roles;
    /**
     * @ORM\OneToMany(targetEntity=Assign::class, mappedBy="user")
     */
    private $assigns;
    /**
     * @ORM\Column(type="boolean")
     */
    private $gender;
    /**
     * @ORM\Column(type="date")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->assigns = new ArrayCollection();
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
        return (string) $this->email;
    }
    /**
     * @see UserInterface
     */

    public function getRoles(): array
    {
        $userRoles = $this->roles;
        $roles = [];
        foreach ($userRoles as $userRole) {
            $roles[] = $userRole->getName();
        }

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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function addRole(Role $role): self
    {
        if ($this->roles && !$this->roles->contains($role)) {
            $this->roles[] = $role;
        }
        return $this;
    }
    public function removeRole(Role $role): self
    {
        $this->roles->removeElement($role);
        return $this;
    }
    /**
     * @return Collection<int, Assign>
     */
    public function getAssigns(): Collection
    {
        return $this->assigns;
    }
    public function addAssign(Assign $assign): self
    {
        if (!$this->assigns->contains($assign)) {
            $this->assigns[] = $assign;
            $assign->setUser($this);
        }
        return $this;
    }
    public function removeAssign(Assign $assign): self
    {
        if ($this->assigns->removeElement($assign)) {
            // set the owning side to null (unless already changed)
            if ($assign->getUser() === $this) {
                $assign->setUser(null);
            }
        }

        return $this;
    }
    public function isGender(): ?bool
    {
        return $this->gender;
    }
    public function setGender(bool $gender): self
    {
        $this->gender = $gender;

        return $this;
    }
    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }
    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @ORM\PrePersist
     */
    public function setValue(): void
    {
    }
}
