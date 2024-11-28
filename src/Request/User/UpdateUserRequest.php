<?php
declare(strict_types=1);

namespace App\Request\User;

use App\Request\RequestDto;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserRequest implements RequestDto
{
    private ?string $username;

    #[Assert\Email]
    private ?string $email;

    private ?string $password;

    private ?string $bio;

    #[Assert\Url]
    private ?string $image;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): UpdateUserRequest
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): UpdateUserRequest
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): UpdateUserRequest
    {
        $this->password = $password;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): UpdateUserRequest
    {
        $this->bio = $bio;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): UpdateUserRequest
    {
        $this->image = $image;

        return $this;
    }
}
