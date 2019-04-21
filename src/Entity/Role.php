<?php

namespace App\Entity;

use Aristek\Bundle\SymfonyJSONAPIBundle\Enum\UserRoles;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class Role
 *
 * @ORM\Entity()
 */
class Role
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(length=50)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column()
     */
    private $description;

    /**
     * @Assert\Callback()
     *
     * @param ExecutionContextInterface $context
     *
     * @return void
     */
    public function validate(ExecutionContextInterface $context): void
    {
        if (!array_key_exists($this->getCode(), UserRoles::getRoles())) {
            $context->addViolation(sprintf('Wrong Role Code "%s".', $this->getCode()));
        }
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Role
     */
    public function setCode(string $code): Role
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Role
     */
    public function setDescription(string $description): Role
    {
        $this->description = $description;

        return $this;
    }
}
