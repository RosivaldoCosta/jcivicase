<?php
/**
 * Tine 2.0
 * 
 * @package     Tinebase
 * @subpackage  Server
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @copyright   Copyright (c) 2007-2010 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @version     $Id: Helper.php 18148 2010-12-28 15:37:45Z p.schuele@metaways.de $
 */


/**
 * returns one value of an array, indentified by its key
 *
 * @param mixed $_key
 * @param array $_array
 * @return mixed
 */
function array_value($_key, array $_array)
{
    return array_key_exists($_key, $_array) ? $_array[$_key] : NULL;
}

/**
 * converts string with M or K to bytes integer
 * - for example: 50M -> 52428800
 *
 * @param mixed $_value
 * @return integer
 */
function convertToBytes($_value)
{
    if (is_int($_value) || ! $_value) {
        $bytes = $_value;
    } else {
        if (preg_match("/M/", $_value)) {
            $value = substr($_value, 0, strpos($_value, 'M'));
            $factor = 1024 * 1024;   
        } elseif (preg_match("/K/", $_value)) {
            $value = substr($_value, 0, strpos($_value, 'K'));
            $factor = 1024;
        } elseif (is_string($_value)) {
            $value = $_value;
            $factor = 1;
        } else {
            throw new Exception('Argument type not supported:' . gettype($_value));
        }
        $bytes = intval($value) * $factor;  
    }
    
    return $bytes;
}

/**
 * converts value to megabytes
 * 
 * @param integer $_value
 * @return integer
 */
function convertToMegabytes($_value)
{
    $result = ($_value) ? round($_value / 1024 / 1024) : $_value;
    return $result;
}

/**
 * get svn revision info
 *
 * @return string
 */
function getDevelopmentRevision()
{
    $branch = '';
    $rev = 0;
    $date = '';
    
    try {
        $file = @fopen(dirname(dirname(__FILE__)) . '/.svn/entries', 'r');
        while ($line = @fgets($file)) {
            
            if ((int)$line > 4700) {
                $rev = (int)$line;
            }
            if (preg_match('/^\d{4}-\d{2}-\d{2}[T ]+\d{2}:\d{2}:\d{2}/', $line)) {
                $date = trim($line);
            }
            
            if (empty($branch) && preg_match('/svn\.tine20\.org\/svn/', $line)) {
                $parts = explode('/', $line);
                $branch = $parts[count($parts)-2];
            }
            if (! empty($branch) && ! empty($date) && $line) {
                break;
            }
        }
        
        $revision = "$branch: $rev ($date)";
        @fclose($file);
    } catch (Exception $e) {
        $revision = 'not resolvable';
    }
    
    return $revision;
}

/**
 * converts cache id
 * cache id strings can only contain the chars [a-zA-Z0-9_]
 * 
 * @param string $_cacheId
 * @return string
 */
function convertCacheId($_cacheId) 
{
    $result = preg_replace('/[^a-z^A-Z^0-9^_]/', '', $_cacheId);

    return $result;
}

/**
 * recursive deleting of directory and all containing files
 * 
 * @param string $_dir
 * @return void
 */
function removeDir($_dir)
{
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($_dir), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($iterator as $fullFilename => $cur) {
        if (is_dir($fullFilename) && substr($fullFilename, -1) !== '.') {
            rmdir($fullFilename);
        } elseif (is_file($fullFilename)) {
            unlink($fullFilename);
        }
    }
    if (is_dir($_dir)) rmdir($_dir);
}

/**
 * replaces and/or strips special chars from given string
 *
 * @param string $_input
 * @return string
 */
function replaceSpecialChars($_input)
{
    $search  = array('ä',  'ü',  'ö',  'ß',  'é', 'è', 'ê', 'ó' ,'ô', 'á', 'ź', 'Ä',  'Ü',  'Ö',  'É', 'È', 'Ê', 'Ó' ,'Ô', 'Á', 'Ź'); 
    $replace = array('ae', 'ue', 'oe', 'ss', 'e', 'e', 'e', 'o', 'o', 'a', 'z', 'Ae', 'Ue', 'Oe', 'E', 'E', 'E', 'O', 'O', 'a', 'z');
                
    $output = str_replace($search, $replace, $_input);
    
    return preg_replace('/[^a-zA-Z0-9._\-]/', '', $output);
}
