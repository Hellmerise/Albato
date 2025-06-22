<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;


use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Acceptance\CheckoutInformationPage;

final class CheckoutInformationSteps extends BaseSteps
{
    private readonly CheckoutInformationPage $checkoutInformationPage;
    
    final public function __construct(Scenario $scenario, AcceptanceTester $acceptanceTester)
    {
        parent::__construct($scenario);
        $this->checkoutInformationPage = new CheckoutInformationPage($acceptanceTester);
    }
    
    final public function clickButtonContinue(): void
    {
        $this->checkoutInformationPage->checkTitlePage();
        
        $this->safeClick($this->checkoutInformationPage->returnButtonContinue());
        
        $this->checkoutInformationPage->waitForPageNotVisible();
    }
    
    final public function fillInformation(array $data, bool $isAssertion = false): void
    {
        $this->checkoutInformationPage->checkTitlePage();
        
        $fields = $this->checkoutInformationPage->returnFields();
        
        foreach ($fields as $key => $field) {
            $this->processField($field, $key, $data, $isAssertion);
        }
    }
    
    private function processField(string $fieldSelector, string $fieldKey, array $formData, bool $isAssertion): void
    {
        if ($isAssertion) {
            $this->verifyFieldError($fieldSelector);
        }
        
        if (isset($formData[$fieldKey])) {
            $this->safeFillField($fieldSelector, $formData[$fieldKey]);
            $this->dontSeeInField($fieldSelector, '');
            $this->seeInField($fieldSelector, $formData[$fieldKey]);
        }
    }
    
    private function verifyFieldError(string $fieldSelector): void
    {
        $placeholder = $this->grabAttributeFrom($fieldSelector, 'placeholder');
        
        $errorText = match ($placeholder) {
            'Zip/Postal Code' => 'Postal Code',
            default => $placeholder
        };
        
        $expectedError = sprintf(
            "Error: %s is required",
            $errorText
        );
        
        $this->seeInField($fieldSelector, '');
        $this->safeClick($this->checkoutInformationPage->returnButtonContinue());
        
        $actualError = $this->checkoutInformationPage->returnErrorMessage();
        
        $this->assertEquals(
            $expectedError,
            $actualError,
            sprintf(
                "Ожидалось сообщение об ошибке '%s', но получено сообщение '%s'",
                $expectedError,
                $actualError
            )
        );
    }
    
}
