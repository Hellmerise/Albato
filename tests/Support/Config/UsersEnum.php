<?php

declare(strict_types=1);

namespace Tests\Support\Config;


enum UsersEnum: string
{
    case STANDARD = 'standard_user';
    case LOCKED_OUT = 'locked_out_user';
    case PROBLEM = 'problem_user';
    case PERFORMANCE_GLITCH = 'performance_glitch_user';
    case ERROR = 'error_user';
    case VISUAL = 'visual_user';
    
    private const string PASSWORD = 'secret_sauce';
    
    final public function getPassword(): string
    {
        return match ($this) {
            self::STANDARD,
            self::LOCKED_OUT,
            self::PROBLEM,
            self::PERFORMANCE_GLITCH,
            self::ERROR,
            self::VISUAL => self::PASSWORD,
        };
    }
    
}
