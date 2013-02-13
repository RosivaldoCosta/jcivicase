<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 3.1                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2009                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007.                                       |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License along with this program; if not, contact CiviCRM LLC       |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 * Create a xml file for a set of contact ID's in a format digestible 
 * by Solr
 */

$pth = '../../../';

require_once $pth.'civicrm.config.php';
require_once 'CRM/Core/Config.php';
require_once 'solr.inc.php';
require_once 'solr.config.php';

define( 'CHUNK_SIZE', 128 );

global $debug;
global $noSOLR;
$debug=false;
$noSOLR=false;

$config =& CRM_Core_Config::singleton();
#$config->userFramework          = 'Soap';
#$config->userFrameworkClass     = 'CRM_Utils_System_Soap';
#$config->userHookClass          = 'CRM_Utils_Hook_Soap';

$sql = "SELECT id FROM civicrm_contact";

//take note of elapsed time
$timestart = microtime(1); // note 1 

$dao =& CRM_Core_DAO::executeQuery( $sql, CRM_Core_DAO::$_nullArray );

$contactIDs = array( );
while ( $dao->fetch( ) ) {
    $contactIDs[] = $dao->id;
}

if ($argv[3]=="debug") $debug=true;

if ($argv[4]=="nosolr") $noSOLR=true;

switch ($argv[1].$argv[2]) {

   case 'updatecontacts':
        updateSolrContacts( $contactIDs );
        break;
   case 'deletecontacts':
        deleteContactsFromSolr($contactIDs);
        break;

   default:
        echo "usage: php solrScript.php [update|delete] [contacts|cases|events] <debug> <nosolr>\n\ne.g.\nphp updateSolr.php update debug nosolr\nphp updateSolr.php update\n\ndebug outputs all xml to stdout.\n\nnosolr skips posting the xml to solr\n\n";
}

$elapsed_time = microtime(1)-$timestart; // note 2 

echo "elapsed time = $elapsed_time sec\n\n";

/**
 * Given an array of values, generate the XML in the Solr format
 */
function &generateSolrUpdateXML( $type, $values) {

    global $solrSourceID;

    $count=0;

    $doc = "<add>\n";

    foreach ( $values as $cid => $tokens ) {

        if ( empty( $tokens ) ) continue;

	++$count;

	$doc .= "<doc>\n";
	$doc .= "<field name=\"sourceID\">".htmlentities($solrSourceID)."</field>\n";
	$doc .= "<field name=\"docType\">".htmlentities($type)."</field>\n";

        foreach ( $tokens as $t ) {
            $doc .= "<field name=\"".htmlentities($t[0])."\">".htmlentities($t[1])."</field>\n";
        }

        $doc .= "  </doc>\n";
    }

    $doc .= "</add>\n";

    global $debug;
    if($debug) echo $doc;

    return $doc;
}

function &generateSolrDeleteXML( $type, $values ) {

    $count=0;

    $doc = "<delete>\n";

    foreach ( $values as $cid ) {

        ++$count;

        $doc .= "<query>docType:$type id:$cid</query>\n";
    }

    $doc .= "</delete>\n";
   
    global $debug;
    if($debug) echo $doc;

    return $doc;
}

function deleteContactsFromSolr(& $contactIDs) {

    $chunks =& splitContactIDs( $contactIDs );

    foreach ( $chunks as $chunk ) {
        $xml = & generateSolrDeleteXML( 'contact', $chunk );
        postToSolr($xml);
    }

    echo "total records deleted: ".sizeof($contactIDs).".\n\n";

}

function updateSolrContacts( & $contactIDs ) {
    
    $chunks =& splitContactIDs( $contactIDs );

    foreach ( $chunks as $chunk ) {
        $values = array( );
        getDetailedContactValues( $chunk, $values );
        $xml = & generateSolrUpdateXML('contact', $values );
	postToSolr($xml);
    }

    echo "total records updated: ".sizeof($contactIDs).".\n\n";

}

function postToSolr($data) {

	global $solrURL;
	global $noSOLR;

	if ($noSOLR) return;

        $ch = curl_init($solrURL);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt ($ch , CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
        curl_exec ($ch);
        curl_close ($ch);
}

?>
