<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Aristek\Bundle\ExtraBundle\Model\Traits\StatusTrait;
use Aristek\Bundle\SymfonyJSONAPIBundle\Enum\UserRoles;
use Aristek\Bundle\SymfonyJSONAPIBundle\Model\UserModel;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User extends UserModel
{
    use StatusTrait;
    public const ROLE_RESET_PASSWORD = 'ROLE_RESET_PASSWORD';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $productionAccess = true;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(nullable=true)
     */
    private $lastName;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->roles = [UserRoles::ROLE_USER];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getFullName();
    }

    /**
     * @param string|null $firstName
     *
     * @return User
     */
    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string|null $lastName
     *
     * @return User
     */
    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return trim(sprintf('%s %s', $this->getFirstName(), $this->getLastName())) ?: $this->getUsername();
    }

    /**
     * @return bool
     */
    public function isPasswordResetOnlyAccess(): bool
    {
        return $this->hasRole(static::ROLE_RESET_PASSWORD);
    }

    /**
     * Set productionAccess
     *
     * @param boolean $productionAccess
     *
     * @return User
     */
    public function setProductionAccess(bool $productionAccess): User
    {
        $this->productionAccess = $productionAccess;

        return $this;
    }

    /**
     * Get productionAccess
     *
     * @return boolean
     */
    public function getProductionAccess(): bool
    {
        return $this->productionAccess;
    }

    /**
     * @param bool $value
     *
     * @return User
     */
    public function setActive(bool $value): User
    {
        $this->active = $value;

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
