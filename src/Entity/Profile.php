<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Profile
 *
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 */
class Profile
{
    /**
     * @var User
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="profile")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;

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
     * @var DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthDay;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $biography;

    /**
     * @var Contact[]
     *
     * @ORM\OneToMany(targetEntity=Contact::class, mappedBy="profile", cascade={"all"})
     */
    private $contacts;

    /**
     * Profile constructor.
     */
    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return trim(sprintf('%s %s', $this->getFirstName(), $this->getLastName())) ?: null;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return Profile
     */
    public function setFirstName(string $firstName): Profile
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return Profile
     */
    public function setLastName(string $lastName): Profile
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getBirthDay(): DateTime
    {
        return $this->birthDay;
    }

    /**
     * @param DateTime $birthDay
     *
     * @return Profile
     */
    public function setBirthDay(DateTime $birthDay): Profile
    {
        $this->birthDay = $birthDay;

        return $this;
    }

    /**
     * @return string
     */
    public function getBiography(): string
    {
        return $this->biography;
    }

    /**
     * @param string $biography
     *
     * @return Profile
     */
    public function setBiography(string $biography): Profile
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return Profile
     */
    public function setUser(User $user): Profile
    {
        if (!$this->user) {
            $this->user = $user;
        }

        return $this;
    }

    /**
     * @param Contact[] $contacts
     *
     * @return Profile
     */
    public function setContacts($contacts): Profile
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * @return Contact[]|Collection
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }
}
