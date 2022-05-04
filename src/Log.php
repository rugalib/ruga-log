<?php

namespace Ruga;

use Laminas\Config\Config;
use Ruga\Log\Severity;
use Ruga\Log\Type;

class Log
{
    
    /**
     * Counter for recursive calls. Prevents endless log loop.
     */
    private static $iiterationscounter = 0;
    
    
    
    /**
     * Writes the $message to the log file.
     *
     * @param $message string
     *                 Message
     */
    static public function log_msg($message)
    {
        $ts = date('Y-m-d H:i:s');
        $lines = explode("\n", $message);
        foreach ($lines as $lstr) {
            $lstr = trim($lstr);
            if (empty($lstr)) {
                continue;
            }
            if (php_sapi_name() == 'cli') {
                error_log("{$ts} {$lstr}", 4);
// 				error_log("{$ts} {$lstr}", 3, "php://stderr");
// 				file_put_contents("php://stderr", "{$ts} {$lstr}");
            } else {
                error_log("{$ts} {$lstr}", 0);
            }
        }
    }
    
    
    
    /**
     * Creates a log entry.
     *
     * @param string|array|\Throwable                                         $msg
     *        Message|array($facility, $message)
     * @param null                                                            $severity
     * @param null                                                            $type
     * @param string|Zend_Db_Table_Row_Abstract|Zend_Db_Table_Abstract|object $refTable
     *        Name der Tabelle|Zeile|Tabelle|Objekt
     * @param string|int                                                      $refId
     *        Primärschlüssel
     *
     * @return string|\Exception
     * @throws \Exception
     */
    public static function addLog($msg, $severity = null, $type = null, $refTable = null, $refId = null)
    {
        self::$iiterationscounter++;
        // Abbrechen, wenn addLog() rekursiv aufgerufen wird.
        if (self::$iiterationscounter > 1) {
            static::log_msg('IITERATION ' . self::$iiterationscounter . ' ($msg=' . print_r($msg, true) . ')');
            self::$iiterationscounter--;
            return null;
        }
        
        /** @var Config $config */
        $config = static::getConfig();
        if (!$config) {
            throw new \Exception('[Ruga_Log] section in config not found.');
        }
        $dbLogSeverity = $config->get('db', new Config([]))
            ->get('log_severity', Severity::ALL)
            ->toArray();
        $LogMsgLogSeverity = $config->get('log_msg', new Config([]))
            ->get('log_severity', Severity::ALL)
            ->toArray();
        
        $e = null;
        
        // if(is_object($msg)) static::log_msg('addLog(): $msg=' . get_class($msg));
        // else static::log_msg('addLog(): $msg=' . gettype($msg));
        
        // ARRAY
        if (is_array($msg)) {
            list ($facility, $message) = $msg;
        } // EXCEPTION
        elseif (is_a($msg, \Exception::class)) {
            $e = $msg;
            $message = $msg->getCode() == 0 ? '' : '(' . $msg->getCode() . ') ';
            $message .= $msg->getMessage();
            $message .= ' in "' . $msg->getFile() . '"';
            $message .= ' at line ' . $msg->getLine() . "\n";
            $message .= $msg->getTraceAsString();
            if ($severity === null) {
                $severity = Severity::ERROR;
            }
            if ($type === null) {
                $type = Type::EXCEPTION;
            }
        } // STRING
        else {
            $message = $msg;
        }
        
        
        // Default-Severity = DEBUG
        if ($severity === null) {
            $severity = Severity::DEBUG;
        }
        
        if (!isset($facility) && is_object($refTable)) {
            $facility = $refTable;
        }
        if (!isset($facility)) {
            $facility = '';
        }
        
        if (is_a($facility, 'Zend_Db_Table_Row_Abstract')) {
            $facility = get_class($facility) . ' ' . $facility->idname;
        } elseif (is_object($facility)) {
            $facility = get_class($facility);
        }
        
//        $d = [
//            'time' => new \Laminas\Db\Sql\Expression('NOW()'),
//            'facility' => $facility,
//            'message' => $message,
//            'severity' => $severity,
//            'type' => $type,
//        ];
//
//        if (is_string($refTable)) {
//            $d['refTable'] = $refTable;
//            $d['refId'] = is_numeric($refId) ? $refId : null;
//        } elseif (is_a($refTable, "\Laminas\Db\RowGateway\AbstractRowGateway")) {
//            /** @var \Laminas\Db\RowGateway\RowGateway $refTable */
//            $d['refTable'] = $refTable->getTable()->info(\Laminas\Db\TableGateway\TableGateway::NAME);
//            $m = \Laminas\Db\Metadata\Source\Factory::createSourceFromAdapter($db);
//            $m->getTable($tableName);
//            new \Laminas\Db\Sql\Sql($adapter);
//            $d['refId'] = ($refTable->id) ? $refTable->id : null;
//        } elseif (is_a($refTable, "\Laminas\Db\TableGateway\AbstractTableGateway")) {
//            /** @var \Laminas\Db\TableGateway\TableGateway $refTable */
//            $d['refTable'] = $refTable->getTable();
//            $d['refId'] = null;
//        } elseif (is_object($refTable)) {
//            /** @var object $refTable */
//            $d['refTable'] = get_class($refTable);
//            $d['refId'] = null;
//        }
        
        if (!empty($facility)) {
            $facility = $facility . ': ';
        }
        if (!empty($type)) {
            $facility = $type . '|' . $facility;
        }
        if (!empty($severity)) {
            $facility = $severity . '|' . $facility;
        }
        
        $m = $facility . $message;
        if (in_array($severity, $LogMsgLogSeverity)) {
            static::log_msg($m);
        }
        
//        if (in_array($severity, $dbLogSeverity)) {
//            try {
//                /** @var Zend_Db_Adapter_Abstract $db */
//                // $db=Bootstrap::$registry->database;
//                /** @var \Laminas\Db\Adapter\Adapter $db */
//                if ($db = static::getDb()) {
//                    $sql = new \Laminas\Db\Sql\Sql($db);
//                    $insert = $sql->insert(
//                        $config->get('db', new \Laminas\Config\Config([]))
//                            ->get('tablename', 'tLog')
//                    );
//                    $db->query($insert->getSqlString(), $db::QUERY_MODE_EXECUTE);
//                }
//            } catch (\Exception $e) {
//                static::log_msg('Can not log to database: ' . $e->getMessage());
//            }
//        }
        
        self::$iiterationscounter--;
        if (is_a($e, \Exception::class)) {
            return $e;
        }
        return $m;
    }
    
    
    
    /**
     * Can be used to log function entry.
     *
     * @param object $obj
     * @param string $severity
     *
     * @throws \Exception
     */
    public static function functionHead($obj = null, $severity = Severity::DEBUG)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $bt = $backtrace[1];
        
        if (!isset($bt['type'])) {
            $bt['type'] = '';
        }
        if (!isset($bt['class'])) {
            $bt['class'] = '';
        }
        
        $btArgs = [];
        foreach ($bt['args'] as $arg) {
            $btArgs[] = is_scalar($arg) ? $arg : gettype($arg);
        }
        
        static::addLog(
            '' .
            // . ( isset($bt['object']) ? get_class($bt['object']) : $bt['class'] )
            $bt['class'] . $bt['type'] . $bt['function'] . '(' . implode(
                ', ',
                $btArgs
            ) . ')  ' . (!isset($bt['file']) ? '' : 'called in ' . $bt['file'] . ':' . $bt['line']) . '  ',
            $severity,
            Type::STATUS,
            $obj
        );
    }
    
    
    
    /**
     * Get the config of the module.
     *
     * @return Config
     */
    public static function getConfig(): Config
    {
        return new Config(
            [
                'db' => [
                    'tablename' => 'tLog',
                    'log_severity' => [
                        Severity::EMERGENCY,
                        Severity::ALERT,
                        Severity::CRITICAL,
                        Severity::ERROR,
                        Severity::WARNING,
                        Severity::NOTICE,
                        Severity::INFORMATIONAL,
                        Severity::DEBUG,
                    ],
                ],
                'log_msg' => [
                    'log_severity' => [
                        Severity::EMERGENCY,
                        Severity::ALERT,
                        Severity::CRITICAL,
                        Severity::ERROR,
                        Severity::WARNING,
                        Severity::NOTICE,
                        Severity::INFORMATIONAL,
                        Severity::DEBUG,
                    ],
                ],
            ]
        );
    }
    
    
    
}
