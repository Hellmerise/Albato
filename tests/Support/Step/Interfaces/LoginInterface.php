<?php

declare(strict_types=1);

namespace Tests\Support\Step\Interfaces;


interface LoginInterface
{
    public function login(string $username, string $password): void;
}