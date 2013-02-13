<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgCivicrmDischargesummary extends JPlugin
{
	var $_referralsActivityType = '';
	var $_referralsGroup = '';
	var $_dischargeActivityType = '';
	var $_referralsTypeIDs = array();
	var $_referralsFieldIDs = array();

	function plgCivicrmDischargesummary(& $subject, $config)
        {
                parent::__construct($subject, $config);
		
		// load plugin parameters
    		$this->_plugin = JPluginHelper::getPlugin( 'civicrm', 'dischargesummary' );
		//echo '<pre>'.print_r($this->_plugin,true).'</pre>';
    		$this->_params = new JParameter( $this->_plugin->params );
		$this->_referralsActivityType = $this->_params->get('referral_activity_type');
		$this->_referralsGroup = $this->_params->get('referral_group');
		$this->_dischargeActivityType = $this->_params->get('discharge_activity_type');
		$this->_referralsTypeIDs = explode(',',$this->_params->get('referral_type_ids'));
		$this->_referralsFieldIDs = explode(',',$this->_params->get('referral_field_ids'));
	}


	public function civicrm_buildForm( $formName, &$form )
        {
		
		if( $formName == 'CRM_Case_Form_Activity')
                {
			if(!isset($form->_activityTypeId))
                        {
                                $aId = CRM_Utils_Array::value( 'id', $_GET );
                                if($aId)
                                {
                                        $dao = new CRM_Activity_BAO_Activity();
                                        $dao->id = $aId;
                                        if( $dao->find( true ) )
                                        {
                                                $aType = $dao->activity_type_id;
                                        }
                                        else
                                        {
                                            $aType = CRM_Utils_Array::value( 'atype', $_GET );
                                            if(!$aType && isset($form->_subType))
                                            {
                                                $aType = $form->_subType;
                                            }
                                        }
                                }
                                else
                                {
                                        $aType = CRM_Utils_Array::value( 'atype', $_GET );
                                    if(!$aType && isset($form->_subType))
                                    {
                                        $aType = $form->_subType;
                                    }
                                }
                        }
                        else
                        {
                                $aType = $form->_activityTypeId;
                        }
			
			// Discharge Summary
                        if($aType == $this->_dischargeActivityType)
                        {
                                if (isset($form->_elementIndex['activity_date_time']))
                                {
                                        $defaults['case_status_id'] = 15; //array_search( 'Recommend for Closure', CRM_Case_PseudoConstant::caseStatus());
                                        $element = $form->getElement('activity_date_time') ;
                                        $element->_label = 'Date Closed';
                                }
				}

				// Days Open
				if(isset($form->_elementIndex['custom_291_-1']))
                        	{
                                	$defaults['custom_291_-1'] = CRM_Case_BAO_Case::calculateDaysOpened($form->_caseId);
                        	}

			// Age Bracket
                        if(isset($form->_elementIndex['custom_300_-1']))
                        {
				require_once 'CRM/Core/BAO/CustomValueTable.php';
			
                		$customParams = array( 'entityID' => $form->_currentlyViewedContactId, 'custom_1807' => true);
                		$customfieldValues = CRM_Core_BAO_CustomValueTable::getValues( $customParams );

				//echo '<pre>'.print_r($form,true).'</pre>';
                                /*$params = array();
                                $params['id'] = $form->_contactID;
                                $params['contact_id'] = $form->_contactID;
                                $defaultAtt = array();
                                $client = CRM_Contact_BAO_Contact::retrieve( $params, $defaultAtt, true );
				*/

                                /*if($customfieldValues['custom_1807'])
                                {
                                        $age = $customfieldValues['custom_1807'];
		

                                                $defaults['custom_113_-1'] = $age;

                                                if( $age <= 12)
                                                	$defaults['custom_300_-1'] = 'child';
                                                else if ( $age >= 13 && $age <= 17)
                                                	$defaults['custom_300_-1'] = 'adolescent';
                                                else if ( $age >= 18 && $age <= 21 )
                                                	$defaults['custom_300_-1'] = 'trans_adult';
                                                else if ( $age >= 22 && $age <= 60 )
                                                	$defaults['custom_300_-1'] = 'adult';
                                                else if ( $age > 61 )
                                                	$defaults['custom_300_-1'] = 'geriatric';
                                                else
                                                	$defaults['custom_300_-1'] = 'unknown';
                                }
                                else
                                {
                                        $defaults['custom_300_-1'] = 'unknown';

                                }*/
                        } // end isset


			require_once 'CRM/Case/Form/Activity/OpenCase.php'; 
                        
                        // Referral Source
                        if(isset($form->_elementIndex['custom_293_-1']))
                        	$defaults['custom_293_-1'] = CRM_Case_Form_Activity_OpenCase::getValue('Caller Information',
                                                                                                'Referral Source',
                        									$form->_caseId,
                        									$form->_currentlyViewedContactId);
                        
                        // Insurance Status
                        if(isset($form->_elementIndex['custom_294_-1']))
                        	$defaults['custom_294_-1'] = CRM_Case_Form_Activity_OpenCase::getValue('Insurance','Insurance Type',$form->_caseId, $form->_currentlyViewedContactId);

                        // Risk Factors
                        if(isset($form->_elementIndex['custom_298_-1']))
                        {        
                                $v = CRM_Case_Form_Activity_OpenCase::getValue('Risk Factors','Eviction or Homeless',$form->_caseId, $form->_currentlyViewedContactId);
                                if($v === 'Yes')
                                	$defaults['custom_298_-1[homeless]'] = 1; // CRM_Case_Form_Activity_OpenCase::getValue('Risk Factors','Eviction or Homeless',$form->_caseId, $form->_currentlyViewedContactId);
                                 
                                $v = CRM_Case_Form_Activity_OpenCase::getValue('Risk Factors','Inpatient Hospital',$form->_caseId, $form->_currentlyViewedContactId);
                                if($v === 'Yes')
                                	$defaults['custom_298_-1[imminent_admit]'] = 1; // CRM_Case_Form_Activity_OpenCase::getValue('Risk Factors','Inpatient Hospital',$form->_caseId, $form->_currentlyViewedContactId);
                                
                                $v = CRM_Case_Form_Activity_OpenCase::getValue('Risk Factors','Incarceration',$form->_caseId, $form->_currentlyViewedContactId);
                                if($v === 'Yes')
                                	$defaults['custom_298_-1[incarceration]'] = 1; // CRM_Case_Form_Activity_OpenCase::getValue('Risk Factors','Incarceration',$form->_caseId, $form->_currentlyViewedContactId);
                                
                                $v = CRM_Case_Form_Activity_OpenCase::getValue('Risk Factors','Risk for Hospitalization',$form->_caseId, $form->_currentlyViewedContactId);
                                if($v == 'Yes')
                                	$defaults['custom_298_-1[future_hospitalization]'] = 1; // CRM_Case_Form_Activity_OpenCase::getValue('Risk Factors','Risk for Hospitalization',$form->_caseId, $form->_currentlyViewedContactId);
                        }       

			
			// CRS Action
                        $v = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Information Only',$form->_caseId, $form->_currentlyViewedContactId);
                        if($v == 'Yes')
                        	$defaults['custom_307_-1[information_only]'] = 1; // CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Called 911',$form->_caseId, $form->_currenltyViewedContactId);

                        $v = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Crisis Plan Only',$form->_caseId, $form->_currentlyViewedContactId);
                        if($v == 'Yes')
                        	$defaults['custom_307_-1[crisis_plan]'] = 1; // CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Called 911',$form->_caseId, $form->_currenltyViewedContactId);

                        $v = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','MCT Dispatch',$form->_caseId, $form->_currentlyViewedContactId);
                        if($v == 'Yes')
                        	$defaults['custom_307_-1[mct]'] = 1; // CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Called 911',$form->_caseId, $form->_currenltyViewedContactId);

                        $v = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Notify APS or CPS',$form->_caseId, $form->_currentlyViewedContactId);
                        if($v == 'Yes')
                        	$defaults['custom_307_-1[aps_cps_gst]'] = 1; // CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Called 911',$form->_caseId, $form->_currenltyViewedContactId);

                        $v = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Non-MH Referral',$form->_caseId, $form->_currentlyViewedContactId);
                        if($v == 'Yes')
                        	$defaults['custom_307_-1[refer_to_non_mh_provider]'] = 1; // CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Called 911',$form->_caseId, $form->_currenltyViewedContactId);

                        $v = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','MH Referral',$form->_caseId, $form->_currentlyViewedContactId);
                        if($v == 'Yes')
                        	$defaults['custom_307_-1[refer_to_mhs_provider]'] = 1; // CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Called 911',$form->_caseId, $form->_currenltyViewedContactId);

                        $v = CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Called 911',$form->_caseId, $form->_currentlyViewedContactId);
                        if($v == 'Yes')
                        	$defaults['custom_307_-1[911_dispatch]'] = 1; // CRM_Case_Form_Activity_OpenCase::getValue('CRS Action','Called 911',$form->_caseId, $form->_currenltyViewedContactId);

			// Lethality
                        if(isset($form->_elementIndex['custom_301_-1']))
                        {
                                $v = CRM_Case_Form_Activity_OpenCase::getValue('Immediate Safety','Threatening Violence',$form->_caseId, $form->_currentlyViewedContactId);
                                if( $v == 'Yes')
                                	$defaults['custom_301_-1[threat_of_violence]'] = 1;

                                /*$v = CRM_Case_Form_Activity_OpenCase::getValue('Immediate Safety','Threatening Violence',$form->_caseId, $form->_currentlyViewedContactId);
                                 if( $v == 'Yes')
                                 $defaults['custom_301_-1[assaultive]'] = 1;
                                 */

                                /*$v = CRM_Case_Form_Activity_OpenCase::getValue('Immediate Safety','',$form->_caseId, $form->_currentlyViewedContactId);
                                 if( $v == 'Yes')
                                 $defaults['custom_301_-1[psychosis]'] = 1;
                                 */

                                $v = CRM_Case_Form_Activity_OpenCase::getValue('Immediate Safety','Gun Weapon Present',$form->_caseId, $form->_currentlyViewedContactId);
                                if( $v == 'Yes')
                                	$defaults['custom_301_-1[weapon_present]'] = 1;

                                $v = CRM_Case_Form_Activity_OpenCase::getValue('Immediate Safety','Suicide Attempt',$form->_caseId, $form->_currentlyViewedContactId);
                                if( $v == 'Yes')
                                	$defaults['custom_301_-1[suicide_attempt]'] = 1;

                                /*$v = CRM_Case_Form_Activity_OpenCase::getValue('Immediate Safety','Threatening Violence',$form->_caseId, $form->_currentlyViewedContactId);
                                 if( $v == 'Yes')
                                 $defaults['custom_301_-1[suicide_ideation_with_pain]'] = 1;
                                 */

                                $v = CRM_Case_Form_Activity_OpenCase::getValue('Immediate Safety','Suicide Ideation',$form->_caseId, $form->_currentlyViewedContactId);
                                if( $v == 'Yes')
                                	$defaults['custom_301_-1[suicide_ideation_with_no_pain]'] = 1;

                                $v = CRM_Case_Form_Activity_OpenCase::getValue('Immediate Safety','Decompensation',$form->_caseId, $form->_currentlyViewedContactId);
                                if( $v == 'Yes')
                                	$defaults['custom_301_-1[rapid_decompensation]'] = 1;

                                $v = CRM_Case_Form_Activity_OpenCase::getValue('Immediate Safety','Intoxication',$form->_caseId, $form->_currentlyViewedContactId);
                                if( $v == 'Yes')
                                	$defaults['custom_301_-1[under_influence_intoxication]'] = 1;

                                $v = CRM_Case_Form_Activity_OpenCase::getValue('Immediate Safety','Children In House',$form->_caseId, $form->_currentlyViewedContactId);
                                if( $v == 'Yes')
                                	$defaults['custom_301_-1[children_present]'] = 1;

                                $v = CRM_Case_Form_Activity_OpenCase::getValue('Immediate Safety','Medical Issues',$form->_caseId, $form->_currentlyViewedContactId);
                                if( $v == 'Yes')
                                	$defaults['custom_301_-1[medical_issues]'] = 1;
                        }


			 if($form->_caseId && $form->_currentlyViewedContactId)
                        {
                                // Urgent Care Appointments
                                $atArray = array('activity_type_id' => 43);
                                $activities = CRM_Case_BAO_Case::getCaseActivity( $form->_caseId, $atArray, $form->_currentlyViewedContactId);
                                $activities = array_keys($activities);
                                if( count($activities) > 0 )
                                {
                                        require_once 'CRM/Case/XMLProcessor/Report.php';
                                        $xmlProcessor = new CRM_Case_XMLProcessor_Report( );
                                        /*foreach($activities as $k => $activity_id)
                                         {
                                         $report       = $xmlProcessor->getActivityInfo( $form->_currentlyViewedContactId, $activity_id, true );
                                         $form->addDate( 'custom_348_-11'.$k, ts('Appointment Date'), true, array( 'formatType' => 'activityDate') );

                                         $checkBoxes = array( );
                                         $checkBoxes[0] = $form->addElement('checkbox', 'custom_349_-1'.$k, null, '' );

                                         $form->addGroup  ( $checkBoxes, 'custom_349_-11'.$k );
                                         $form->addElement( 'checkbox', 'toggleSelect', null, null, array( 'onclick' => "return toggleCheckboxVals('contact_check',this);" ) );

                                         }
                                         */
                                }
                        }

			// DOB from Intake
                        if (isset($report['customGroups']['Client Information'][8]) && date_parse($report['customGroups']['Client Information'][8]['value']))
                        {
                                if (isset($form->_elementIndex['custom_304_-1']) && $defaults['custom_304_-1'] != '')
                                {
                                        $defaults['custom_304_-1'] = CRM_Utils_Date::customFormat(CRM_Utils_Date::processDate($report['customGroups']['Client Information'][8]['value']),'%m/%d/%Y');
                                }

                                if (isset($form->_elementIndex['custom_674_-1']) && $defaults['custom_674_-1'] != '')
                                {
                                        $defaults['custom_674_-1'] = CRM_Utils_Date::customFormat(CRM_Utils_Date::processDate($report['customGroups']['Client Information'][8]['value']),'%m/%d/%Y');
                                }
                        }
                        else
                        {
                		if (isset($form->_elementIndex['custom_304_-1']))
                		{
                    			$defaults['custom_304_-1'] = '';
                		}

                		if (isset($form->_elementIndex['custom_674_-1']))
                		{
                    			$defaults['custom_674_-1'] = '';
                		}
                        }


				// Appointments
                                /*if (isset($form->_elementIndex['custom_357_-1']))
                                {
                                        $atArray = array('activity_type_id' => 53); //  Therapist Appointment - 53
                                        $activities = CRM_Case_BAO_Case::getCaseActivity( $form->_caseId, $atArray, $form->_currentlyViewedContactId);
                                        $activities_key = array_keys($activities);

                                        if( count($activities_key) > 0 )
                                        {
                                                if(isset($activities[$activities_key[0]]))
                                                {
                                                        $result = $this->joomla_civicrm_buildForm_get_activity_appt( $activities[$activities_key[0]] );
                                                        $defaults['custom_357_-1'] = $result['date'];
                                                        $defaults['custom_358_-1'] = $result['status'];
                                                }

                                                if(isset($activities[$activities_key[1]]))
                                                {
                                                        $result = $this->joomla_civicrm_buildForm_get_activity_appt( $activities[$activities_key[1]] );
                                                        $defaults['custom_359_-1'] = $result['date'];
                                                        $defaults['custom_360_-1'] = $result['status'];
                                                }

                                                if(isset($activities[$activities_key[2]]))
                                                {
                                                        $result = $this->joomla_civicrm_buildForm_get_activity_appt( $activities[$activities_key[2]] );
                                                        $defaults['custom_361_-1'] = $result['date'];
                                                        $defaults['custom_362_-1'] = $result['status'];
                                                }

                                                if(isset($activities[$activities_key[3]]))
                                                {
                                                        $result = $this->joomla_civicrm_buildForm_get_activity_appt( $activities[$activities_key[3]] );
                                                        $defaults['custom_363_-1'] = $result['date'];
                                                        $defaults['custom_364_-1'] = $result['status'];
                                                }

                                        }
                                }

				if (isset($form->_elementIndex['custom_348_-1']))
                                {
                                        $atArray = array('activity_type_id' => 54); //  Physician Appointment - 54
                                        $activities = CRM_Case_BAO_Case::getCaseActivity( $form->_caseId, $atArray, $form->_currentlyViewedContactId);

                                        $activities_key = array_keys($activities);

                                        if( count($activities_key) > 0 )
                                        {
                                                if(isset($activities[$activities_key[0]]))
                                                {
                                                        $result = $this->joomla_civicrm_buildForm_get_activity_appt( $activities[$activities_key[0]] );
                                                        $defaults['custom_348_-1'] = $result['date'];
                                                        $defaults['custom_349_-1'] = $result['status'];
                                                }

                                                if(isset($activities[$activities_key[1]]))
                                                {
                                                        $result = $this->joomla_civicrm_buildForm_get_activity_appt( $activities[$activities_key[1]] );
                                                        $defaults['custom_350_-1'] = $result['date'];
                                                        $defaults['custom_351_-1'] = $result['status'];
                                                }

                                                if(isset($activities[$activities_key[2]]))
                                                {
                                                        $result = $this->joomla_civicrm_buildForm_get_activity_appt( $activities[$activities_key[2]] );
                                                        $defaults['custom_352_-1'] = $result['date'];
                                                        $defaults['custom_353_-1'] = $result['status'];
                                                }

                                                if(isset($activities[$activities_key[3]]))
                                                {
                                                        $result = $this->joomla_civicrm_buildForm_get_activity_appt( $activities[$activities_key[3]] );
                                                        $defaults['custom_354_-1'] = $result['date'];
                                                        $defaults['custom_355_-1'] = $result['status'];
                                                }
                                        }
                                }*/

				if (isset($form->_elementIndex['custom_365_-1']))
                                {
                                        // Total Visits IHIT/IFIT
                                        $atArray = array('activity_type_id' => CRM_Core_OptionGroup::getValue( 'activity_type', 'IHIT Visit', 'label' ));
                                        $activities = CRM_Case_BAO_Case::getCaseActivity( $form->_caseId, $atArray, $form->_currentlyViewedContactId);

                                        $activities_key = array_keys($activities);
                                        if( count($activities_key) > 0 )
                                        {
                                                $defaults['custom_365_-1'] = count($activities_key);
                                        }
                                }

				if (isset($form->_groupTree[77]['fields'][792]))
                		{
                    			$atArray = array('activity_type_id' => $this->_referralsActivityType); // Out Referrals Given to Client
		                    	$activities = CRM_Case_BAO_Case::getCaseActivity( $form->_caseId, $atArray, $form->_currentlyViewedContactId );
                   		 	$activities = array_keys($activities);

                    			if( count($activities) > 0 )
                    			{
                        			require_once 'CRM/Core/BAO/CustomGroup.php';
			                        require_once 'CRM/Core/BAO/CustomOption.php';
                        			$formAlt = array();
			                        $dataArr = array();
                       			 	$refTypeOption = array();

			                        foreach ($activities as $k => $v)
                        			{
				                            	$groupTree =& CRM_Core_BAO_CustomGroup::getTree( 'Activity', $formAlt , $v, null, $this->_referralsActivityType);

								foreach($this->_referralsFieldIDs as $k => $id)
								{
                                                               		if(isset($groupTree[$this->_referralsGroup]['fields'][$id]['customValue'][1]['data']) && strpos($groupTree[$this->_referralsGroup]['fields'][$id]['column_name'], 'non_mental')===0 )
                                                                       {
                                                                       		$dataArr[0][] = $groupTree[$this->_referralsGroup]['fields'][$id]['customValue'][1]['data'];
                                                                               	$refTypeOption[0][] = CRM_Core_BAO_CustomOption::getCustomOption($id);
                                                                 	}
								}

								foreach($this->_referralsTypeIDs as $k => $id)
								{

                                                               		if(isset($groupTree[$this->_referralsGroup]['fields'][$id]['customValue'][1]['data']) && strpos($groupTree[$this->_referralsGroup]['fields'][$id]['column_name'], '_nmh_')>0 )
                                                                        {
                                                                        	$dataArr[1][] = $groupTree[$this->_referralsGroup]['fields'][$id]['customValue'][1]['data'];
										//echo '<pre>'.print_r($groupTree[$this->_referralsGroup]['fields'][$id],true).'</pre>';
                                                                               	$refTypeOption[1][] = CRM_Core_BAO_CustomOption::getCustomOption($id);
                                                                        }
								}

                        			}
                    			}


					$element = $form->getElement($form->_groupTree[77]['fields'][792]['element_name']) ;

                    			$defaults[$form->_groupTree[77]['fields'][792]['element_name']] = implode(',',$dataArr[0]);

					$element->_options = array();
                    			$i = 0;
                    			if (!empty($refTypeOption) && $refTypeOption[0] && $refTypeOption[1])
                    			{
                        			foreach ($refTypeOption[0][0] as $v)
                        			{
                            				$key = array_search($v['value'], $dataArr[0]);
                            				if ($key !== false)
                            				{
                                				$element->_options[$i]['text'] =  $v['label'];
                                				$element->_options[$i]['attr']['value'] = $dataArr[0][$key];
                                				foreach ($refTypeOption[1][0] as $v1)
                                				{
                                    					if ($v1['value'] == $dataArr[1][$key])
                                    					{
 					                                       $element->_options[$i]['text'] = $element->_options[$i]['text'] .' ('.$v1['label'].')';
                                        					$i++;
                                    					}
                                				}

                            				}
                        			}
                    			}

                    			$element->freeze( );

				}
				
				// Linkage
                                if (isset($form->_elementIndex['custom_330_-1']))
                                {
                                        require_once 'CRM/Case/Form/Activity/OpenCase.php';
                                        $defaults['custom_330_-1'] = CRM_Case_Form_Activity_OpenCase::getValue('Linkage and Referrals',
                                                                                                'Linkage',
                                        $form->_caseId,
                                        $form->_currentlyViewedContactId);
                                }

				if (isset($form->_groupTree[77]['fields'][791]))
                                {
                                        $atArray = array('activity_type_id' => 101); // Out Referrals Given to Client
                                        $activities = CRM_Case_BAO_Case::getCaseActivity( $form->_caseId, $atArray, $form->_currentlyViewedContactId );
                                        $activities = array_keys($activities);

                                        if( count($activities) > 0 )
                                        {
                                                require_once 'CRM/Core/BAO/CustomGroup.php';
                                                require_once 'CRM/Core/BAO/CustomOption.php';
                                                $formAlt = array();
                                                $refTypeOption = array();
                                                $dataArr = array();

                                                foreach ($activities as $k => $v)
                                                {
				                        // Mental Health Out Referral
                                                        $groupTree =& CRM_Core_BAO_CustomGroup::getTree( 'Activity', $formAlt , $v,null, '101');

							foreach($this->_referralsFieldIDs as $k => $id)
							{
                                                        	if(isset($groupTree[$this->_referralsGroup]['fields'][$id]['customValue'][1]['data']))
                                                        	{
                                                                	$dataArr[0][] = $groupTree[$this->_referralsGroup]['fields'][$id]['customValue'][1]['data'];
                                                                	$refTypeOption[0][] = CRM_Core_BAO_CustomOption::getCustomOption($id);
                                                        	}
                                                        	else
                                                        	{
									for($i=0; $i<=2;$i++)
									{
						
                                                               			if(isset($groupTree[$this->_referralsGroup]['fields'][$this->_referralsFieldIDs[$i]]['customValue'][1]['data'])  )
                                                                        	{
                                                                                	$dataArr[0][] = $groupTree[$this->_referralsGroup]['fields'][$this->_referralsFieldIDs[$i]]['customValue'][1]['data'];
                                                                                	$refTypeOption[0][] = CRM_Core_BAO_CustomOption::getCustomOption($this->_referralsFieldIDs[$i]);
                                                                        	}
									}

									for($i=0; $i<=2;$i++)
									{
                                                               			if(isset($groupTree[$this->_referralsGroup]['fields'][$this->_referralsTypeIDs[$i]]['customValue'][1]['data']) )
                                                                        	{
                                                                                	$dataArr[1][] = $groupTree[$this->_referralsGroup]['fields'][$this->_referralsTypeIDs[$i]]['customValue'][1]['data'];
                                                                               		$refTypeOption[1][] = CRM_Core_BAO_CustomOption::getCustomOption($this->_referralsTypeIDs[$i]);
                                                                        	}
									}
                                                        	}
							}
	
							foreach($this->_referralsTypeIDs as $k => $id)
							{
                                                                $dataArr[1][] = $groupTree[$this->_referralsGroup]['fields'][$id]['customValue'][1]['data'];
                                                                $refTypeOption[1][] = CRM_Core_BAO_CustomOption::getCustomOption($id);
							}


                                                }
							//echo '<pre>'.print_r($dataArr,true).'</pre>';
                                        }


					
					$element = $form->getElement($form->_groupTree[77]['fields'][791]['element_name']) ;

                                        $element->_options = array();
                                        $i = 0;
					
                                        if (!empty($refTypeOption) && $refTypeOption[0])// && $refTypeOption[1])
                                        {
                                                $defaults[$form->_groupTree[77]['fields'][791]['element_name']] = implode(',',$dataArr[0]);
                                                foreach ($refTypeOption[0][0] as $v)
                                                {
                                                        $key = array_search($v['value'], $dataArr[0]);
                                                        if ($key !== false)
                                                        {
                                                                $element->_options[$i]['text'] =  $v['label'];
                                                                $element->_options[$i]['attr']['value'] = $dataArr[0][$key];

                                                                foreach ($refTypeOption[1][0] as $v1)
                                                                {
                                                                        if ($v1['value'] == $dataArr[1][$key])
                                                                        {
                                                                                $element->_options[$i]['text'] = $element->_options[$i]['text'] .' ('.$v1['label'].')';
                                                                                $i++;
                                                                        }
                                                                }

                                                        }
                                                }
                                        }

                                        $element->freeze( );

                        }


                        


		}

		$form->setDefaults($defaults);


	}

	 /**
         *
         * Method is called after user data is stored in the database
         *
         * @param       array           holds the old user data
         * @param       boolean         true if a new user is stored
         */
	function civicrm_post( $op, $objectName, $objectId, &$objectRef )
        {

		jimport('civicrm.case');
	
		_civicrm_initialize();

		$changecasestatus_activity_type = $this->_params->get('changecasestatus_activity_type');
		$discharge_activity_type = $this->_params->get('discharge_activity_type');
		$reclosure_status = $this->_params->get('reclosure_status');


		if($op === 'create' && $objectName == 'Activity' && $objectRef->activity_type_id == 47)
		{
			if(isset($_POST['form_emel_name']) || isset($_GET['autosave'])) {
				 return;
			}

			require_once 'CRM/Core/DAO.php';
        		$oldCaseStatus =  CRM_Core_DAO::getFieldValue('CRM_Case_BAO_Case', $objectRef->case_id, 'status_id' );
        		$targetContactId =  CRM_Core_DAO::getFieldValue('CRM_Case_DAO_CaseContact', $objectRef->case_id, 'contact_id' );

				$params = array(
						    'subject' => 'Change Case Status',
						    'status_id' => 2,
						    'source_contact_id' => $objectRef->source_contact_id,
						    'activity_date_time' => date('YmdHis'),
						    'source_contact_qid' => $objectRef->source_contact_id,
						    'case_status_id' => 15,
						    'current_case_status_id' => $oldCaseStatus,
						    'activity_type_id' => 16,
						    'target_contact_id' => $targetContactId,
						    'custom' => Array(),
						    'assignee_contact_id' => Array(),
						    'id' => '',
						    'case_id' => $objectRef->case_id
				 );
				 
			    include_once 'CRM/Activity/BAO/Activity.php';
			    $activity = CRM_Activity_BAO_Activity::create( $params );

			    $caseParams = array( 
			    						'source_contact_id' => $objectRef->source_contact_id,
			                     				'activity_date_time' => date('YmdHis'),  
									'source_contact_qid'     => $objectRef->source_contact_id,   
									'case_status_id'     => 15,   
									'current_case_status_id' =>  $oldCaseStatus,   
									'activity_type_id'     => 16,   
									'target_contact_id'     => $targetContactId,   
									'assignee_contact_id' =>   Array(), 
									'case_id'     => $objectRef->case_id,  
			    						'priority_id'     => 2, 
			   						'statusMsg'     => 'Case Status changed successfully', 
			    						'id'     => $objectRef->case_id 
			    );			        
				$case = CRM_Case_BAO_Case::create( $caseParams );		    
			    
			    $caseParams = array( 'activity_id' => $activity->id,
			                         'case_id'     => $params['case_id']   );
			    CRM_Case_BAO_Case::processCaseActivity( $caseParams );

				$caseParams['case_status_id'] = $params['case_status_id'];
				$caseParams['current_case_status_id'] = $params['current_case_status_id'];
			    CRM_Case_BAO_Case::processChangeCaseStatusActivity( $caseParams );
			    

		} 


	}

	/**
 * the client information should get
 * client's case information
 *
 * @param $element
 *
 */
function joomla_civicrm_buildForm_get_activity_appt( $element )
{
        $result = array();
        $result['date'] = CRM_Utils_Date::customFormat(CRM_Utils_Date::processDate($element['display_date']),'%m/%d/%Y');
        switch ($element['status']) {
                case 'Kept (UCC Only)':
                        $status = 'kept';
                        break;
                case 'No Show (UCC Only)':
                        $status = 'no_show';
                        break;
                case 'Cancelled (UCC Only)':
                        $status = 'cancelled';
                        break;

                default:
                        $status = '';
                        break;
        }
        $result['status'] = $status;

        return $result;
}



}
