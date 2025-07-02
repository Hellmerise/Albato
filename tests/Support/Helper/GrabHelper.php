<?php

declare(strict_types=1);

namespace Tests\Support\Helper;


use Codeception\Module;

class GrabHelper extends Module
{
    final public function extractFloatFrom(string $text, bool $safeMode = false): float
    {
        if (preg_match('/\$\s*(\d+(?:\.\d+)?)/', $text, $matches)) {
            $price = (float)$matches[1];
            
            if ($safeMode) {
                return (float)number_format($price, 2, '.', '');
            }
            return $price;
        }
        return 0.0;
    }
}
