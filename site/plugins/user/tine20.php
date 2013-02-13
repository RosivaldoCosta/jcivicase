<?php
/**
* @version		$Id: ldap.php 10709 2008-08-21 09:58:52Z eddieajau $
* @package		Joomla
* @subpackage	JFramework
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
ini_set('display_errors','On');
error_reporting(E_ALL);


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

require_once(JPATH_SITE .DS.'libraries'.DS.'loader.php');
spl_autoload_register('__autoload');
set_include_path('.' . PATH_SEPARATOR . dirname(__FILE__) . '/../../administrator/components/com_tine20/tine20/library/' . PATH_SEPARATOR . get_include_path());
set_include_path('.' . PATH_SEPARATOR . dirname(__FILE__) . '/../../administrator/components/com_tine20/tine20/' . PATH_SEPARATOR . get_include_path());
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

/**
 * Tine20 Authentication Plugin
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 1.5
 */

class plgUserTine20 extends JPlugin
{
	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param 	object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	function plgUserTine20(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * This method should handle any authentication and report back to the subject
	 *
	 * @access	public
	 * @param   array 	$credentials Array holding the user credentials
	 * @param 	array   $options     Array of extra options
	 * @param	object	$response	Authentication response object
	 * @return	object	boolean
	 * @since 1.5
	 */
	function onLoginUser( $user, $options )
	{
		$result = $this->sso($user['username'], 
			$this->params->get('remote_addr'),
			$this->params->get('agent'),
			$this->params->get('sso_url'),
			$this->params->get('sso_version'),
			$this->params->get('sso_action'),
			$this->params->get('sso_userdata'),
			$user['password']
				 );	
		
		print_r($result);

		return true;
	}


function sso($User_Name,$remote_addr,$agent,$sso_url,$sso_version="",$sso_action="",$sso_userdata="", $password=null) {
		//do basic check
			if ($sso_version == "") return array("Error"=>"sso version out of date");
		//start init
			$this->ssoInit();
		//check wether account exists	
			/*$accountsController = Tinebase_User::getInstance();
			try {
				$account     = $accountsController->getFullUserByLoginName($User_Name);
				$userExists = true;
			} catch (Tinebase_Exception_NotFound $e) {
				$userExists = false;
			}*/
		//unpack userdata
			//$sso_userdata   = $this->process_userdata($sso_userdata);
			//$name           = explode(' ',$sso_userdata['name']);
		// decide sso action
					//authenticate
					
					/*try {
					$authResult = Tinebase_Auth::getInstance()->authenticate($User_Name, $password);
					return $authResult;
					} catch (Zend_Session_Exception $e) {
						echo "exception";
						return false;
					}*/
	

               				//sync user with backend
                      			$accountsController = Tinebase_User::getInstance();
                    			$groupsController   = Tinebase_Group::getInstance();


                  			$accountName = $User_Name;

                  			try {
                         			if ($accountsController instanceof Tinebase_User_Interface_SyncAble) {
                             				Tinebase_User::syncUser($accountName);
                         			}

                         			$user = $accountsController->getFullUserByLoginName($accountName);
                     			} catch (Tinebase_Exception_NotFound $e) {
                         			if (Tinebase_Core::isLogLevel(Zend_Log::CRIT)) Tinebase_Core::getLogger()->crit(__METHOD__ . '::' . __LINE__ . 'Account ' . $accountName . ' not found in account storage.');
                         			$accessLog->result = Tinebase_Auth::FAILURE_IDENTITY_NOT_FOUND;
                     			}

                  			$accessLog = new Tinebase_Model_AccessLog(array(
                       				'sessionid'     => session_id(),
                     				'ip'            => $_SERVER["REMOTE_ADDR"],
                     				'login_name'    => $User_Name,
                     				'li'            => Tinebase_DateTime::now()->get(Tinebase_Record_Abstract::ISO8601LONG),
                     				'result'        => Tinebase_Auth::SUCCESS,
                     				'account_id'    => $user->getId(),
                     				'clienttype'    => 'TineJson',   
                    				),TRUE);

					
					Zend_Session::registerValidator(new Zend_Session_Validator_HttpUserAgent());
					Zend_Session::registerValidator(new Zend_Session_Validator_IpAddress());
					Zend_Session::regenerateId();
					Tinebase_Core::getSession()->currentAccount = $user;

					//store user in session
					Tinebase_Core::set(Tinebase_Core::USER, $user);

					//create credentialcache
					$credentialCache = Tinebase_Auth_CredentialCache::getInstance()->cacheCredentials($User_Name, md5($User_Name));
					Tinebase_Core::set(Tinebase_Core::USERCREDENTIALCACHE, $credentialCache);
					//create login log
					

               				//create login log
                  			$user->setLoginTime($_SERVER["REMOTE_ADDR"]);

					$accessLog->sessionid = session_id();
            				$accessLog->login_name = $accountName;
            				$accessLog->account_id = $user->getId();

                    			Tinebase_AccessLog::getInstance()->create($accessLog);
                    
                  			$_SESSION['Zend_Auth']['storage'] = $User_Name;

               				//create credentialcache
                  			$cacheId = Tinebase_Core::get(Tinebase_Core::USERCREDENTIALCACHE)->getCacheId();
                      			setcookie('TINE20SESSID',        session_id()                              );
                  			setcookie('usercredentialcache', base64_encode(Zend_Json::encode($cacheId)));
                    			Tinebase_Core::getLogger()->debug(__METHOD__ . '::' . __LINE__ . ' Finished creating session with session_id'.session_id());

					//return auth result			
					$return_val = array();
					$return_val[0] = array();
					$return_val += array( "redirecturl" => $sso_url);
					// pass session data to the SSO-Agent
					//print_r($_SESSION);
					#die();
					return $return_val;
}

function ssoInit() {
	if(!defined('ssoInit')) {
		//assure, that it's only executed once
			define('ssoInit',1);

		//do basic tine20 initiation
		// disable magic_quotes_runtime
	        ini_set('magic_quotes_runtime', 0);

		// display errors we can't handle ourselves
	        error_reporting(E_COMPILE_ERROR | E_CORE_ERROR | E_ERROR | E_PARSE);
	        ini_set('display_errors', 1);
	        ini_set('log_errors', 1);
	        set_error_handler('Tinebase_Core::errorHandler', E_ALL);

	    // set default internal encoding
	        ini_set('iconv.internal_encoding', 'utf-8');
		//setting up the usersession
			Tinebase_Core::setupConfig();
	        Tinebase_Core::setupTempDir();
	    // Server Timezone must be setup before logger, as logger has timehandling!
	        Tinebase_Core::setupServerTimezone();
	        Tinebase_Core::setupLogger();
	    // Database Connection must be setup before cache because setupCache uses constant "SQL_TABLE_PREFIX" 
	        Tinebase_Core::setupDatabaseConnection();
	    //Cache must be setup before User Locale because otherwise Zend_Locale tries to setup 
	    //its own cache handler which might result in a open_basedir restriction depending on the php.ini settings
	        Tinebase_Core::setupCache();
	        //Tinebase_Core::setupSession();
	    // setup a temporary user locale/timezone. This will be overwritten later but we 
	    // need to handle exceptions during initialisation process such as seesion timeout
	    // @todo add fallback locale to config file
	        Tinebase_Core::set('locale', new Zend_Locale('en_US'));
	        Tinebase_Core::set('userTimeZone', 'UTC');
	        Tinebase_Core::setupMailer();
	        Tinebase_Core::setupUserCredentialCache();
	        Tinebase_Core::setupUserTimezone();
	        Tinebase_Core::setupUserLocale();
	}
}

/*
 * return the protocol version
 */
	function get_version(){
		return "2.0";
	}


/* 
 * process the userdata string and return an associative array
 * 
 * @param string $sso_userdata: the data from fe_users (pipe-separated)
 * @return array	$data: the userdata
 */
	function process_userdata($sso_userdata){
		$sso_userdata = split("\|",$sso_userdata);
		for ($i=0;$i<count($sso_userdata);$i++) {
			$sso_userdata[$i]=split("=",$sso_userdata[$i]);
			$data[$sso_userdata[$i][0]]=$sso_userdata[$i][1];
		}
		unset ($sso_userdata);
		return $data;
	}

	
}
