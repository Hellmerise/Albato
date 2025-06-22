<?php

declare(strict_types=1);


namespace Tests\Acceptance;

use Codeception\Attribute\Group;
use Codeception\Example;
use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Acceptance\LoginPage;

#[Group('second')]
final class CheckingAccessPagesCest
{
    private LoginPage $loginPage;
    public function _before(AcceptanceTester $I): void
    {
        $this->loginPage = new LoginPage($I);
    }
    
    /**
     * @dataProvider dataProvider
     */
    public function testAccessWithoutLogged(AcceptanceTester $I, Example $example): void
    {
        $I->wantTo($example["wantTo"]);
        
        foreach ($example["pages"] as $page) {
            $expectedError = sprintf(
                "Epic sadface: You can only access '/%s' when you are logged in.",
                $page
            );
            
            $I->amOnPage($page);
            $this->loginPage->waitForPageVisible();
            $actualError = $this->loginPage->returnErrorMessage();
            $I->assertEquals($expectedError, $actualError);
        }
    }
    
    private function dataProvider(): array
    {
        return require dirname(__DIR__ ) . '/Support/Data/Acceptance/testcases_access_to_pages_without_auth.php';
    }
}
