<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );


class ActivityPDFHelper {

	/**
     * Build form by user info for print PDF
     *
     * @param type $form
     * @param type $groupTree
     */
	function buildForm_user_info(&$form) {
			// add customDataTopUser
			require_once 'CRM/Contact/BAO/Client.php';
			CRM_Contact_BAO_Client::buildForm_user_info( $form );

			$params = array( 'id' => $form->_activityId );
            $defaults = array();
            CRM_Activity_BAO_Activity::retrieve( $params, $defaults );
//			$form->assign( 'details', 			array('label' => 'Reason For Contact', 'html' => $defaults['details'] ));
			$form->assign( 'source_contact_id', array('label' => 'Reported By', 'html' => $defaults['source_contact']) );
			$form->assign( 'subject', 			array('label' => 'Subject', 'html' => $defaults['subject']) );
			$form->assign( 'activity_date_time', array('label' => 'Date', 'html' => $defaults['activity_date_time']) );
			$form->assign( 'rootURL', JURI::root());

			$groupID  = CRM_Utils_Request::retrieve( 'groupID', 'Positive', $form );

			// restructure columns Client_Profile
			$tpl = CRM_Core_Smarty::singleton();
			$tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][1][] = $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][0][1];
			$tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][2][] = $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][0][2];
			// TODO delete correctly
			$tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][1][0]['value'] = trim(str_replace('United States', '', $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][1][0]['value']));

			// date format
			// DOB
			$date = $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][2][2]['value'];
			$date = explode('/', $date);
            if(isset($date[2])){
                $dateNeed =  $date[2].'-'.$date[0].'-'.$date[1];
                $dateNew = CRM_Utils_Date::customFormat($dateNeed);
            }
			else
            {
                $dateNew = '';
            }
            $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][2][2]['value'] = $dateNew;

			// case opened
//			$date = $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][3][3]['value'];
//			$date = explode('/', $date);
//            if(isset($date[2])){
//                $dateNeed =  $date[2].'-'.$date[0].'-'.$date[1];
//                $dateNew = CRM_Utils_Date::customFormat($dateNeed);
//            }
//			else
//            {
//                $dateNew = '';
//            }
//			$tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][3][3]['value'] = $dateNew;
            $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][3][3]['value'] = str_replace('/', '-', $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][3][3]['value']);
            if($tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][1][1]['value'] == 'select')
            {
                $tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'][1][1]['value'] = '';
            }
//echo '<pre>' . print_r($tpl->_tpl_vars['userinfo_groupTree']['Client_Profile']['fields'],true) . '</pre>'            ;
	}

    /**
     * Reorganization form for print PDF 
     * 
     * @param type $form
     * @param type $groupTree
     * @param type $defaults
     * @param type $groupTreeIdx 
     */
    function buildForm_reorganization(&$form, &$groupTree, &$defaults, &$groupTreeIdx, $overlenght = 85, $countcolumn = 0)
    {
//echo '<pre>' . print_r($defaults,true).'</pre>';
    			/* ************* collect all group elements (radio, checkbox) and define content  *************** */
		    $tmpArr = array();
		    foreach($form->_elements as $element){

		       if($element->_type == 'group'){
		        	$val = '';
		        	if(isset($element->_elements[0]) && is_object($element->_elements[0]) && $element->_elements[0]->_type == 'radio'){
		        		$val = 'rb';
		        	} elseif(isset($element->_elements[0]) && is_object($element->_elements[0]) && $element->_elements[0]->_type == 'checkbox'){
		        		$val = 'cb';
		        	}

		        	foreach($element->_elements as $el){
//echo '<pre>' . print_r($el,true).'</pre>';
		        		if(isset($defaults[$element->_name])){
			        		//canonize
//			        		if($defaults[$element->_name] == '0'){$defaults[$element->_name] = 'No';}  // @todo ???
//			        		if($defaults[$element->_name] == '1'){$defaults[$element->_name] = 'Yes';}
			        		if(isset($defaults[$element->_name]) && $defaults[$element->_name] == 'M'){$defaults[$element->_name] = 'Male';}
			        		if(isset($defaults[$element->_name]) && $defaults[$element->_name] == 'F'){$defaults[$element->_name] = 'Female';}
		        			if(isset($defaults[$element->_name]) && $defaults[$element->_name] == 'eastside'){$defaults[$element->_name] = 'Eastside';}
		        			if(isset($defaults[$element->_name]) && $defaults[$element->_name] == 'westside'){$defaults[$element->_name] = 'Westside';}

//echo $element->_name.'==='.print_r($defaults[$element->_name],true).'==='.$el->_text.'(<br>';
                            if(isset($el->_attributes['type']) && $el->_attributes['type'] == 'checkbox')
                            {
                                if(isset($defaults[$element->_name]) && array_key_exists($el->_attributes['id'], $defaults[$element->_name]) ){
                                    $val1 = $val.'ch';
                                } else {
                                    $val1 = $val.'em';
                                }
                            }
                            else
                            {
                                if(isset($defaults[$element->_name]) && $defaults[$element->_name] == $el->_attributes['value'] ) { // $el->_text){
                                    $val1 = $val.'ch';
                                } else {
                                    $val1 = $val.'em';
                                }
                            }
			        		$tmpArr[$element->_name][] = array('text' => $el->_text, // element label
			        											'value' => $val1 );  // if check (file name for element pict)
		        		}
		        	}
		        }
		    }

//			$groupTreeIdx = array();
        /******************************* reorganozation key = block name, remove unneeded blocks *******************/


        	foreach($groupTree as $key => $val){
         		$groupTreeIdx[$val['name']]  = $val;
        	}

			/********************************** fill value for checkbox/radiobutton + set value format (date, state) *********/
			foreach($groupTreeIdx as $gtkey => $gtval){
//if($gtval['title']=='Consent For Services Details' || $gtval['title']=='Authorization')
//{
//    echo '<pre>' . print_r($gtval,true).'</pre>';
//}
				if(isset($groupTreeIdx[$gtkey]['help_pre'])){
//					$groupTreeIdx[$gtkey]['help_pre'] = trim(strip_tags($groupTreeIdx[$gtkey]['help_pre']));
				}

		        // if radio or checkbox - add full content
		        if(is_array($gtval['fields'])){

		        	foreach ($gtval['fields'] as $fieldKey => $fieldVal){

		        		// value formatting block
		        		// clean pre help text
						if(isset($fieldVal['help_pre'])){
//							$groupTreeIdx[$gtkey]['fields'][$fieldKey]['help_pre'] = trim(strip_tags($fieldVal['help_pre']));
						}

                        if(isset($fieldVal['label'])){
							$groupTreeIdx[$gtkey]['fields'][$fieldKey]['label'] = trim(str_replace(':','',$fieldVal['label']));
						}

						//set date to word format
		        		if($fieldVal['data_type'] == 'Date'){
		        			if(isset($fieldVal['element_value']) && $fieldVal['element_value']){
//echo        '<pre>' . $gtkey . print_r( $groupTreeIdx[$gtkey]['fields'][$fieldKey], true) . '</pre>';
                                if(!isset($fieldVal['time_format']))
                                {
                                    $date =	substr($fieldVal['element_value'], 0, 10);
                                }
                                else
                                {
                                    $date =	$fieldVal['element_value'];
                                }
//								$date = explode('/', $date);
//								$dateNeed =  $date[2].'-'.$date[0].'-'.$date[1];
						        $groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] = CRM_Utils_Date::customFormat($date);
					        }
		        		}
		        		// set state/province to word format
		        		if($fieldVal['data_type'] == 'StateProvince'){
		        			if(intval($fieldVal['element_value']) > 0){
		        				$fieldNum = $form->_elementIndex[$fieldVal['element_name']];
				        		foreach((array)$form->_elements[$fieldNum]->_options as $state){
						        	if($state['attr']['value'] == $fieldVal['element_value']){
						        		$groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] = $state['text'];
						        	}
						        }
					        }
		        		}

		        		if($fieldVal['html_type'] == 'Radio' || $fieldVal['html_type'] == 'CheckBox'){
							$len = 0;
		        			if(isset($tmpArr[$fieldVal['element_name']])){
		        				$groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] = $tmpArr[$fieldVal['element_name']];

//		        				if($form->_activityTypeId == 35){
		        					// calculate lenght for correct formatted string
		        					$countField = count($tmpArr[$fieldVal['element_name']]);
		        					if($countField > 0){
		        						$str = '';
		        						foreach ($tmpArr[$fieldVal['element_name']] as $el){
		        							$str .= $el['text'];
		        						}
		        						$len = strlen($str) + ($countField * 4); // radiobutton lenght equal 4 char
		        						// approximate length of the string to the limit of the block
										$groupTreeIdx[$gtkey]['fields'][$fieldKey]['overlenght'] = ($len > $overlenght)?true:false;
                                        $groupTreeIdx[$gtkey]['fields'][$fieldKey]['overlenght_len'] = $len;
                                        $groupTreeIdx[$gtkey]['fields'][$fieldKey]['countrow'] = $countcolumn;
                                        $groupTreeIdx[$gtkey]['fields'][$fieldKey]['widthrow'] = round(100.0/$countcolumn,2)-2;
		        					}
//		        				}

		        			} else {
		        				$groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] = '';
		        			}
		        		}
                        if ( in_array($fieldVal['html_type'], array( 'Select', 'Multi-Select', 'AdvMulti-Select', 'Autocomplete-Select'))    ) // 'CheckBox', 'Radio',
                        {
                            require_once 'CRM/Core/OptionGroup.php';
                            $options = CRM_Core_OptionGroup::valuesByID( $fieldVal['option_group_id'] );

                            $mulValues = explode( CRM_Core_DAO::VALUE_SEPARATOR, $groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] );
//echo '<pre>mulValues ' . print_r($mulValues,true).'</pre>';
                            $groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] = '';
                            foreach( $mulValues as $v1 ) {
                                if ( strlen( $v1 ) == 0 ) {
                                    continue;
                                }
                                if(isset($options[$v1]))
                                {
                                    if( $groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] != '' )
                                    {
                                        $groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] .= ', ';
                                    }
                                    $groupTreeIdx[$gtkey]['fields'][$fieldKey]['element_value'] .= $options[$v1];
                                }
                            }
//echo '<pre>options ' . print_r($options,true).'</pre>';
                        }
		        	}
		        }
//echo '<pre>' . print_r($groupTreeIdx[$gtkey],true).'</pre>';
		    }

			$form->assign( 'grTree', $groupTreeIdx );
    }

    /**
     * Build title for form by activity
     *
     * @param type $form
     * @param type $groupTreeIdx
     */
    public function civicrm_buildForm_activityTitle( &$form, &$groupTreeIdx )
    {
//echo '<pre>'; print_r($form); die;
//echo '<pre>'; print_r($form->_defaultValues); die;
        $subject = '';
		if (isset($form->_activityTypeName))
		{
			$subject = $form->_activityTypeName;
		}

        $groupTreeIdx['Group1_head'] = array(
                                        'name' => 'Group1_head',
                                        'title' => $subject,
                                        'fields' => array()
                                        );
        $groupTreeIdx['Group1_head']['fields']['task_form_type'] = array(
                'label' => 'Task/Form Type',
                'data_type' => 'String',
                'html_type' => 'Text',
                'element_name' => 'task_form_type',
                'element_value' => 	$subject
        );
        $element = $form->getElement('source_contact_id');
        $groupTreeIdx['Group1_head']['fields']['source_contact_id'] = array(
                'label' => 'Reported By',
                'data_type' => 'String',
                'html_type' => 'Text',
                'element_name' => 'source_contact_id',
                'element_value' => $element->_attributes['value']
        );

        $subject = '';
		if (isset($form->_defaultValues['subject']))
		{
			$subject = $form->_defaultValues['subject'];
		}
        $groupTreeIdx['Group1_head']['fields']['task_details'] = array(
                'label' => 'Task Details',
                'data_type' => 'String',
                'html_type' => 'Text',
                'element_name' => 'task_details',
                'element_value' => $subject
        );
        $element = $form->getElement('activity_date_time');
        $groupTreeIdx['Group1_head']['fields']['activity_date_time'] = array(
                'label' => 'Date',
                'data_type' => 'String',
                'html_type' => 'Select date',
                'element_name' => 'activity_date_time',
                'element_value' => $element->_attributes['value']
        );
        $element = $form->getElement('activity_date_time_time');
        $groupTreeIdx['Group1_head']['fields']['time'] = array(
                'label' => 'Time',
                'data_type' => 'String',
                'html_type' => 'Text',
                'element_name' => 'time',
                'element_value' => $element->_attributes['value']
        );

    }

    function buildForm_signature_images(&$form, &$groupTree, &$defaults, &$groupTreeIdx)
	{
		// Retrieve any attached entity files
		CRM_Core_BAO_File::buildSignedAttachments( $form,
                                            'civicrm_activity',
                                            $form->_activityId );
                $params = array();
                $params['target_id'] = $form->_activityId;
                $signatureData = CRM_Case_BAO_Case::getSignatureData($params);
//		echo '<pre>'.print_r($signatureData,true).'</pre>';exit(0);
                $form->assign( 'embedObjectData', $signatureData );
                $form->embedObjectData = $signatureData;

	}
    
    public function buildForm_signature_add($groupName,$field,$type,&$form, &$groupTreeIdx)
    {
//actlog($groupName,__METHOD__);
//actlog($groupTreeIdx,__METHOD__);
//actlog($form->embedObjectData,__METHOD__);
        if(isset($form->embedObjectData)) // 274, 275 // isset($groupTreeIdx[$groupName]['fields']) && 
        {
            if(isset($groupTreeIdx[$groupName]['fields'][$field]))
            {
                $groupTreeIdx[$groupName]['fields'][$field]['element_value'] = '';
            }
            foreach ($form->embedObjectData as $val)
            {
                if($val['type'] == $type)
                {
                    if($field && isset($groupTreeIdx[$groupName]['fields'][$field]))
                    {
                        $groupTreeIdx[$groupName]['fields'][$field]['element_value'] = $form->embedAttachmentTags[$val['file_name_804']];
                    }
                    else
                    {
                        $groupTreeIdx[$groupName]['fields']['Sign_' . $type]['data_type'] = 'String';
                        $groupTreeIdx[$groupName]['fields']['Sign_' . $type]['html_type'] = 'Sign';
                        $groupTreeIdx[$groupName]['fields']['Sign_' . $type]['element_value'] = $form->embedAttachmentTags[$val['file_name_804']];
                        switch($type)
                        {
                            case 'client':
                                $typeName = 'Client';
                                break;
                            case 'staff':
                                $typeName = 'Staff';
                                break;
                            case 'witness' :
                                $typeName = 'Witness';
                                break;
                            case 'physician':
                                $typeName = 'Physician';
                                break;
                        }
                        $groupTreeIdx[$groupName]['fields']['Sign_' . $type]['label'] = $typeName . ' Signature';
                    }
                }
            }
        }
    }
}
