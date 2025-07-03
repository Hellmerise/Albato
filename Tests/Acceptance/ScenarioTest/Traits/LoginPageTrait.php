<?php

declare(strict_types=1);


namespace Tests\Acceptance\ScenarioTest\Traits;


use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Acceptance\LoginPage;

trait LoginPageTrait
{
    /**
     * @var LoginPage Класс PageObject.
     */
    private LoginPage        $loginPage;
    private AcceptanceTester $acceptanceTester;
    
    final public function _before(AcceptanceTester $I): void
    {
        $this->loginPage = new LoginPage($I);
        $this->acceptanceTester = $I;
    }
}