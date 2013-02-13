<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

include_once(JPATH_SITE.'/plugins/civicrm/activityPDF/helper.php');

class plgCivicrmLethalitypdf extends JPlugin
{

    function plgCivicrmLethalitypdf(& $subject, $config)
    {
	parent::__construct($subject, $config);

	// load plugin parameters
	$this->_plugin = JPluginHelper::getPlugin( 'civicrm', 'lethalitypdf' );
	$this->_params = new JParameter( $this->_plugin->params );

    }


    public function civicrm_buildForm( $formName, &$form )
    {

	//echo '<pre>'; print_r($form); die;
	if (isset($form->_activityTypeId) && $form->_activityTypeId == 35 && $formName == 'CRM_Case_Form_Activity' && (CRM_Utils_Array::value( 'snippet', $_GET ) == 3 || CRM_Utils_Array::value( 'snippet', $_GET ) == 10) )
	{
	    $countcolumn = (int)$this->_params->get('countcolumn');
	    $overlenght = (int)$this->_params->get('overlenght');
	    $supercolumn = $this->_params->get('supercolumn');
	    $pagebreak = $this->_params->get('pagebreak');

	    // PDF view rule
	    $form->assign( 'pdf', true );
	    $pdf_template = $this->params->get('pdf_template');
	    if($pdf_template != '')
	    {
		$form->assign( 'pdf_template', $pdf_template );
	    }
	    $form->assign( 'aType', $form->_activityTypeId );
	    $supercolumnArr = array();
	    $tmpArr = explode(',', $supercolumn);
	    if(count($tmpArr) )
	    {
		foreach ($tmpArr as $value)
		{
		    if((int)$value > 0)
		    {
			$supercolumnArr[] = (int)$value;
		    }
		}
	    }
	    $form->assign( 'supercolumnArr', $supercolumnArr );

	    $pagebreakArr = array();
	    $tmpArr = explode(',', $pagebreak);
	    if(count($tmpArr) )
	    {
		foreach ($tmpArr as $value)
		{
		    if((int)$value > 0)
		    {
			$pagebreakArr[] = (int)$value;
		    }
		}
	    }
	    $form->assign( 'pagebreakArr', $pagebreakArr );

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
//            self::civicrm_buildForm_customization( $form, $groupTreeIdx );


	    ActivityPDFHelper::buildForm_reorganization($form, $groupTree, $defaults, $groupTreeIdx, $overlenght, $countcolumn);
	}
    }

    public function civicrm_buildForm_customization( &$form, &$groupTreeIdx )
    {
//echo '<pre>'; print_r($form); die;
//echo '<pre>'; print_r($form->_defaultValues); die;

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

