<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Aristek\Bundle\ExtraBundle\Model\Traits\StatusTrait;
use Aristek\Bundle\SymfonyJSONAPIBundle\Entity\File\File;
use Aristek\Bundle\SymfonyJSONAPIBundle\Model\UserModel;
use Aristek\Component\Util\StringHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User extends UserModel
{
    use StatusTrait;

    /**
     * @var File
     *
     * @ORM\OneToOne(targetEntity=File::class, orphanRemoval=true, cascade={"persist"})
     */
    private $avatar;

    /**
     * @var UserRole[]
     *
     * @ORM\OneToMany(targetEntity="UserRole", mappedBy="user", cascade={"all"})
     */
    protected $roles;

    /**
     * @var Profile
     *
     * @ORM\OneToOne(targetEntity="Profile", mappedBy="user", cascade={"all"})
     *
     * @Assert\Valid()
     */
    private $profile;

    /**
     * @var Department[]
     *
     * @ORM\ManyToMany(targetEntity="Department", mappedBy="users")
     */
    private $departments;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->departments = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->password = StringHelper::randomPassword();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) ($this->getFullName() ?: $this->getUsername());
    }

    /**
     * @return File|null
     */
    public function getAvatar(): ?File
    {
        return $this->avatar;
    }

    /**
     * @param File $avatar
     *
     * @return User
     */
    public function setAvatar(File $avatar): User
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        $profile = $this->getProfile();

        return $profile ? $profile->getFullName() : null;
    }

    /**
     * @return Profile|null
     */
    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    /**
     * @param Profile $profile
     *
     * @return User
     */
    public function setProfile(Profile $profile): User
    {
        $this->profile = $profile;
        $profile->setUser($this);

        return $this;
    }

    /**
     * @return Department[]
     */
    public function getDepartments(): array
    {
        return $this->departments;
    }

    /**
     * @param Department $department
     *
     * @return $this
     */
    public function addDepartment(Department $department): User
    {
        if (!$this->departments->contains($department)) {
            $this->departments->add($department);
            $department->addUser($this);
        }

        return $this;
    }

    /**
     * @param Department $department
     *
     * @return $this
     */
    public function removeDepartment(Department $department): User
    {
        if ($this->departments->contains($department)) {
            $this->departments->removeElement($department);
            $department->removeUser($this);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $codes = [];
        foreach ($this->roles as $userRole) {
            if ($role = $userRole->getRole()) {
                $codes[] = $role->getCode();
            }
        }

        return $codes;
    }

    /**
     * @return UserRole[]|Collection
     */
    public function getUserRoles(): Collection
    {
        return $this->roles;
    }

    /**
     * @param Collection $userRoles
     *
     * @return $this
     */
    public function setUserRoles(Collection $userRoles): User
    {
        $this->roles->clear();
        foreach ($userRoles as $userRole) {
            $this->addUserRole($userRole);
        }

        return $this;
    }

    /**
     * @param UserRole $userRole
     *
     * @return $this
     */
    public function addUserRole(UserRole $userRole): User
    {
        if (!$this->roles->contains($userRole)) {
            $this->roles->add($userRole);
            $userRole->setUser($this);
        }

        return $this;
    }

    /**
     * @param UserRole $userRole
     *
     * @return $this
     */
    public function removeUserRole(UserRole $userRole): User
    {
        $this->roles->removeElement($userRole);

        return $this;
    }

    /**
     * Unserialize the user.
     *
     * @param string $serialized
     */
    public function unserialize(string $serialized): void
    {
        $data = unserialize($serialized, ['allowed_classes' => []]);
        // add a few extra elements in the array to ensure that we have enough keys when universalizing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 2, null));

        [$this->password, $this->usernameCanonical, $this->username, $this->id, $this->active] = $data;
    }

    /**
     * @return array
     */
    protected function getSerializeData(): array
    {
        return array_merge(parent::getSerializeData(), [$this->active]);
    }
}
