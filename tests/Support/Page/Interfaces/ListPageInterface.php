<?php

declare(strict_types=1);

namespace Tests\Support\Page\Interfaces;


interface ListPageInterface
{
    public function returnListProductsFromPage(): array;
}