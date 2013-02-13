<?php
/**
 * Helper class for /joomla/factory.php
 *
 * @package Joomla
 * @subpackage UnitTest
 * @version    $Id: JFactory_helper.php 9743 2007-12-24 04:18:48Z instance $
 */

/* class to test */
//require_once 'libraries/joomla/factory.php';
//require_once 'libraries/joomla/registry/registry.php';

class JFactoryTestHelper
{

	function tearDownTestCase() {
		
	}

	/**
	 * Helper used with TestOfJFactory::testGetMailer_smpt() to
	 * provide the required credentials for the SMTP mailer.
	 */
	function GetMailer_smtp($auth = false)
	{
		$config =& JFactory::getConfig();
		$config->setValue('config.mailer', 'smtp');

		// get credentials form TestConfiguration.php
//		$cfg = ($auth == false)
//			 ? JUnit_Setup::makeCfg(JUT_JFACTORY_SMTP_NOAUTH, false, true)
//			 : JUnit_Setup::makeCfg(JUT_JFACTORY_SMTP_AUTH,   false, true);

		$config->setValue('config.smtpuser', $cfg['user']);
		$config->setValue('config.smtppass', $cfg['pass']);
		$config->setValue('config.smtphost', $cfg['host']);

		if ($auth) $config->setValue('config.smtpauth', $cfg['smtpauth']);

	   }

	/**
	 * Helper used with TestOfJFactory::testGetDBO() to provide
	 * the required credentials for a database connection.
	 */
	function GetDBO()
	{
		$config =& JFactory::getConfig();
		$config = JUnit_Setup::makeCfg(JUNIT_DATABASE_MYSQL4, true);

//        SimpleTest::getContext()
//                    ->getReporter()
//                    ->dumpCode(JFactory::getConfig() , __FUNCTION__);

	}

}

class JFactoryTestObserver
{
	/* called from Simpletest */
	function atTestEnd($method, &$test_case) {
//        $context  = &SimpleTest::getContext();
//        $reporter = &$context->getReporter();
	}

}

