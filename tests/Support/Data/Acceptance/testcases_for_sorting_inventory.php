<?php

use Tests\Support\Config\InventorySortingEnum;

return [
    [
        "wantTo" => "Отсортировать товары по возрастанию цены (low to high)",
        "mode"   => InventorySortingEnum::LOHI->value,
    ],
    [
        "wantTo" => "Отсортировать товары по убыванию цены (high to low)",
        "mode"   => InventorySortingEnum::HILO->value,
    
    ],
    [
        "wantTo" => "Отсортировать товары по названию в порядке возрастания (A to Z)",
        "mode"   => InventorySortingEnum::AZ->value,
    
    ],
    [
        "wantTo" => "Отсортировать товары по названию в порядке убывания (Z to A)",
        "mode"   => InventorySortingEnum::ZA->value,
    ],
];