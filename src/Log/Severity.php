<?php

namespace Ruga\Log;

use Psr\Log\LogLevel;

class Severity extends LogLevel
{
    /** system is unusable */
    const EMERGENCY = 'EMERGENCY';
    /** action must be taken immediately */
    const ALERT = 'ALERT';
    /** critical conditions */
    const CRITICAL = 'CRITICAL';
    /** error conditions */
    const ERROR = 'ERROR';
    /** warning conditions */
    const WARNING = 'WARNING';
    /** normal but significant condition */
    const NOTICE = 'NOTICE';
    /** informational messages */
    const INFORMATIONAL = 'INFORMATIONAL';
    /** debug messages */
    const DEBUG = 'DEBUG';
    
    const ALL = [
        self::EMERGENCY,
        self::ALERT,
        self::CRITICAL,
        self::ERROR,
        self::WARNING,
        self::NOTICE,
        self::INFORMATIONAL,
        self::DEBUG,
    ];
    
    const NONE = [];
}