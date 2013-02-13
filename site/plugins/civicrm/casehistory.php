<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
require_once('CRM/Activity/BAO/Activity.php');

class plgCivicrmCaseHistory extends JPlugin
{
    /**
     *	Post Civicrm Plugin
     */

    public function civicrm_caseHistory( &$activities )
    {
	foreach($activities as $key =>$act)
	{

	    if($act['type'] == 'Physician Appointment')
	    {
		$activities[$key]['class'] = $act['class'].' physician-appointment';   //' bg_blue';
	    } elseif($act['type'] == 'Therapist Appointment')
	    {
		$activities[$key]['class'] = $act['class'].' therapist-appointment'; //." bg_red";
	    } elseif($act['type'] == 'IHIT Visit')
	    {
		$activities[$key]['class'] = $act['class'].' ihit-visit'; // " bg_green";
	    } elseif($act['type'] == 'Change Case Status')
	    {
		$activities[$key]['class'] = $act['class'].' change-case-status'; // " bg_grey";
	    }
	}
    }

}
