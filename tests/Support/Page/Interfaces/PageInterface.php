<?php

declare(strict_types=1);

namespace Tests\Support\Page\Interfaces;


interface PageInterface
{
    /**
     * Ожидает загрузку страницы
     */
    public function waitForPageVisible(): void;
    
    /**
     * Ожидает исчезновение страницы
     */
    public function waitForPageNotVisible(): void;
}