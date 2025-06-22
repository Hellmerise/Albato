<?php

declare(strict_types=1);

namespace Tests\Support\Page\Interfaces;


interface CalculationInterface extends ListPageInterface
{
    public function checkCalculationTotals();
    
    public function assertCalculationTotalIsNull(): void;
}