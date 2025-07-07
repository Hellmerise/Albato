<?php

declare(strict_types=1);

namespace Tests\Support\Step\Acceptance;


use Codeception\Scenario;
use Tests\Support\AcceptanceTester;
use Tests\Support\Config\SuccessPaymentEnum;
use Tests\Support\Page\Acceptance\CompletePage;
use Tests\Support\Page\Acceptance\testPage;

final class CompleteSteps extends AcceptanceTester
{
    private readonly CompletePage $completePage;
    private readonly string $text_header_success;
    private readonly string $text_message_success;
    private readonly string $text_header_actual;
    private readonly string $text_message_actual;
    
    final public function __construct(Scenario $scenario, AcceptanceTester $acceptanceTester)
    {
        parent::__construct($scenario);
        
        $this->completePage = new CompletePage($acceptanceTester);
        
        $this->text_header_success = $this->completePage->getHeaderSuccess();
        $this->text_message_success = $this->completePage->getMessageSuccess();
        $this->text_header_actual = $this->completePage->getTextFromHeader();
        $this->text_message_actual = $this->completePage->getTextFromMessage();
    }
    
    final public function seeSuccessPayment(): void
    {
        $this->completePage->assertHeaderPage();
        
        $this->assertEquals($this->text_header_success, $this->text_header_actual);
        $this->assertEquals($this->text_message_success, $this->text_message_actual);
    }
    
    final public function assertCartEmpty(): void
    {
        $countInCart = $this->completePage->getValueCart();
        
        $this->assertEquals(0, $countInCart);
    }
}
