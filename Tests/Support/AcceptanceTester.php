<?php

declare(strict_types=1);

namespace Tests\Support;


use Codeception\Actor;
use Codeception\Exception\TestRuntimeException;
use Tests\Support\Config\TestConfigEnum;



/**
 * Inherited Methods
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class AcceptanceTester extends Actor
{
    use _generated\AcceptanceTesterActions;
    
    private const int PAGE_TIMEOUT = TestConfigEnum::WAIT_PAGE_LOAD;
    
    private const string   JS_WAITING_LOAD_PAGE = "return (document.readyState === 'complete' && (typeof jQuery === 'undefined' || jQuery.active === 0));";
    
    final public function waitForPageLoad(): void
    {
        $scriptJS = self::JS_WAITING_LOAD_PAGE;
        
        $startTime = microtime(true);
        
        while (!$this->executeJS($scriptJS) && (microtime(true) - $startTime) < self::PAGE_TIMEOUT) {
            $this->wait(0.1);
        }
        
        if ((microtime(true) - $startTime) >= self::PAGE_TIMEOUT) {
            throw new TestRuntimeException(
                sprintf(
                    "Страница и скрипты не были завершены за %s секунд.\nМетод: %s ",
                    self::PAGE_TIMEOUT,
                    __METHOD__,
                )
            );
        }
    }
}
