<?php

declare(strict_types=1);

namespace Tests\Support\Data\Acceptance;


enum Users: string
{
    case STANDARD = 'standard_user';
    case LOCKED_OUT = 'locked_out_user';
    case PROBLEM = 'problem_user';
    case PERFORMANCE_GLITCH = 'performance_glitch_user';
    case ERROR = 'error_user';
    case VISUAL = 'visual_user';
    
    public const string PASSWORD = 'secret_sauce';
}
