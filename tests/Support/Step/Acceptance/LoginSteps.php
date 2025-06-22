<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;


use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Page\Acceptance\LoginPage;
use Tests\Support\Step\Interfaces\LoginInterface;

class LoginSteps extends BaseSteps implements LoginInterface
{
    protected readonly LoginPage $loginPage;
    private readonly string      $field_username;
    private readonly string      $field_password;
    private readonly string      $button_login;
    private readonly string      $error_message;
    
    public function __construct(Scenario $scenario, AcceptanceTester $acceptanceTester)
    {
        parent::__construct($scenario);
        $this->loginPage = new LoginPage($acceptanceTester);
        
        $this->field_username = $this->loginPage->returnFieldUsername();
        $this->field_password = $this->loginPage->returnFieldPassword();
        $this->button_login = $this->loginPage->returnButtonLogin();
    }
    
    final public function login(string $username, string $password): void
    {
        $this->amOnPage('/');
        
        $this->loginPage->waitForPageVisible();
        
        $this->safeFillField($this->field_username, $username);
        $this->safeFillField($this->field_password, $password);
        $this->safeClick($this->button_login);
        
        $this->loginPage->waitForPageNotVisible();
    }
}
