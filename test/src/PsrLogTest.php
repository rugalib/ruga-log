<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

namespace Ruga\Log\Test;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Ruga\Log\Severity;
use Ruga\Log\Type;
use Ruga\Std\Test\Enum\Weekday;


/**
 * @author                 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 */
class PsrLogTest extends TestCase
{
    public function testCanLogSimpleMessage(): void
    {
        $logger = new \Ruga\Log();
        $this->expectNotToPerformAssertions();
        $logger->debug("Log Message");
        $logger->info("Log Message");
        $logger->notice("Log Message");
        $logger->warning("Log Message");
        $logger->error("Log Message");
        $logger->critical("Log Message");
        $logger->alert("Log Message");
        $logger->emergency("Log Message");
    }
    
    
    
    public function testCanLogMessage(): void
    {
        $logger = new \Ruga\Log();
        $this->expectNotToPerformAssertions();
        $logger->log(LogLevel::DEBUG, "Log Message");
        $logger->log(Severity::INFORMATIONAL, "Log Message");
        $logger->log(LogLevel::NOTICE, "Log Message");
        $logger->log(Severity::WARNING, "Log Message");
        $logger->log(LogLevel::ERROR, "Log Message");
        $logger->log(Severity::CRITICAL, "Log Message");
        $logger->log(LogLevel::ALERT, "Log Message");
        $logger->log(Severity::EMERGENCY, "Log Message");
    }
    
    
    
    public function testCanLogException(): void
    {
        $logger = new \Ruga\Log();
        $this->expectNotToPerformAssertions();
        $logger->error("Log Message", ["exception" => new \RuntimeException("Test exception", 123)]);
    }
    
}
