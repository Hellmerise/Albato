<?php

declare(strict_types=1);

namespace Tests\Support\Helper;


use Codeception\Exception\ModuleException;
use Codeception\Module;
use Codeception\Module\WebDriver;
use Tests\Support\Exception\WebDriverException;

class GrabHelper extends Module
{
    final public function grabFloatFrom(string $cssOrXPath, bool $safeMode = false): float
    {
        try {
            /** @var WebDriver $webDriver */
            $webDriver = $this->getModule('WebDriver');
        } catch (ModuleException $moduleException) {
            throw WebDriverException::driverError($moduleException->getMessage());
        }
        
        $text = $webDriver->grabTextFrom($cssOrXPath);
        
        if (preg_match('/\$\d+\.?\d*/', $text, $matches)) {
            $priceText = $matches[0];
        } else {
            $priceText = $text;
        }
        
        return match (true) {
            str_starts_with($priceText, '$') => $this->grabPriceFrom($priceText, '$', $safeMode),
            str_contains($priceText, 'RUB') => $this->grabPriceFrom($priceText, 'RUB', $safeMode),
            default => (float)$priceText,
        };
    }
    
    final public function grabPriceFrom(string $text, string $currency, bool $safeMode = false): float
    {
        $result = (float)trim(str_replace([$currency, ' '], '', $text));
        
        return $safeMode
            ? (float)number_format($result, 2, '.', '')
            : $result;
    }
}
