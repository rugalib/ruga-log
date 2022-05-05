<?php

namespace Ruga\Log;

use Ruga\Std\Enum\AbstractEnum;

class Type /*extends AbstractEnum*/
{
    /** The message is a final result */
    const RESULT = 'RESULT';
    
    /** The message is a intermediate result */
    const STATUS = 'STATUS';
    
    /** The message is a exception */
    const EXCEPTION = 'EXCEPTION';
}