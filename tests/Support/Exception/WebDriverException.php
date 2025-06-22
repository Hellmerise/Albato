<?php

declare(strict_types=1);

namespace Tests\Support\Exception;

use RuntimeException;

final class WebDriverException extends RuntimeException
{
    public static function driverError(string $message): self
    {
        return new self("Произошла Ошибка в веб-драйвере: {$message}");
    }
}