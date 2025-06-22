<?php

declare(strict_types=1);

namespace Tests\Support\Step\Interfaces;


interface InventoryInterface
{
    public function loginAsStandardUser(): void;
    
    public function loginAsLockedOutUser(): void;
    
    public function loginAsProblemUser(): void;
    
    public function loginAsPerformanceGlitchUser(): void;
    
    public function loginAsErrorUser(): void;
    
    public function loginAsVisualUser(): void;
    
    public function checkItemsSorting(string $modeSort): void;
    
    public function clickButtonCart(): void;
    
    public function fillCart(): void;
    
}