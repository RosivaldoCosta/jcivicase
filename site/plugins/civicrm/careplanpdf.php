<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

include_once(JPATH_SITE.'/plugins/civicrm/activityPDF/helper.php');

class plgCivicrmCareplanpdf extends JPlugin
{
    /**
     Post Civicrm Plugin
     */

    public function civicrm_buildForm( $formName, &$form )
    {

	//echo '<pre>'; print_r($form); die;
	if (isset($form->_activityTypeId) && $form->_activityTypeId == 36 && $formName == 'CRM_Case_Form_Activity' && (CRM_Utils_Array::value( 'snippet', $_GET ) == 3 ))
	{
	    // PDF view rule
	    $form->assign( 'pdf', true );
	    $form->assign( 'aType', $form->_activityTypeId );

	    ActivityPDFHelper::buildForm_user_info($form);

	    $groupTree = array();
	    $groupTree =& CRM_Core_BAO_CustomGroup::getTree( 'Activity',
		    $form,
		    $form->_activityId,
		    null,
		    $form->_activityTypeId);

	    $groupTree = CRM_Core_BAO_CustomGroup::formatGroupTree( $groupTree, 1, $form );
	    CRM_Core_BAO_CustomGroup::buildQuickForm( $form, $groupTree, false);

	    $defaults = array( );
	    CRM_Core_BAO_CustomGroup::setDefaults( $groupTree, $defaults);

	    $groupTreeIdx = array();

	    self::civicrm_buildForm_customization( $form, $groupTreeIdx );

	    ActivityPDFHelper::buildForm_reorganization($form, $groupTree, $defaults, $groupTreeIdx);

	}
    }

    public function civicrm_buildForm_customization( &$form, &$groupTreeIdx )
    {
	$groupTreeIdx['Group1_head'] = array(
		'name' => 'Group1_head',
		'title' => 'Ops Care Plan form',
		'fields' => array()
	);
	$groupTreeIdx['Care_Plan_head']['fields'][] = array(
		'label' => 'Task/Form Type',
		'data_type' => 'String',
		'html_type' => 'Text',
		'element_name' => 'task_form_type',
		'element_value' => 'Care Plan'
	);
	$element = $form->getElement('source_contact_id');
	$groupTreeIdx['Care_Plan_head']['fields'][] = array(
		'label' => 'Reported By',
		'data_type' => 'String',
		'html_type' => 'Text',
		'element_name' => 'source_contact_id',
		'element_value' => $element->_attributes['value']
	);
	$element = $form->getElement('subject');
	$groupTreeIdx['Care_Plan_head']['fields'][] = array(
		'label' => 'Task Details',
		'data_type' => 'String',
		'html_type' => 'Text',
		'element_name' => 'task_details',
		'element_value' => $element->_attributes['value']
	);
	$element = $form->getElement('activity_date_time');
	$groupTreeIdx['Care_Plan_head']['fields'][] = array(
		'label' => 'Date',
		'data_type' => 'Date',
		'html_type' => 'Select date',
		'element_name' => 'activity_date_time',
		'element_value' => $element->_attributes['value']
	);
	$element = $form->getElement('activity_date_time_time');
	$groupTreeIdx['Care_Plan_head']['fields'][] = array(
		'label' => 'Time',
		'data_type' => 'String',
		'html_type' => 'Text',
		'element_name' => 'time',
		'element_value' => $element->_attributes['value']
	);
    }

}
