<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgCivicrmTaskFieldToTextarea extends JPlugin
{
	/**
	*	Post Civicrm Plugin
	*/
	
	public function civicrm_buildForm( $formName, &$form ){
		if ($formName == 'CRM_Case_Form_Activity'){
			// for 'subject' field change type from text to textarea
			if (isset($form->_elementIndex['subject']))
			{
				$elementOld = $form->getElement('subject');
				$form->_elements[$form->_elementIndex['subject']] = new HTML_QuickForm_textarea($elementOld->getName(), $elementOld->getLabel());
				$element = $form->getElement('subject');
				$element->setRows(4);
				$element->setCols(60);
				$element->setValue($elementOld->getValue());
				$element->updateAttributes(array('style'=>"width:640px;"));
				$form->_fields['subject']['type'] = 'textarea';
				$form->_fields['subject']['attributes'] = array('rows' => 4, 'cols' => 60);
			}
		}
	}

	public function civicrm_caseHistory( &$activities ){
		
		foreach($activities as $key=>$val){
			$activities[$key]['subject'] = str_replace(array("\r\n", "\r", "\n"), ' ', $val['subject']);
		}
	}
}