<?php

define('CIVICRM_UF', 'UnitTests');

define('JPATH_BASE', '/Users/charles/sites/santeemrv2/trunk/' );
define( 'DS', DIRECTORY_SEPARATOR );

define( '_JEXEC', 1 );

//require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
//require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

if ( !empty( $GLOBALS['mysql_user'] ) ) {
    define( 'CIVICRM_DSN'          , "mysql://cc5tudio_pgcrs:23wesdxc@#WESDXC@127.0.0.1/cc5tudio_pgcrs?new_link=true" );
}

global $civicrm_root;

$civicrm_root = dirname( dirname( dirname( dirname( __FILE__ ) ) ) );

//echo "using root: $civicrm_root\n";

// ADJUST THIS PATH!
define( 'CIVICRM_TEMPLATE_COMPILEDIR', JPATH_BASE . '/media/civicrm/templates_c/' );

define( 'CIVICRM_SITE_KEY', 'phpunittestfakekey' );

/**
 * 
 * Do not change anything below this line. Keep as is
 *
 */

$joomla_path = '/administrator/components/com_civicrm/civicrm';
$joomlatests = 'tests/phpunit/functionaltest/selenium';

$include_path = '.'        . PATH_SEPARATOR .
                $civicrm_root . PATH_SEPARATOR . 
                $civicrm_root.'/administrator/components/com_civicrm/civicrm' . PATH_SEPARATOR . 
                $civicrm_root. DIRECTORY_SEPARATOR . $joomlatests . PATH_SEPARATOR . 
                $civicrm_root . $joomla_path. DIRECTORY_SEPARATOR . 'packages' . PATH_SEPARATOR .
                get_include_path( );
set_include_path( $include_path );

//echo "using path:  $include_path\n";

if ( function_exists( 'variable_get' ) && variable_get('clean_url', '0') != '0' ) {
    define( 'CIVICRM_CLEANURL', 1 );
} else {
    define( 'CIVICRM_CLEANURL', 0 );
}

// force PHP to auto-detect Mac line endings
ini_set('auto_detect_line_endings', '1');

// make sure the memory_limit is at least 64 MB
$memLimitString = trim(ini_get('memory_limit'));
$memLimitUnit   = strtolower(substr($memLimitString, -1));
$memLimit       = (int) $memLimitString;
switch ($memLimitUnit) {
    case 'g': $memLimit *= 1024;
    case 'm': $memLimit *= 1024;
    case 'k': $memLimit *= 1024;
}
if ($memLimit >= 0 and $memLimit < 67108864) {
    ini_set('memory_limit', '64M');
}

