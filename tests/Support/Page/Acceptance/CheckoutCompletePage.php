<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Abstract\AbstractMenuPage;

final class CheckoutCompletePage extends AbstractMenuPage
{
    private const string HEADER              = "//h2[@data-test = 'complete-header']";
    private const string MESSAGE             = "//div[@data-test = 'complete-text']";
    private const string BUTTON_BACK_TO_HOME = "//button[@data-test = 'back-to-products']";
    protected static string $title = "Checkout: Complete!";
    
    public function __construct(AcceptanceTester $I)
    {
        self::$acceptanceTester = $I;
    }
    
    final public function returnTextHeader(): string
    {
        return self::$acceptanceTester->grabTextFrom(self::HEADER);
    }
    
    final public function returnTextMessage(): string
    {
        return self::$acceptanceTester->grabTextFrom(self::MESSAGE);
    }
    
    final public function returnButtonBackToHome(): string
    {
        return self::BUTTON_BACK_TO_HOME;
    }
    
    final protected function returnElementsForWait(): array
    {
        return [
            self::HEADER,
            self::MESSAGE,
            self::BUTTON_BACK_TO_HOME,
        ];
    }
}
