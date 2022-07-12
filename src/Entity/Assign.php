<?php

namespace App\Entity;

use App\Repository\AssignRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AssignRepository::class)
 */
class Assign
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $data_assign;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $due_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_return;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="assigns", fetch= "EAGER")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Equipment::class, inversedBy="assigns")
     */
    private $equipment;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataAssign(): ?\DateTimeInterface
    {
        return $this->data_assign;
    }

    public function setDataAssign(?\DateTimeInterface $data_assign): self
    {
        $this->data_assign = $data_assign;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(?\DateTimeInterface $due_date): self
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getDateReturn(): ?\DateTimeInterface
    {
        return $this->date_return;
    }

    public function setDateReturn(?\DateTimeInterface $date_return): self
    {
        $this->date_return = $date_return;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user_id): self
    {
        $this->user = $user_id;

        return $this;
    }

    public function getEquipment(): ?Equipment
    {
        return $this->equipment;
    }

    public function setEquipment(?Equipment $equipment_id): self
    {
        $this->equipment = $equipment_id;

        return $this;
    }

}
