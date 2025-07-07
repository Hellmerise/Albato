<?php

declare(strict_types=1);

use Tests\Support\Config\TestCasesEnum;

return [
    [
        TestCasesEnum::KEY_COUNTS_PRODUCTS => 6,
        TestCasesEnum::KEY_FIRSTNAME       => 'Дмитрий',
        TestCasesEnum::KEY_LASTNAME        => 'Базарнов',
        TestCasesEnum::KEY_POSTAL_CODE     => '123456',
    ],
    [
        TestCasesEnum::KEY_COUNTS_PRODUCTS => 0,
        TestCasesEnum::KEY_FIRSTNAME       => 'Дмитрий',
        TestCasesEnum::KEY_LASTNAME        => 'Базарнов',
        TestCasesEnum::KEY_POSTAL_CODE     => '123456',
    ],
    [
        TestCasesEnum::KEY_FIRSTNAME   => 'Дмитрий',
        TestCasesEnum::KEY_LASTNAME    => 'Базарнов',
        TestCasesEnum::KEY_POSTAL_CODE => '123456',
    ],
    [
        TestCasesEnum::KEY_FIRSTNAME => 'Дмитрий',
        TestCasesEnum::KEY_LASTNAME  => 'Базарнов',
    ],
];