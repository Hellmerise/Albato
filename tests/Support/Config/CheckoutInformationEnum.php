<?php

declare(strict_types=1);

namespace Tests\Support\Config;


enum CheckoutInformationEnum: string
{
    case FIRSTNAME = 'firstname';
    case LASTNAME = 'lastname';
    case POSTAL_CODE = 'postal_code';
}
