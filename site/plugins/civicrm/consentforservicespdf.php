<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

include_once(JPATH_SITE.'/plugins/civicrm/activityPDF/helper.php');

class plgCivicrmConsentforservicespdf extends JPlugin
{

    public function civicrm_buildForm( $formName, &$form )
    {

//echo '<pre>'; print_r($form); die;
	if (isset($form->_activityTypeId) && $form->_activityTypeId == 39 && $formName == 'CRM_Case_Form_Activity' && (CRM_Utils_Array::value( 'snippet', $_GET ) == 3 ))
	{
	    // PDF view rule
	    $form->assign( 'pdf', true );
	    $pdf_template = $this->params->get('pdf_template');
	    if($pdf_template != '')
	    {
		$form->assign( 'pdf_template', $pdf_template );
	    }
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

	    ActivityPDFHelper::civicrm_buildForm_activityTitle( $form, $groupTreeIdx );


	    ActivityPDFHelper::buildForm_reorganization($form, $groupTree, $defaults, $groupTreeIdx);
	    ActivityPDFHelper::buildForm_signature_images($form, $groupTree, $defaults, $groupTreeIdx);

	    self::civicrm_buildForm_customization( $form, $groupTreeIdx );
	}


    }

    public function civicrm_buildForm_customization( &$form, &$groupTreeIdx )
    {
//echo '<pre>'; print_r($form); die;
//echo '<pre>' . print_r($form->embedObjectData,true) . '</pre>';
//echo '<pre>' . print_r($form->embedAttachmentTags,true) . '</pre>';
        ActivityPDFHelper::buildForm_signature_add('Consent_For_Services_Details',false,'client',$form, $groupTreeIdx);
        ActivityPDFHelper::buildForm_signature_add('Consent_For_Services_Details',false,'staff',$form, $groupTreeIdx);
        ActivityPDFHelper::buildForm_signature_add('Consent_For_Services_Details',false,'witness',$form, $groupTreeIdx);
        ActivityPDFHelper::buildForm_signature_add('Consent_For_Services_Details',false,'physician',$form, $groupTreeIdx);
        ActivityPDFHelper::buildForm_signature_add('Consent_For_Services_Details',false,'physician',$form, $groupTreeIdx);
        $form->assign( 'grTree', $groupTreeIdx );

//        $element = $form->getElement('activity_date_time_time');
//        $groupTreeIdx['Group1_head']['fields'][] = array(
//                'label' => 'Time',
//                'data_type' => 'String',
//                'html_type' => 'Text',
//                'element_name' => 'time',
//                'element_value' => $element->_attributes['value']
//        );
    }

}

