<?php

declare(strict_types=1);

namespace Ruga\Log\Test;

use PHPUnit\Framework\TestCase;


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
 
    
    
}
