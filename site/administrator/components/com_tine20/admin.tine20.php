<?php

/**
* @version		$Id: admin.content.php 10381 2008-06-01 03:35:53Z pasamio $
* @package		Joomla
* @subpackage	Content
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * magic_quotes_gpc Hack!!!
 * @author Florian Blasel
 * 
 * If you are on a shared host you may not able to change the php setting for magic_quotes_gpc
 * this hack will solve this BUT this takes performance (speed)!
 */
/*
if (ini_get('magic_quotes_gpc')) {
    function __magic_quotes_gpc($requests) {
        foreach($requests AS $k=>&$v) {
            if (is_array($v)) {
                $requests[stripslashes($k)] = __magic_quotes_gpc($v);
            } else {
                $requests[stripslashes($k)] = stripslashes($v);
            }
        }
        return $requests;
    } 
    
    // Change the incomming data if needed
    $_GET = __magic_quotes_gpc( $_GET );
    $_POST = __magic_quotes_gpc( $_POST );
    $_COOKIE = __magic_quotes_gpc( $_COOKIE );
    $_ENV = __magic_quotes_gpc( $_ENV );
    $_REQUEST = __magic_quotes_gpc( $_REQUEST );
} // end magic_quotes_gpc Hack
*/

$time_start = microtime(true);

$paths = array(
    realpath(dirname(__FILE__).'/tine20'),
    realpath(dirname(__FILE__) . '/tine20/library'),
    get_include_path()
);
echo "here";
print_r($paths);
set_include_path(implode(PATH_SEPARATOR, $paths));

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);
Tinebase_Autoloader::initialize($autoloader);

Tinebase_Core::dispatchRequest();

// log profiling information
$time_end = microtime(true);
$time = $time_end - $time_start;

if(function_exists('memory_get_peak_usage')) {
    $memory = memory_get_peak_usage(true);
} else {
    $memory = memory_get_usage(true);
}

if(function_exists('realpath_cache_size')) {
    $realPathCacheSize = realpath_cache_size();
} else {
    $realPathCacheSize = 'unknown';
}

Tinebase_Core::getLogger()->info('index.php ('. __LINE__ . ') TIME: ' . $time . ' seconds  MEMORY: ' . $memory/1024/1024 . ' MBytes  REALPATHCACHESIZE: ' . $realPathCacheSize);
?>
