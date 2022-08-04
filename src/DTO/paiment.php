<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Address;

class paiment
{
    public string $cardNumber;

    public string $expirationMonth;

    public string $expirationYear;

    public string $cvc;

    public Address $address;
}
