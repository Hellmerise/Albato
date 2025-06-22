<?php

declare(strict_types=1);

namespace Tests\Support\Page\Acceptance;


use Tests\Support\AcceptanceTester;
use Tests\Support\Config\CheckoutInformationEnum;
use Tests\Support\Page\Abstract\AbstractMenuPage;

final class CheckoutInformationPage extends AbstractMenuPage
{
    private const string FIELD_FIRSTNAME     = "//input[@data-test = 'firstName']";
    private const string FIELD_LASTNAME      = "//input[@data-test = 'lastName']";
    private const string POSTAL_CODE         = "//input[@data-test = 'postalCode']";
    private const string BUTTON_CONTINUE     = "//input[@data-test = 'continue']";
    private const string BUTTON_CANCEL       = "//button[@data-test = 'cancel']";
    private const string ERROR_MESSAGE_XPATH = "//h3[@data-test = 'error']";
    
    protected static string $title = "Checkout: Your Information";
    
    final public function __construct(AcceptanceTester $I)
    {
        self::$acceptanceTester = $I;
    }
    
    final public function returnErrorMessage(): string
    {
        return $this->getErrorMessageOrFail(self::ERROR_MESSAGE_XPATH);
    }
    
    final public function returnFields(): array
    {
        return [
            CheckoutInformationEnum::FIRSTNAME->value   => self::FIELD_FIRSTNAME,
            CheckoutInformationEnum::LASTNAME->value    => self::FIELD_LASTNAME,
            CheckoutInformationEnum::POSTAL_CODE->value => self::POSTAL_CODE,
        ];
    }
    
    final protected function returnElementsForWait(): array
    {
        return [
            self::FIELD_FIRSTNAME,
            self::FIELD_LASTNAME,
            self::POSTAL_CODE,
            self::BUTTON_CONTINUE,
        ];
    }
    
    final public function returnButtonContinue(): string
    {
        return self::BUTTON_CONTINUE;
    }
}
