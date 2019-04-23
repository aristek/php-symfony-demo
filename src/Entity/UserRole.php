<?php

namespace App\Entity;

use Aristek\Bundle\ExtraBundle\Model\Traits\IdTrait;
use Aristek\Bundle\ExtraBundle\Model\Traits\StatusTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserRole
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRoleRepository")
 */
class UserRole
{
    use IdTrait;
    use StatusTrait;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="roles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(name="role", nullable=false, referencedColumnName="code")
     */
    private $role;

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return UserRole
     */
    public function setUser(User $user): UserRole
    {
        $this->user = $user;
        $user->addUserRole($this);

        return $this;
    }

    /**
     * @return Role|null
     */
    public function getRole(): ?Role
    {
        return $this->role;
    }

    /**
     * @param Role $role
     *
     * @return UserRole
     */
    public function setRole(Role $role): UserRole
    {
        $this->role = $role;

        return $this;
    }
}
