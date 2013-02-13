<?php
// Set flag that this is a parent file
define('_JEXEC', 1);

ini_set('display_errors',1);
error_reporting(E_ALL);
ini_set('max_execution_time',0);

define('DS', DIRECTORY_SEPARATOR);
define('JPATH_BASE', dirname(__FILE__));

require_once(JPATH_BASE.DS.'includes'.DS.'defines.php');
require_once(JPATH_BASE.DS.'includes'.DS.'framework.php');

//jimport('joomla.database.table');
//jimport('joomla.plugin.helper');

$mainframe = &JFactory::getApplication('site');
//JPluginHelper::importPlugin('system');

$user = &JFactory::getUser();
/*
if($user->usertype != 'Super Administrator')
{
    echo 'You do not have access!';
    return;
}
*/
define('MY_JPATH_BASE', dirname(__FILE__) );
$start_time = $start_step_time = microtime(true);
$average_load_time = $average_save_time = 0.0;

require_once MY_JPATH_BASE . '/components/com_civicrm/civicrm.settings.php';
require_once MY_JPATH_BASE . '/components/com_civicrm/civicrm/api/v2/utils.php';

_civicrm_initialize();

require_once 'CRM/Contact/BAO/Contact.php';
require_once 'CRM/Core/BAO/CustomValueTable.php';

require_once 'api/v2/Contact.php';

$params = array('showAll' => 'all', 'rowCount' => 0, 'offset' => 0);

$contacts = civicrm_contact_get( $params );

print 'contacts=' . count($contacts). "\n";
$end_step_time = microtime(true);
print '<br />Load all contacts=' . ( $end_step_time - $start_step_time ). " sec.\n";;
$start_step_time = $end_step_time;

//print '<pre>' . print_r($contacts, true) . '</pre>';

$count = 0;

foreach($contacts as $contact_id=>$val)
{
    if(!isset($val['phone']))
    {
            $contact = array();
            $get_params = array('entityID' => $contact_id, 'custom_67' => 1 , 'custom_280' => 1);
            $values = CRM_Core_BAO_CustomValueTable::getValues($get_params);
//            print '<pre>' . print_r($values, true) . '</pre>';
$end_step_time = microtime(true);
$delta_time = $end_step_time - $start_step_time;
//print '<br />Load custom values=' . $delta_time;
$average_load_time = ($average_load_time > 0)?(($average_load_time+$delta_time)/2):$delta_time;
$start_step_time = $end_step_time;

            $new_phone = '';
            if(isset($values['custom_280']))
            {
                $new_phone = $values['custom_280'];
            }
            else if(isset($values['custom_67']))
            {
                $new_phone = $values['custom_67'];
            }

            if(!$values['is_error'] && $new_phone)
            {
                    $contact['phone'][1]['contact_id'] = $contact_id;
                    $contact['phone'][1]['phone'] = $new_phone;
                    $contact['phone'][1]['location_type_id'] = 1;
                    $contact['phone'][1]['phone_type_id'] = 1;
                    $contact['phone'][1]['is_primary'] = 1;

                    $location = array(	'version'    => '3.0',
                        'contact_id' => $contact_id,
                        'phone'		=> $contact['phone']
                    );
                    require_once 'api/v2/Location.php';
                    $newLocation =& civicrm_location_add($location);
                    $count++;
$end_step_time = microtime(true);
$delta_time = $end_step_time - $start_step_time;
//print '<br />Save new Location=' . $delta_time;
$average_save_time = ($average_save_time > 0)?(($average_save_time+$delta_time)/2):$delta_time;
$start_step_time = $end_step_time;
            }
    }
//    if($count > 5)
//    {
//        break;
//    }
}

print '<br />Updated=' . $count . "\n";
$end_step_time = microtime(true);
print '<br />All time of work=' . ( $end_step_time - $start_time ). " sec.\n";
print '<br />Load custom values (average)=' . $average_load_time. " sec.\n";
print '<br />Save new Location (average)=' . $average_save_time. " sec.\n";
