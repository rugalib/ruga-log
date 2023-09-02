<?php
/*
 * SPDX-FileCopyrightText: 2023 Roland Rusch, easy-smart solution GmbH <roland.rusch@easy-smart.ch>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

declare(strict_types=1);

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