<?php

namespace App\Entity;

use Aristek\Bundle\ExtraBundle\Model\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;

/**
 * Class Contact
 *
 * @ORM\Entity()
 */
class Contact
{
    use IdTrait;

    /**
     * @var string
     *
     * @ORM\Column()
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column()
     *
     * @Assert\Email(mode=Email::VALIDATION_MODE_LOOSE)
     */
    private $email;

    /**
     * @var Profile
     *
     * @ORM\ManyToOne(targetEntity=Profile::class, inversedBy="contacts")
     * @ORM\JoinColumn(referencedColumnName="user_id", nullable=false)
     */
    private $profile;

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return Contact
     */
    public function setPhone(string $phone): Contact
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Contact
     */
    public function setEmail(string $email): Contact
    {
        $this->email = $email;

        return $this;
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
     * @return Contact
     */
    public function setProfile(Profile $profile): Contact
    {
        $this->profile = $profile;

        return $this;
    }
}
