<?php

declare(strict_types=1);

namespace App\Request\User;

use App\Request\RequestDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserRequest implements RequestDto
{
    #[Assert\NotBlank]
    private string $username;

    #[Assert\Email]
    #[Assert\NotBlank]
    private string $email;

    #[Assert\NotBlank]
    private string $password;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): CreateUserRequest
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): CreateUserRequest
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): CreateUserRequest
    {
        $this->password = $password;

        return $this;
    }
}
