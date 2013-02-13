select 
sql_calc_found_rows 
sum((case month(case_civireport.start_date) when 7 then 1 else 0 end)) AS `Jul`,
sum((case month(case_civireport.start_date) when 8 then 1 else 0 end)) AS `Aug`,
sum((case month(case_civireport.start_date) when 9 then 1 else 0 end)) AS `Sep`,
sum((case month(case_civireport.start_date) when 10 then 1 else 0 end)) AS `Oct`,
sum((case month(case_civireport.start_date) when 11 then 1 else 0 end)) AS `Nov`,
sum((case month(case_civireport.start_date) when 12 then 1 else 0 end)) AS `December`,
sum((case month(case_civireport.start_date) when 1 then 1 else 0 end)) AS `Jan`,
sum((case month(case_civireport.start_date) when 2 then 1 else 0 end)) AS `Feb`,
sum((case month(case_civireport.start_date) when 3 then 1 else 0 end)) AS `March`,
sum((case month(case_civireport.start_date) when 4 then 1 else 0 end)) AS `Apr`,
sum((case month(case_civireport.start_date) when 5 then 1 else 0 end)) AS `May`,
sum((case month(case_civireport.start_date) when 6 then 1 else 0 end)) AS `Jun`,
count(case_civireport.start_date) AS `TTL` 
FROM civicrm_contact contact_civireport
            LEFT JOIN civicrm_case_contact ccc ON ccc.contact_id = contact_civireport.id
            LEFT JOIN civicrm_case case_civireport ON case_civireport.id = ccc.case_id
            LEFT JOIN civicrm_case_activity cca ON cca.case_id = case_civireport.id
            LEFT JOIN civicrm_activity activity_civireport ON (activity_civireport.id = cca.activity_id AND activity_civireport.is_current_revision=1) 
         LEFT JOIN civicrm_value_additional_info_12 value_additional_info_12_civireport ON value_additional_info_12_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_caller_information_8 value_caller_information_8_civireport ON value_caller_information_8_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_case_events_4 value_case_events_4_civireport ON value_case_events_4_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_client_information_10 value_client_information_10_civireport ON value_client_information_10_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_client_profile_67 value_client_profile_67_civireport ON value_client_profile_67_civireport.entity_id = contact_civireport.id LEFT JOIN civicrm_value_crisis_plan_13 value_crisis_plan_13_civireport ON value_crisis_plan_13_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_crisisassessment_2 value_crisisassessment_2_civireport ON value_crisisassessment_2_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_crs_action_3 value_crs_action_3_civireport ON value_crs_action_3_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_emergency_contact_7 value_emergency_contact_7_civireport ON value_emergency_contact_7_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_emergency_severity_6 value_emergency_severity_6_civireport ON value_emergency_severity_6_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_financial_9 value_financial_9_civireport ON value_financial_9_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_insurance_30 value_insurance_30_civireport ON value_insurance_30_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_linkage_14 value_linkage_14_civireport ON value_linkage_14_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_provider_pcp_32 value_provider_pcp_32_civireport ON value_provider_pcp_32_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_referral_source_31 value_referral_source_31_civireport ON value_referral_source_31_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_release_of_information_11 value_release_of_information_11_civireport ON value_release_of_information_11_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_risk_factors_5 value_risk_factors_5_civireport ON value_risk_factors_5_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_five_axis_diagnosis_27 value_five_axis_diagnosis_27_civireport ON value_five_axis_diagnosis_27_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_mct_dispatch_details_127 value_mct_dispatch_details_127_civireport ON value_mct_dispatch_details_127_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_mct_dispatch_notes_128 value_mct_dispatch_notes_128_civireport ON value_mct_dispatch_notes_128_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_mct_dispatch_selections_129 value_mct_dispatch_selections_129_civireport ON value_mct_dispatch_selections_129_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_mct_focal_issues_130 value_mct_focal_issues_130_civireport ON value_mct_focal_issues_130_civireport.entity_id = activity_civireport.id 
WHERE ( contact_civireport.contact_type = 'Individual' )  AND value_client_information_10_civireport.veteran__62=1 AND ( case_civireport.case_type_id IN ( '2') )

Age Bracket
select 

sum((case month(case_civireport.start_date) when 7 then 1 else 0 end)) AS `Jul`,
sum((case month(case_civireport.start_date) when 8 then 1 else 0 end)) AS `Aug`,
sum((case month(case_civireport.start_date) when 9 then 1 else 0 end)) AS `Sep`,
sum((case month(case_civireport.start_date) when 10 then 1 else 0 end)) AS `Oct`,
sum((case month(case_civireport.start_date) when 11 then 1 else 0 end)) AS `Nov`,
sum((case month(case_civireport.start_date) when 12 then 1 else 0 end)) AS `December`,
sum((case month(case_civireport.start_date) when 1 then 1 else 0 end)) AS `Jan`,
sum((case month(case_civireport.start_date) when 2 then 1 else 0 end)) AS `Feb`,
sum((case month(case_civireport.start_date) when 3 then 1 else 0 end)) AS `March`,
sum((case month(case_civireport.start_date) when 4 then 1 else 0 end)) AS `Apr`,
sum((case month(case_civireport.start_date) when 5 then 1 else 0 end)) AS `May`,
sum((case month(case_civireport.start_date) when 6 then 1 else 0 end)) AS `Jun`,
count(case_civireport.start_date) AS `TTL` 
FROM civicrm_contact contact_civireport
            LEFT JOIN civicrm_case_contact ccc ON ccc.contact_id = contact_civireport.id
            LEFT JOIN civicrm_case case_civireport ON case_civireport.id = ccc.case_id
            LEFT JOIN civicrm_case_activity cca ON cca.case_id = case_civireport.id
            LEFT JOIN civicrm_activity activity_civireport ON (activity_civireport.id = cca.activity_id AND activity_civireport.is_current_revision=1) 
         LEFT JOIN civicrm_value_additional_info_12 value_additional_info_12_civireport ON value_additional_info_12_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_caller_information_8 value_caller_information_8_civireport ON value_caller_information_8_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_case_events_4 value_case_events_4_civireport ON value_case_events_4_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_client_information_10 value_client_information_10_civireport ON value_client_information_10_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_client_profile_67 value_client_profile_67_civireport ON value_client_profile_67_civireport.entity_id = contact_civireport.id LEFT JOIN civicrm_value_crisis_plan_13 value_crisis_plan_13_civireport ON value_crisis_plan_13_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_crisisassessment_2 value_crisisassessment_2_civireport ON value_crisisassessment_2_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_crs_action_3 value_crs_action_3_civireport ON value_crs_action_3_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_emergency_contact_7 value_emergency_contact_7_civireport ON value_emergency_contact_7_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_emergency_severity_6 value_emergency_severity_6_civireport ON value_emergency_severity_6_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_financial_9 value_financial_9_civireport ON value_financial_9_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_insurance_30 value_insurance_30_civireport ON value_insurance_30_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_linkage_14 value_linkage_14_civireport ON value_linkage_14_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_provider_pcp_32 value_provider_pcp_32_civireport ON value_provider_pcp_32_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_referral_source_31 value_referral_source_31_civireport ON value_referral_source_31_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_release_of_information_11 value_release_of_information_11_civireport ON value_release_of_information_11_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_risk_factors_5 value_risk_factors_5_civireport ON value_risk_factors_5_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_five_axis_diagnosis_27 value_five_axis_diagnosis_27_civireport ON value_five_axis_diagnosis_27_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_mct_dispatch_details_127 value_mct_dispatch_details_127_civireport ON value_mct_dispatch_details_127_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_mct_dispatch_notes_128 value_mct_dispatch_notes_128_civireport ON value_mct_dispatch_notes_128_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_mct_dispatch_selections_129 value_mct_dispatch_selections_129_civireport ON value_mct_dispatch_selections_129_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_mct_focal_issues_130 value_mct_focal_issues_130_civireport ON value_mct_focal_issues_130_civireport.entity_id = activity_civireport.id 
WHERE ( contact_civireport.contact_type = 'Individual' )  AND value_client_information_10_civireport.veteran__62=1 AND ( case_civireport.case_type_id IN ( '2') ) AND  floor(((to_days(now()) - to_days(contact_civireport.birth_date)) / 365.25)) > 18  AND floor(((to_days(now()) - to_days(contact_civireport.birth_date)) / 365.25)) < 21

Insurance
select
value_insurance_30_civireport.insurance_type_121, 
sum((case month(case_civireport.start_date) when 7 then 1 else 0 end)) AS `Jul`,
sum((case month(case_civireport.start_date) when 8 then 1 else 0 end)) AS `Aug`,
sum((case month(case_civireport.start_date) when 9 then 1 else 0 end)) AS `Sep`,
sum((case month(case_civireport.start_date) when 10 then 1 else 0 end)) AS `Oct`,
sum((case month(case_civireport.start_date) when 11 then 1 else 0 end)) AS `Nov`,
sum((case month(case_civireport.start_date) when 12 then 1 else 0 end)) AS `December`,
sum((case month(case_civireport.start_date) when 1 then 1 else 0 end)) AS `Jan`,
sum((case month(case_civireport.start_date) when 2 then 1 else 0 end)) AS `Feb`,
sum((case month(case_civireport.start_date) when 3 then 1 else 0 end)) AS `March`,
sum((case month(case_civireport.start_date) when 4 then 1 else 0 end)) AS `Apr`,
sum((case month(case_civireport.start_date) when 5 then 1 else 0 end)) AS `May`,
sum((case month(case_civireport.start_date) when 6 then 1 else 0 end)) AS `Jun`,
count(case_civireport.start_date) AS `TTL` 
FROM civicrm_contact contact_civireport
            LEFT JOIN civicrm_case_contact ccc ON ccc.contact_id = contact_civireport.id
            LEFT JOIN civicrm_case case_civireport ON case_civireport.id = ccc.case_id
            LEFT JOIN civicrm_case_activity cca ON cca.case_id = case_civireport.id
            LEFT JOIN civicrm_activity activity_civireport ON (activity_civireport.id = cca.activity_id AND activity_civireport.is_current_revision=1) 
         LEFT JOIN civicrm_value_client_information_10 value_client_information_10_civireport ON value_client_information_10_civireport.entity_id = activity_civireport.id
         LEFT JOIN civicrm_value_insurance_30 value_insurance_30_civireport ON value_insurance_30_civireport.entity_id = activity_civireport.id  
WHERE ( contact_civireport.contact_type = 'Individual' )  AND value_client_information_10_civireport.veteran__62=1 GROUP BY value_insurance_30_civireport.insurance_type_121

Referral Source
select
source_122, 
sum((case month(case_civireport.start_date) when 7 then 1 else 0 end)) AS `Jul`,
sum((case month(case_civireport.start_date) when 8 then 1 else 0 end)) AS `Aug`,
sum((case month(case_civireport.start_date) when 9 then 1 else 0 end)) AS `Sep`,
sum((case month(case_civireport.start_date) when 10 then 1 else 0 end)) AS `Oct`,
sum((case month(case_civireport.start_date) when 11 then 1 else 0 end)) AS `Nov`,
sum((case month(case_civireport.start_date) when 12 then 1 else 0 end)) AS `December`,
sum((case month(case_civireport.start_date) when 1 then 1 else 0 end)) AS `Jan`,
sum((case month(case_civireport.start_date) when 2 then 1 else 0 end)) AS `Feb`,
sum((case month(case_civireport.start_date) when 3 then 1 else 0 end)) AS `March`,
sum((case month(case_civireport.start_date) when 4 then 1 else 0 end)) AS `Apr`,
sum((case month(case_civireport.start_date) when 5 then 1 else 0 end)) AS `May`,
sum((case month(case_civireport.start_date) when 6 then 1 else 0 end)) AS `Jun`,
count(case_civireport.start_date) AS `TTL` 
FROM civicrm_contact contact_civireport
            LEFT JOIN civicrm_case_contact ccc ON ccc.contact_id = contact_civireport.id
            LEFT JOIN civicrm_case case_civireport ON case_civireport.id = ccc.case_id
            LEFT JOIN civicrm_case_activity cca ON cca.case_id = case_civireport.id
            LEFT JOIN civicrm_activity activity_civireport ON (activity_civireport.id = cca.activity_id AND activity_civireport.is_current_revision=1) 
         LEFT JOIN civicrm_value_client_information_10 value_client_information_10_civireport ON value_client_information_10_civireport.entity_id = activity_civireport.id
         LEFT JOIN civicrm_value_referral_source_31 value_referral_source_31_civireport ON value_referral_source_31_civireport.entity_id = activity_civireport.id  
WHERE ( contact_civireport.contact_type = 'Individual' )  AND value_client_information_10_civireport.veteran__62=1 GROUP BY source_122

Focal Issue
select
SELECT
focal_issue_306, 
sum((case month(case_civireport.start_date) when 7 then 1 else 0 end)) AS `Jul`,
sum((case month(case_civireport.start_date) when 8 then 1 else 0 end)) AS `Aug`,
sum((case month(case_civireport.start_date) when 9 then 1 else 0 end)) AS `Sep`,
sum((case month(case_civireport.start_date) when 10 then 1 else 0 end)) AS `Oct`,
sum((case month(case_civireport.start_date) when 11 then 1 else 0 end)) AS `Nov`,
sum((case month(case_civireport.start_date) when 12 then 1 else 0 end)) AS `December`,
sum((case month(case_civireport.start_date) when 1 then 1 else 0 end)) AS `Jan`,
sum((case month(case_civireport.start_date) when 2 then 1 else 0 end)) AS `Feb`,sum((case month(case_civireport.start_date) when 3 then 1 else 0 end)) AS `March`,sum((case month(case_civireport.start_date) when 4 then 1 else 0 end)) AS `Apr`,sum((case month(case_civireport.start_date) when 5 then 1 else 0 end)) AS `May`,sum((case month(case_civireport.start_date) when 6 then 1 else 0 end)) AS `Jun`,count(case_civireport.start_date) AS `TTL`
FROM civicrm_contact contact_civireport
            LEFT JOIN civicrm_address address_civireport 
                   ON (contact_civireport.id = address_civireport.contact_id AND 
                      address_civireport.is_primary = 1 )
            LEFT JOIN civicrm_case_contact ccc ON ccc.contact_id = contact_civireport.id
            LEFT JOIN civicrm_case case_civireport ON case_civireport.id = ccc.case_id
            LEFT JOIN civicrm_case_activity cca ON cca.case_id = case_civireport.id
            LEFT JOIN civicrm_activity activity_civireport ON (activity_civireport.id = cca.activity_id AND activity_civireport.is_current_revision=1) 
         LEFT JOIN civicrm_value_client_profile_67 value_client_profile_67_civireport ON value_client_profile_67_civireport.entity_id = contact_civireport.id 
LEFT JOIN civicrm_value_discharge_28 value_discharge_28_civireport ON value_discharge_28_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_discharge_comments_141 value_discharge_comments_141_civireport ON value_discharge_comments_141_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_discharge_urgent_care_83 value_discharge_urgent_care_83_civireport ON value_discharge_urgent_care_83_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_focal_issue_72 value_focal_issue_72_civireport ON value_focal_issue_72_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_overall_satisfaction_average_84 value_overall_satisfaction_average_84_civireport ON value_overall_satisfaction_average_84_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_referrals_given_77 value_referrals_given_77_civireport ON value_referrals_given_77_civireport.entity_id = activity_civireport.id
LEFT JOIN civicrm_value_client_information_10 value_client_information_10_civireport ON value_client_information_10_civireport.entity_id = activity_civireport.id 
WHERE ( contact_civireport.contact_type = 'Individual' ) AND value_client_information_10_civireport.veteran__62=1    group by value_focal_issue_72_civireport.focal_issue_306


Information Only
SELECT
sum((case month(case_civireport.start_date) when 7 then 1 else 0 end)) AS `Jul`,sum((case month(case_civireport.start_date) when 8 then 1 else 0 end)) AS `Aug`,sum((case month(case_civireport.start_date) when 9 then 1 else 0 end)) AS `Sep`,sum((case month(case_civireport.start_date) when 10 then 1 else 0 end)) AS `Oct`,sum((case month(case_civireport.start_date) when 11 then 1 else 0 end)) AS `Nov`,sum((case month(case_civireport.start_date) when 12 then 1 else 0 end)) AS `December`,sum((case month(case_civireport.start_date) when 1 then 1 else 0 end)) AS `Jan`,sum((case month(case_civireport.start_date) when 2 then 1 else 0 end)) AS `Feb`,sum((case month(case_civireport.start_date) when 3 then 1 else 0 end)) AS `March`,
sum((case month(case_civireport.start_date) when 4 then 1 else 0 end)) AS `Apr`,
sum((case month(case_civireport.start_date) when 5 then 1 else 0 end)) AS `May`,
sum((case month(case_civireport.start_date) when 6 then 1 else 0 end)) AS `Jun`,count(case_civireport.start_date) AS `TTL` 
FROM civicrm_contact contact_civireport
            LEFT JOIN civicrm_case_contact ccc ON ccc.contact_id = contact_civireport.id
            LEFT JOIN civicrm_case case_civireport ON case_civireport.id = ccc.case_id 
            LEFT JOIN civicrm_case_activity cca ON cca.case_id = case_civireport.id
            LEFT JOIN civicrm_activity activity_civireport ON (activity_civireport.id = cca.activity_id AND activity_civireport.is_current_revision=1) 
         LEFT JOIN civicrm_value_additional_info_12 value_additional_info_12_civireport ON value_additional_info_12_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_caller_information_8 value_caller_information_8_civireport ON value_caller_information_8_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_case_events_4 value_case_events_4_civireport ON value_case_events_4_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_client_information_10 value_client_information_10_civireport ON value_client_information_10_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_client_profile_67 value_client_profile_67_civireport ON value_client_profile_67_civireport.entity_id = contact_civireport.id LEFT JOIN civicrm_value_crisis_plan_13 value_crisis_plan_13_civireport ON value_crisis_plan_13_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_crisisassessment_2 value_crisisassessment_2_civireport ON value_crisisassessment_2_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_crs_action_3 value_crs_action_3_civireport ON value_crs_action_3_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_emergency_contact_7 value_emergency_contact_7_civireport ON value_emergency_contact_7_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_emergency_severity_6 value_emergency_severity_6_civireport ON value_emergency_severity_6_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_financial_9 value_financial_9_civireport ON value_financial_9_civireport.entity_id = activity_civireport.id 
LEFT JOIN civicrm_value_insurance_30 value_insurance_30_civireport ON value_insurance_30_civireport.entity_id = activity_civireport.id
LEFT JOIN civicrm_value_linkage_14 value_linkage_14_civireport ON value_linkage_14_civireport.entity_id = activity_civireport.id
LEFT JOIN civicrm_value_provider_pcp_32 value_provider_pcp_32_civireport ON value_provider_pcp_32_civireport.entity_id = activity_civireport.id LEFT JOIN civicrm_value_referral_source_31 value_referral_source_31_civireport ON value_referral_source_31_civireport.entity_id = activity_civireport.id
LEFT JOIN civicrm_value_release_of_information_11 value_release_of_information_11_civireport ON value_release_of_information_11_civireport.entity_id = activity_civireport.id
LEFT JOIN civicrm_value_risk_factors_5 value_risk_factors_5_civireport ON value_risk_factors_5_civireport.entity_id = activity_civireport.id
LEFT JOIN civicrm_value_five_axis_diagnosis_27 value_five_axis_diagnosis_27_civireport ON value_five_axis_diagnosis_27_civireport.entity_id = activity_civireport.id
LEFT JOIN civicrm_value_mct_dispatch_details_127 value_mct_dispatch_details_127_civireport ON value_mct_dispatch_details_127_civireport.entity_id = activity_civireport.id
LEFT JOIN civicrm_value_mct_dispatch_notes_128 value_mct_dispatch_notes_128_civireport ON value_mct_dispatch_notes_128_civireport.entity_id = activity_civireport.id
LEFT JOIN civicrm_value_mct_dispatch_selections_129 value_mct_dispatch_selections_129_civireport ON value_mct_dispatch_selections_129_civireport.entity_id = activity_civireport.id
LEFT JOIN civicrm_value_mct_focal_issues_130 value_mct_focal_issues_130_civireport ON value_mct_focal_issues_130_civireport.entity_id = activity_civireport.id
WHERE ( contact_civireport.contact_type = 'Individual' ) AND value_client_information_10_civireport.veteran__62=1 and value_crs_action_3_civireport.information_only_16=1

Crisis Plan
 
