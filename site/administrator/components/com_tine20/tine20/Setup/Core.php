<?php
/**
 * Tine 2.0
 * 
 * @package     Setup
 * @subpackage  Server
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @version     $Id: Core.php 5153 2008-10-29 14:23:09Z p.schuele@metaways.de $
 *
 */

/**
 * dispatcher and initialisation class (functions are static)
 * - dispatchRequest() function
 * - initXYZ() functions 
 * - has registry and config
 * 
 * @package     Setup
 */
class Setup_Core extends Tinebase_Core
{
    /**
     * constant for config registry index
     *
     */
    const CHECKDB = 'checkDB';    

    /**
     * init setup framework
     */
    public static function initFramework()
    {
        Setup_Core::setupConfig();
        
        Setup_Core::setupTempDir();
                
        // Server Timezone must be setup before logger, as logger has timehandling!
        Setup_Core::setupServerTimezone();

        Setup_Core::setupLogger();

        //Database Connection must be setup before cache because setupCache uses constant "SQL_TABLE_PREFIX"
        Setup_Core::setupDatabaseConnection();

        Setup_Core::setupStreamWrapper();
        
        //Cache must be setup before User Locale because otherwise Zend_Locale tries to setup 
        //its own cache handler which might result in a open_basedir restriction depending on the php.ini settings 
        Setup_Core::setupCache();

        Setup_Core::setupSession();
        
        // setup a temporary user locale/timezone. This will be overwritten later but we 
        // need to handle exceptions during initialisation process such as seesion timeout
        Setup_Core::set('locale', new Zend_Locale('en_US'));
        Setup_Core::set('userTimeZone', 'UTC');
        
        Setup_Core::setupUserLocale();
        
        header('X-API: http://www.tine20.org/apidocs/tine20/');
    }
    
    /**
     * dispatch request
     *
     */
    public static function dispatchRequest()
    {
        // disable magic_quotes_runtime
        ini_set('magic_quotes_runtime', 0);
        
        // display errors we can't handle ourselves
        error_reporting(E_COMPILE_ERROR | E_CORE_ERROR | E_ERROR | E_PARSE);
        ini_set('display_errors', 1);
        
        ini_set('log_errors', 1);
        set_error_handler('Tinebase_Core::errorHandler', E_ALL);
        
        // set default internal encoding
        ini_set('iconv.internal_encoding', 'utf-8');
        
        $server = NULL;
        
        /**************************** JSON API *****************************/

        if ( (isset($_SERVER['HTTP_X_TINE20_REQUEST_TYPE']) && $_SERVER['HTTP_X_TINE20_REQUEST_TYPE'] == 'JSON')  || 
             (isset($_POST['requestType']) && $_POST['requestType'] == 'JSON')
           ) {
            $server = new Setup_Server_Json();

        /**************************** CLI API *****************************/
        
        } elseif (php_sapi_name() == 'cli') {
            $server = new Setup_Server_Cli();
            
        /**************************** HTTP API ****************************/
        
        } else {
            $server = new Setup_Server_Http();
        }        
        
        $server->handle();
    }
    
    /**
     * setups global config
     * 
     * NOTE a config object will be intanciated regardless of the existance of 
     *      the conffile!
     *
     * @return void
     */
    public static function setupConfig()
    {
        if(self::configFileExists()) {
            $config = new Zend_Config(require self::getConfigFilePath());
        } else {
            $config = new Zend_Config(array());
        }
        self::set(self::CONFIG, $config);  
    }
    
    /**
     * checks if global config file exists
     *
     * @return bool
     */
    public static function configFileExists()
    {
        return (bool)self::getConfigFilePath();
    }
    
    /**
     * Searches for config.inc.php in include paths and returnes the first match
     *
     * @return String
     */
    public static function getConfigFilePath()
    {
        $includePaths = explode(PATH_SEPARATOR, get_include_path());
        foreach ($includePaths as $includePath) {
            $path = $includePath . '/config.inc.php';
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * checks if global config file or tine root is writable
     *
     * @return bool
     */
    public static function configFileWritable()
    {        
        if (self::configFileExists()) {
            $configFilePath = self::getConfigFilePath();
            return is_writable($configFilePath);
        } else {
            $path = dirname(dirname(__FILE__));
            $testfilename = $path . DIRECTORY_SEPARATOR . uniqid(mt_rand()).'.tmp';
            if (!($f = @fopen($testfilename, 'w'))) {
                error_log(__METHOD__ . '::' . __LINE__ . ' Your tine root dir ' . $path . ' is not writable for the webserver! Config file can\'t be created.');
                return false;
            }
            fclose($f);
            unlink($testfilename);
            return true;
        }
    }
    
    /**
     * initializes the database connection
     *
     * @throws  Tinebase_Exception_UnexpectedValue
     * 
     * @todo try to write to db, if it fails: self::set(Setup_Core::CHECKDB, FALSE);
     */
    public static function setupDatabaseConnection()
    {
        self::set(Setup_Core::CHECKDB, FALSE);
        
        // check database first
        if (self::configFileExists()) {
            try {
                parent::setupDatabaseConnection();
                
                // check (mysql)db server version
                $ext = new Setup_ExtCheck(dirname(__FILE__) . '/essentials.xml');
                $dbConfig = Tinebase_Core::getConfig()->database;
                if ($dbConfig->adapter === 'pdo_mysql' && ($mysqlRequired = $ext->getExtensionData('MySQL'))) {
                    //Check if installed MySQL version is compatible with required version
                    $hostnameWithPort = (isset($dbConfig->port)) ? $dbConfig->host . ':' . $dbConfig->port : $dbConfig->host;
                    $link = @mysql_connect($hostnameWithPort, $dbConfig->username, $dbConfig->password);
                    if ($link) {
                        $serverVersion = @mysql_get_server_info();
                        if (version_compare($mysqlRequired['VERSION'], $serverVersion, '<')) {
                            self::set(Setup_Core::CHECKDB, TRUE);
                        } else {
                            Setup_Core::getLogger()->info(__METHOD__ . '::' . __LINE__ 
                                . ' MySQL server version incompatible! ' . $serverVersion
                                . ' < ' . $mysqlRequired['VERSION']
                            );
                        }
                    }
                } else {
                    //@todo check version requirements for other db adapters
                    self::set(Setup_Core::CHECKDB, TRUE);
                }
            } catch (Zend_Db_Adapter_Exception $zae) {
                Setup_Core::getLogger()->info(__METHOD__ . '::' . __LINE__ . ' ' . $zae->getMessage());
            }
        }
    }
    
    /**
     * setups the logger
     * 
     * NOTE: if no logger is configured, we write to stderr in setup
     *
     * @param $_defaultWriter Zend_Log_Writer_Abstract default log writer
     */
    public static function setupLogger(Zend_Log_Writer_Abstract $_defaultWriter = NULL)
    {
        $writer = new Zend_Log_Writer_Stream('php://stderr');
        parent::setupLogger($writer);
    }
    
    /**
     * initializes the session
     */
    public static function setupSession()
    {
        self::startSession(array(
            'name'              => 'TINE20SETUPSESSID',
        ), 'tinesetup');
        
    	if (isset(self::get(self::SESSION)->setupuser)) {
            self::set(self::USER, self::get(self::SESSION)->setupuser);
        }

        $config = self::getConfig();
        define('TINE20_BUILDTYPE', strtoupper($config->get('buildtype', 'RELEASE')));
        define('TINE20SETUP_CODENAME',      'Neele');
        define('TINE20SETUP_PACKAGESTRING', '2011-01-2');
        define('TINE20SETUP_RELEASETIME',   '2011-02-02 21:33:55+01:00');
    }
    
    /**
     * setup the cache and add it to zend registry
     * 
     * Ignores {@param $_enabled} and always sets it to false
     *
     */
    public static function setupCache($_enabled = true)
    {
        // disable caching for setup
        parent::setupCache(false);
    }
}
