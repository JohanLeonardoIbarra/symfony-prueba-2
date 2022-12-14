<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use Symfony\Component\Validator\Constraints as Assert;

#[ODM\Document]
#[Unique('email')]
class User
{
    #[ODM\Id]
    private $id;

    #[ODM\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Regex("/\d/", message: 'Your name cannot contain a number', match: false)]
    private string $name;

    #[ODM\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Regex("/\d/", message: 'Your surname cannot contain a number', match: false)]
    private string $surname;

    #[ODM\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Email]
    private string $email;

    #[ODM\Field(type: 'bool')]
    #[Assert\NotNull]
    private bool $firstBuy;

    /**
     * @return bool
     */
    public function isFirstBuy(): bool
    {
        return $this->firstBuy;
    }

    /**
     * @param bool $firstBuy
     * @return User
     */
    public function setFirstBuy(bool $firstBuy): User
    {
        $this->firstBuy = $firstBuy;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return User
     */
    public function setSurname(string $surname): static
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }


}