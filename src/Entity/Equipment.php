<?php

namespace App\Entity;

use App\Repository\EquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ObjectManager;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Faker\Generator;
use Faker\Factory;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Validator\Constraints as Assert;
use JsonSerializable;
/**
 * @ORM\Entity(repositoryClass=EquipmentRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
*/

class Equipment implements JsonSerializable
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $serial_number;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="equipments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Assign::class, mappedBy="equipment")
     */
    private $assigns;

    public function __construct()
    {
        $this->assigns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serial_number;
    }

    public function setSerialNumber(string $serial_number): self
    {
        $this->serial_number = $serial_number;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Assign>
     */
    public function getAssigns(): Collection
    {
        return $this->assigns;
    }

    public function getLastUser()
    {
        $criteria = Criteria::create()
        ->orderBy(['date_assign' => 'DESC'])
        ->setMaxResults(1);

        $result = $this->assigns->matching($criteria);
        return $result->isEmpty() ? null : $result->first()->getUser();
    }

    public function getLastAssign()
    {
        $criteria = Criteria::create()
        ->orderBy(['date_assign' => 'DESC'])
        ->setMaxResults(1);

        $result = $this->assigns->matching($criteria);
        return $result->isEmpty() ? null : $result->first();
    }

    public function getUsername(): array
    {
        $username = $this->assigns->getValues();    
        return $username;
    }

    public function addAssign(Assign $assign): self
    {
        if (!$this->assigns->contains($assign)) {
            $this->assigns[] = $assign;
            $assign->setEquipment($this);
        }

        return $this;
    }

    public function removeAssign(Assign $assign): self
    {
        if ($this->assigns->removeElement($assign)) {
            // set the owning side to null (unless already changed)
            if ($assign->getEquipment() === $this) {
                $assign->setEquipment(null);
            }
        }

        return $this;
    }   

    protected $faker;
    /**
     * @ORM\PrePersist
     */
    public function setSerialNumberValue(): void
    {  
        $this->faker = Factory::create();
        $this->serial_number = $this->faker->ean8;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->getId(),
            'name' => $this->getName()
        ];
    }

   
}
