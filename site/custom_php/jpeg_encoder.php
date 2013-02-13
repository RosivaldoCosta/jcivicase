<?php
  // Set flag that this is a parent file

define( '_JEXEC', 1 );
define('JPATH_BASE', dirname(__FILE__).'/..' );
define( 'DS', DIRECTORY_SEPARATOR );
//ini_set('display_errors','On');
//error_reporting(E_ALL);


require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'user'.DS.'user.php' );
require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'methods.php' );

jimport('joomla.environment.uri');
jimport('joomla.database.database');
jimport('joomla.database.table' );
jimport('joomla.application.component.helper');
jimport('joomla.environment.uri' );
jimport('joomla.methods');
jimport('joomla.error.log');
$mainframe =& JFactory::getApplication('administrator');

$civicrm_root_api = JPATH_BASE . DS . 'administrator'.DS.'components'.DS.'com_civicrm'.DS.'civicrm';

require_once $civicrm_root_api. DS . '../civicrm.settings.php';
//require_once $civicrm_root_api. DS . 'CRM/Core/Config.php';
//require_once $civicrm_root_api. DS . 'api/api.php';

require_once $civicrm_root_api . DS .'api/v2/utils.php';
require_once $civicrm_root_api . DS .'api/v2/Activity.php';
require_once $civicrm_root_api . DS. 'api/v2/UFGroup.php';
require_once $civicrm_root_api . DS. 'api/Relationship.php';

// Prepare CiviCRM envirement
_civicrm_initialize( );
require_once $civicrm_root_api . DS.'api/v2/Case.php';
require_once $civicrm_root_api . DS.'CRM/Core/OptionGroup.php';
require_once $civicrm_root_api . DS .'api/File.php';


$basepath = JPATH_BASE.DS.'media'.DS.'civicrm'.DS.'custom';
$caseid = '';
$depart = '';
$cid = '';
$activityId = '';
$aType = '';
$stdin = file_get_contents('php://input');

if ( !empty($stdin)) {

	$t = date('l_jS_F_Y_h_i_s');
	if(array_key_exists('caseid', $_GET))
		$caseid = $_GET['caseid'];

	if(array_key_exists('cid', $_GET))
		$cid = $_GET['cid'];

	if(array_key_exists('id', $_GET))
		$activityId = $_GET['id'];

	if(array_key_exists('depart', $_GET))
		$depart = $_GET['depart'];

	if(array_key_exists('aType', $_GET))
		$aType = $_GET['aType'];

	$fileName = $caseid.'_'.$activityId.'_'.str_replace(" ","_",$t).'.jpg';
    	$imageHandle = fopen($basepath.DS.$fileName, "w");

	//$activity = add_case_activity($cid,$caseid);
	//print_r($activity);

	//if( array_key_exists('result',$activity))

    	fwrite($imageHandle, $stdin);

    	fclose($imageHandle);
	if( $activityId )
	{
		$file = add_file($aType,$fileName,$activityId);
		//update_activity($file, $fileName,$activityId);
		//print_r($file);
		//echo $fileName;
		//$log->addEntry(array('comment' => 'Signature Captured: '.$fileName));
	}

	
}  //$log->addEntry(array('comment' => 'Error'));


	function add_file($type, $url, $entityid)
	{

		$params = array(	'file_type_id'		=> 'NULL',
					'mime_type'		=> 'image/jpeg',
					'uri'			=> $url,
					'description'		=> $type.' Signature Capture',
					'upload_date'		=> date('Ymdhis'),
		);
	
		$file = crm_create_file($params);
		// $result = civicrm_api( 'file','create',$params );
		//civicrm_api_legacy('crm_create_file', 'File', $params);

		if(array_key_exists('id',$file))
		{

			$entityfile = crm_create_entity_file($file['id'],$entityid,'civicrm_activity');
			//print_r($entityfile);
		}

		return $file;
	}


	function add_case_activity($cId, $caseId)
        {
		$user 	= &JFactory::getUser();

                $params = array(        'case_id'               => $caseId,
                                        'activity_type_id'      => 96, //CRM_Core_OptionGroup::getValue( 'activity_type', 'Signature Capture', 'label' ), // activity type IHIT Visit
                                        'source_contact_id'     => $cId,
                                        'status_id'             => 2,
                                        'medium_id'             => '1',
                                        'subject'               => $_GET['aType'].' Signature Capture ',
					'custom_804'		=> $imageFile
                      );

        	$new_case = civicrm_case_activity_create( $params ); // returns case id as $new_case['result']

		return $new_case;

        }

