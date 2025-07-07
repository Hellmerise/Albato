<?php

namespace Tests\Acceptance\ScenarioTest\Traits;


use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Step\Acceptance\LoginSteps;

trait LoginStepsTrait
{
    /**
     * @var LoginSteps Класс для взаимодействия с PageObject.
     */
    protected LoginSteps $loginSteps;
    private Scenario $scenario;
    protected AcceptanceTester $acceptanceTester;
    
    public function _before(Scenario $scenario, AcceptanceTester $I): void
    {
        $this->loginSteps = new LoginSteps($scenario, $I);
        
        $this->scenario = $scenario;
        $this->acceptanceTester = $I;
    }
}