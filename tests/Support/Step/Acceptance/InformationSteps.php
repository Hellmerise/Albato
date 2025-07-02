<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;


use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Exception\AssertionEmptyFailed;
use Tests\Support\Exception\InvalidDataForm;
use Tests\Support\Page\Acceptance\InformationPage;


final class InformationSteps extends AcceptanceTester
{
    private readonly InformationPage $informationPage;
    
    final public function __construct(Scenario $scenario, AcceptanceTester $acceptanceTester)
    {
        parent::__construct($scenario);
        
        $this->informationPage = new InformationPage($acceptanceTester);
    }
    
    final public function fillFieldsInformation(?string $firstname, ?string $lastname, ?string $postalCode): void
    {
        try {
            $this->processFillingInformationFields($firstname, $lastname, $postalCode);
            $this->informationPage->clickButtonContinue();
            $this->informationPage->waitForPageNotVisible();
        } catch (AssertionEmptyFailed|InvalidDataForm $failed) {
            $this->fail($failed->getMessageError());
        }
    }
    
    /**
     * @throws AssertionEmptyFailed
     */
    final public function processFillingInformationFields(?string $firstname, ?string $lastname, ?string $postalCode): void
    {
        $this->informationPage->assertHeaderPage();
        
        $fillFields = [
            'fillFirstname'  => $firstname,
            'fillLastname'   => $lastname,
            'fillPostalCode' => $postalCode,
        ];
        
        foreach ($fillFields as $method => $value) {
            if ($value !== null) {
                $this->informationPage->$method($value);
            }
        }
    }
}
