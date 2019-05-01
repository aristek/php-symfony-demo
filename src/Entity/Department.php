<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Aristek\Bundle\ExtraBundle\Model\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Department
 *
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department
{
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column()
     */
    private $name;

    /**
     * @var User[]
     *
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="departments")
     */
    private $users;

    /**
     * Department constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getName();
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Department
     */
    public function setName(string $name): Department
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return User[]|Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param User $user
     *
     * @return Department
     */
    public function addUser(User $user): Department
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addDepartment($this);
        }

        return $this;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function removeUser(User $user): Department
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeDepartment($this);
        }

        return $this;
    }
}
