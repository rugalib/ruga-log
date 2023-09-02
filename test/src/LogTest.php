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
class LogTest extends TestCase
{
    public function testCanLogSimpleMessage(): void
    {
        $this->expectNotToPerformAssertions();
        \Ruga\Log::log_msg("Hello World");
    }
    
    
    
    public function testCanAddLog(): void
    {
        $s = \Ruga\Log::addLog("Log message");
        $this->assertEquals("DEBUG|Log message", $s);
    
        $s = \Ruga\Log::addLog([new TestObject(), "Log message"]);
        $this->assertEquals("DEBUG|Ruga\Log\Test\TestObject: Log message", $s);
    
    }
    
    
    
    public function testCanAddDebugLog(): void
    {
        $s = \Ruga\Log::addLog("Log message", Severity::DEBUG);
        $this->assertEquals("DEBUG|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::DEBUG, Type::STATUS);
        $this->assertEquals("DEBUG|STATUS|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::DEBUG, Type::RESULT);
        $this->assertEquals("DEBUG|RESULT|Log message", $s);
        
        $this->expectException(\RuntimeException::class);
        throw \Ruga\Log::addLog(new \RuntimeException("Test exception", 123), Severity::DEBUG);
    }
    
    
    
    public function testCanAddInformationalLog(): void
    {
        $s = \Ruga\Log::addLog("Log message", Severity::INFORMATIONAL);
        $this->assertEquals("INFORMATIONAL|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::INFORMATIONAL, Type::STATUS);
        $this->assertEquals("INFORMATIONAL|STATUS|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::INFORMATIONAL, Type::RESULT);
        $this->assertEquals("INFORMATIONAL|RESULT|Log message", $s);
        
        $this->expectException(\RuntimeException::class);
        throw \Ruga\Log::addLog(new \RuntimeException("Test exception", 123), Severity::INFORMATIONAL);
    }
    
    
    
    public function testCanAddNoticeLog(): void
    {
        $s = \Ruga\Log::addLog("Log message", Severity::NOTICE);
        $this->assertEquals("NOTICE|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::NOTICE, Type::STATUS);
        $this->assertEquals("NOTICE|STATUS|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::NOTICE, Type::RESULT);
        $this->assertEquals("NOTICE|RESULT|Log message", $s);
        
        $this->expectException(\RuntimeException::class);
        throw \Ruga\Log::addLog(new \RuntimeException("Test exception", 123), Severity::NOTICE);
    }
    
    
    
    public function testCanAddWarningLog(): void
    {
        $s = \Ruga\Log::addLog("Log message", Severity::WARNING);
        $this->assertEquals("WARNING|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::WARNING, Type::STATUS);
        $this->assertEquals("WARNING|STATUS|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::WARNING, Type::RESULT);
        $this->assertEquals("WARNING|RESULT|Log message", $s);
        
        $this->expectException(\RuntimeException::class);
        throw \Ruga\Log::addLog(new \RuntimeException("Test exception", 123), Severity::WARNING);
    }
    
    
    
    public function testCanAddErrorLog(): void
    {
        $s = \Ruga\Log::addLog("Log message", Severity::ERROR);
        $this->assertEquals("ERROR|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::ERROR, Type::STATUS);
        $this->assertEquals("ERROR|STATUS|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::ERROR, Type::RESULT);
        $this->assertEquals("ERROR|RESULT|Log message", $s);
        
        $this->expectException(\RuntimeException::class);
        throw \Ruga\Log::addLog(new \RuntimeException("Test exception", 123), Severity::ERROR);
    }
    
    
    
    public function testCanAddCriticalLog(): void
    {
        $s = \Ruga\Log::addLog("Log message", Severity::CRITICAL);
        $this->assertEquals("CRITICAL|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::CRITICAL, Type::STATUS);
        $this->assertEquals("CRITICAL|STATUS|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::CRITICAL, Type::RESULT);
        $this->assertEquals("CRITICAL|RESULT|Log message", $s);
        
        $this->expectException(\RuntimeException::class);
        throw \Ruga\Log::addLog(new \RuntimeException("Test exception", 123), Severity::CRITICAL);
    }
    
    
    
    public function testCanAddAlertLog(): void
    {
        $s = \Ruga\Log::addLog("Log message", Severity::ALERT);
        $this->assertEquals("ALERT|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::ALERT, Type::STATUS);
        $this->assertEquals("ALERT|STATUS|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::ALERT, Type::RESULT);
        $this->assertEquals("ALERT|RESULT|Log message", $s);
        
        $this->expectException(\RuntimeException::class);
        throw \Ruga\Log::addLog(new \RuntimeException("Test exception", 123), Severity::ALERT);
    }
    
    
    
    public function testCanAddEmergencyLog(): void
    {
        $s = \Ruga\Log::addLog("Log message", Severity::EMERGENCY);
        $this->assertEquals("EMERGENCY|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::EMERGENCY, Type::STATUS);
        $this->assertEquals("EMERGENCY|STATUS|Log message", $s);
        
        $s = \Ruga\Log::addLog("Log message", Severity::EMERGENCY, Type::RESULT);
        $this->assertEquals("EMERGENCY|RESULT|Log message", $s);
        
        $this->expectException(\RuntimeException::class);
        throw \Ruga\Log::addLog(new \RuntimeException("Test exception", 123), Severity::EMERGENCY);
    }
    
    
    
    public function testCanLogException(): void
    {
        $this->expectException(\RuntimeException::class);
        throw \Ruga\Log::addLog(new \RuntimeException("Test exception", 123));
    }
    
    
    
    public function testCanLogExceptionWithObject(): void
    {
        $this->expectException(\RuntimeException::class);
        throw \Ruga\Log::addLog(new \RuntimeException("Test exception", 123), null, null, new TestObject());
    }
    
    
    
    public function testCanLogFunctionHead(): void
    {
        $s = \Ruga\Log::functionHead();
        $this->assertEquals("DEBUG|STATUS|Ruga\Log\Test\LogTest->testCanLogFunctionHead()", substr($s, 0, 60));
    }
    
    
    
    public function testCanLogFunctionHeadWithObject(): void
    {
        $s = \Ruga\Log::functionHead(new TestObject());
        $this->assertEquals(
            "DEBUG|STATUS|Ruga\Log\Test\TestObject: Ruga\Log\Test\LogTest->testCanLogFunctionHeadWithObject()",
            substr($s, 0, 96)
        );
    }
    
    
}
