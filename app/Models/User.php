<?php

declare(strict_types=1);

namespace App\Models;

class User
{
    public ?int $id;
    public string $name;
    public string $password;
    public string $enrollment; // "matrícula"
    public string $email;
    public string $phone;
    public string $address;
    public string $city;
    public string $district;
    public string $zipcode;
}
