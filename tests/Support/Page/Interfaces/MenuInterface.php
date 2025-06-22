<?php

declare(strict_types=1);

namespace Tests\Support\Page\Interfaces;


interface MenuInterface extends PageInterface
{
    public function returnButtonMenu(): string;
    
    public function returnButtonCart(): string;
    
    public function returnButtonAllItems(): string;
    
    public function returnButtonAbout(): string;
    
    public function returnButtonLogout(): string;
    
    public function returnButtonResetAppState(): string;
    
    public function returnValueItemsInCart(): int;
    
    public function checkLogo(): void;
    
    public function checkTitlePage(): void;
}