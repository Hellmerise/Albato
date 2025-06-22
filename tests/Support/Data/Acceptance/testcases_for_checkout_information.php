<?php

use Tests\Support\Config\CheckoutInformationEnum;

return [
    [
        "wantTo" => "Проверить страницу оформления заказа (шаг 1), на которой все поля обязательны к заполнению",
        "fields" => [
            CheckoutInformationEnum::FIRSTNAME->value   => "Иванов",
            CheckoutInformationEnum::LASTNAME->value    => "Иван",
            CheckoutInformationEnum::POSTAL_CODE->value => "1234567",
        ],
    ],
];