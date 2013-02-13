 <?php
/**
 * Tine 2.0
 * @package     Tinebase
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Cli.php 18405 2011-01-07 12:33:06Z c.weiss@metaways.de $
 */

/**
 * cli server
 *
 * This class handles all requests from cli scripts
 *
 * @package     Tinebase
 */
class Tinebase_Frontend_Cli extends Tinebase_Frontend_Cli_Abstract
{
    /**
     * the internal name of the application
     *
     * @var string
     */
    protected $_applicationName = 'Tinebase';

    /**
     * authentication
     *
     * @param string $_username
     * @param string $_password
     */
    public function authenticate($_username, $_password)
    {
        $authResult = Tinebase_Auth::getInstance()->authenticate($_username, $_password);
        
        if ($authResult->isValid()) {
            $accountsController = Tinebase_User::getInstance();
            try {
                $account = $accountsController->getFullUserByLoginName($authResult->getIdentity());
            } catch (Tinebase_Exception_NotFound $e) {
                echo 'account ' . $authResult->getIdentity() . ' not found in account storage'."\n";
                exit();
            }
            
            Tinebase_Core::set('currentAccount', $account);

            $ipAddress = '127.0.0.1';
            $account->setLoginTime($ipAddress);

            Tinebase_AccessLog::getInstance()->create(new Tinebase_Model_AccessLog(array(
                'sessionid'     => 'cli call',
                'login_name'    => $authResult->getIdentity(),
                'ip'            => $ipAddress,
                'li'            => Tinebase_DateTime::now()->get(Tinebase_Record_Abstract::ISO8601LONG),
                'lo'            => Tinebase_DateTime::now()->get(Tinebase_Record_Abstract::ISO8601LONG),
                'result'        => $authResult->getCode(),
                'account_id'    => Tinebase_Core::getUser()->getId(),
                'clienttype'    => 'TineCli',
            )));
            
        } else {
            echo "Wrong username and/or password.\n";
            exit();            
        }
    }
    
    /**
     * handle request (call -ApplicationName-_Cli.-MethodName- or -ApplicationName-_Cli.getHelp)
     *
     * @param Zend_Console_Getopt $_opts
     * @return boolean success
     */
    public function handle($_opts)
    {
        list($application, $method) = explode('.', $_opts->method);
        $class = $application . '_Frontend_Cli';
        
        if (@class_exists($class)) {
            $object = new $class;
            if ($_opts->info) {
                $result = $object->getHelp();
            } else {
                $result = call_user_func(array($object, $method), $_opts);
            }
        } else {
            echo "Class $class does not exist.\n";
            $result = FALSE;
        }
        
        return $result;
    }

    /**
     * trigger async events (for example via cronjob)
     *
     * @param Zend_Console_Getopt $_opts
     * @return boolean success
     */
    public function triggerAsyncEvents($_opts)
    {
        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .' Triggering async events from CLI.');
        
        try {
            $cronuser = Tinebase_User::getInstance()->getFullUserByLoginName($_opts->username);
        } catch (Tinebase_Exception_NotFound $tenf) {
            // get user for cronjob from config / set default admin group
            $cronuserId = Tinebase_Config::getInstance()->getConfig(Tinebase_Model_Config::CRONUSERID)->value;
            
            if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .' Setting user with id ' . $cronuserId . ' as cronuser.');
            $cronuser = Tinebase_User::getInstance()->getFullUserById($cronuserId);
        }
        Tinebase_Core::set(Tinebase_Core::USER, $cronuser);
        
        $scheduler = Tinebase_Core::getScheduler();
        $scheduler->run();
        
        return TRUE;
    }
    
    /**
     * process queue tasks (simple queue processing, intendet to be executed from system cron job)
     *  optional --numtasks param
     *
     * @param Zend_Console_Getopt $_opts
     * @return boolean success
     */
    public function processQueue($_opts)
    {
        if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .' Start a worker for action queye from CLI.');
        
        try {
            $cronuser = Tinebase_User::getInstance()->getFullUserByLoginName($_opts->username);
        } catch (Tinebase_Exception_NotFound $tenf) {
            // get user for cronjob from config / set default admin group
            $cronuserId = Tinebase_Config::getInstance()->getConfig(Tinebase_Model_Config::CRONUSERID)->value;
            
            if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ .' Setting user with id ' . $cronuserId . ' (cronuser) as user.');
            $cronuser = Tinebase_User::getInstance()->getFullUserById($cronuserId);
        }
        Tinebase_Core::set(Tinebase_Core::USER, $cronuser);
        
        $actionQueue = Tinebase_ActionQueue::getInstance();
        $r = $_opts->numtasks ? $actionQueue->processQueue($_opts->numtasks) : $actionQueue->processQueue();
        
        return TRUE;
    }
    
    /**
     * clear table as defined in arguments
     * can clear the following tables:
     * - credential_cache
     * - access_log
     * - async_job
     * 
     * if param date is given (date=2010-09-17), all records before this date are deleted (if the table has a date field)
     * 
     * @param $_opts
     * @return boolean success
     */
    public function clearTable(Zend_Console_Getopt $_opts)
    {
        if (! $this->_checkAdminRight()) {
            return FALSE;
        }
        
        $args = $this->_parseArgs($_opts, array('tables'), 'tables'); 

        $db = Tinebase_Core::getDb();
        foreach ($args['tables'] as $table) {
            switch ($table) {
                case 'access_log':
                    if (array_key_exists('date', $args)) {
                        echo "\nRemoving all access log entries before {$args['date']} ...";
                        $where = array(
                            $db->quoteInto($db->quoteIdentifier('li') . ' < ?', $args['date'])
                        );
                        $db->delete(SQL_TABLE_PREFIX . $table, $where);
                    } else {
                        $db->query('TRUNCATE ' . SQL_TABLE_PREFIX . $table);
                    }
                    break;
                case 'async_job':
                    $db->query(
                        'delete FROM ' . SQL_TABLE_PREFIX . 'async_job' .
                        " WHERE status='success'");
                    break;
                case 'credential_cache':
                    if (Setup_Controller::getInstance()->isInstalled('Felamimail')) {
                        // delete only records that are not related to email accounts
                        $db->query(
                            'delete ' . SQL_TABLE_PREFIX . 'credential_cache FROM `' . SQL_TABLE_PREFIX . 'credential_cache`' .
                            ' LEFT JOIN ' . SQL_TABLE_PREFIX . 'felamimail_account ON ' . SQL_TABLE_PREFIX . 'credential_cache.id = ' . 
                                SQL_TABLE_PREFIX . 'felamimail_account.credentials_id' .
                            ' WHERE ' . SQL_TABLE_PREFIX . 'felamimail_account.credentials_id IS NULL');
                        break;
                    } else {
                        // fallthrough
                    }                    
                default:
                    echo 'Table ' . $table . " not supported or argument missing.\n";
            }
            echo "\nCleared table $table.";
        }
        echo "\n\n";
        
        return TRUE;
    }
    
    /**
     * purge deleted records
     * 
     * if param date is given (for example: date=2010-09-17), all records before this date are deleted (if the table has a date field)
     * if table names are given, purge only records from this tables
     * 
     * @param $_opts
     * @return boolean success
     */
    public function purgeDeletedRecords(Zend_Console_Getopt $_opts)
    {
        if (! $this->_checkAdminRight()) {
            return FALSE;
        }

        $args = $this->_parseArgs($_opts, array(), 'tables'); 

        if (! array_key_exists('tables', $args) || empty($args['tables'])) {
            echo "No tables given.\nPurging records from all tables!\n";
            $args['tables'] = $this->_getAllApplicationTables();
        }
        
        $db = Tinebase_Core::getDb();
        
        if (array_key_exists('date', $args)) {
            echo "\nRemoving all deleted entries before {$args['date']} ...";
            $where = array(
                $db->quoteInto($db->quoteIdentifier('deleted_time') . ' < ?', $args['date'])
            );
        } else {
            echo "\nRemoving all deleted entries ...";
            $where = array();
        }
        $where[] = $db->quoteInto($db->quoteIdentifier('is_deleted') . ' = ?', 1);
    
        foreach ($args['tables'] as $table) {
            $schema = $db->describeTable(SQL_TABLE_PREFIX . $table);
            if (! array_key_exists('is_deleted', $schema)) {
                continue;
            }
            
            try {
                $deleteCount = $db->delete(SQL_TABLE_PREFIX . $table, $where);
            } catch (Zend_Db_Statement_Exception $zdse) {
                // try again with foreign key checks off
                echo "\nTurning off foreign key checks for table $table.";
                $db->query("SET FOREIGN_KEY_CHECKS=0");
                $deleteCount = $db->delete(SQL_TABLE_PREFIX . $table, $where);
                $db->query("SET FOREIGN_KEY_CHECKS=1");
            }
            if ($deleteCount > 0) {
                echo "\nCleared table $table (deleted $deleteCount records).";
            }
        }
        echo "\n\n";
        
        return TRUE;
    }
    
    /**
     * get all app tables
     * 
     * @return array
     */
    protected function _getAllApplicationTables()
    {
        $result = array();
        
        $installedApplications = Tinebase_Application::getInstance()->getApplications(NULL, 'id');
        foreach ($installedApplications as $application) {
            $result = array_merge($result, Tinebase_Application::getInstance()->getApplicationTables($application));
        }
        
        return $result;
    }
    
    /**
     * add new customfield config
     * 
     * needs args like this:
     * application="Addressbook" name="datefield" label="Date" model="Addressbook_Model_Contact" type="datefield"
     * @see Tinebase_Model_CustomField_Config for full list 
     * 
     * @param $_opts
     * @return boolean success
     */
    public function addCustomfield(Zend_Console_Getopt $_opts)
    {
        if (! $this->_checkAdminRight()) {
            return FALSE;
        }
        
        // parse args
        $args = $_opts->getRemainingArgs();
        $data = array();
        foreach ($args as $idx => $arg) {
            list($key, $value) = explode('=', $arg);
            if ($key == 'application') {
                $key = 'application_id';
                $value = Tinebase_Application::getInstance()->getApplicationByName($value)->getId();
            }
            $data[$key] = $value;
        }
        
        $customfieldConfig = new Tinebase_Model_CustomField_Config($data);
        $cf = Tinebase_CustomField::getInstance()->addCustomField($customfieldConfig);

        echo "\nCreated customfield: ";
        print_r($cf->toArray());
        echo "\n";
        
        return TRUE;
    }
}
