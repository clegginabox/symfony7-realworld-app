<?php
declare(strict_types=1);

namespace App\Response;

class UserResponse
{
    private string $email;

    private ?string $token = null;

    private string $username;

    private ?string $bio = null;

    private ?string $image = null;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserResponse
    {
        $this->email = $email;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): UserResponse
    {
        $this->token = $token;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): UserResponse
    {
        $this->username = $username;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): UserResponse
    {
        $this->bio = $bio;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): UserResponse
    {
        $this->image = $image;

        return $this;
    }
}
