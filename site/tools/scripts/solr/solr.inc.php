<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 3.1                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2009                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007.                                       |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License along with this program; if not, contact CiviCRM LLC       |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 * Create a xml file for a set of contact ID's in a format digestible 
 * by Solr
 */


/**
 * Split a large array of contactIDs into more manageable smaller chunks
 */
function &splitContactIDs( &$contactIDs ) {
    // contactIDs could be a real large array, so we split it up into
    // smaller chunks and then general xml for each chunk
    $chunks           = array( );
    $current          = 0;
    $chunks[$current] = array( );
    $count            = 0;

    foreach ( $contactIDs as $cid ) {
        $chunks[$current][] = $cid;
        $count++;

        if ( $count == CHUNK_SIZE ) {
            $current++;
            $chunks[$current] = array( );
            $count            = 0;
        }
    }
     
    if ( empty( $chunks[$current] ) ) {
        unset( $chunks[$current] );
    }

    return $chunks;
}

function getTableInfo( $primaryKey, &$contactIDs, &$values, $tableName, &$fields, $whereField, $additionalWhereCond = null, $entity='' , $subEntity='') {
    $selectString = '';

    //turns each field into an as 
    foreach ($fields as $sqlAlias=>$sqlField) {
	
	if ($sqlField == null) $sqlField=$sqlAlias;

	$selectString .= $sqlField . " as " . $sqlAlias . ", ";
    }
    $selectString = substr($selectString,0,-2);

    $idString     = implode( ',', $contactIDs );

    $sql = "
SELECT $selectString
  FROM $tableName
 WHERE $whereField IN ( $idString )
";
 
    if ( $additionalWhereCond ) {
        $sql .= " AND $additionalWhereCond";
    }
   
    $domain = $entity;
    if ($subEntity!='') $domain .= '.'.$subEntity;

    $dao =& CRM_Core_DAO::executeQuery( $sql );
    while ( $dao->fetch( ) ) {
	
	//set the id if this is the primary entity
        if ($subEntity=='') $values[$dao->contact_id][] = array( 'id', $entity.'.'.$dao->contact_id );
    
	//add the other fields
	foreach ( $fields as $name => $fld ) {
            
	    if ( empty( $dao->$name ) ) continue;

	    //for subEntities, don't repeate the primary entities key. wasteful
	    if ($subEntity!='' && $name==$primaryKey) continue;
	    
            $values[$dao->contact_id][] = array( $domain.'.'.$name, $dao->$name ); 
        }
    }
}

function getBaseContactValues( &$contactIDs, &$values ) {

    //fields are listed by output name => key - allows for different output keys with same value
    $fields = array( 'sort_name'           => null,
                     'contact_id'          => 'id',
                     'id'          	   => 'id',
                     'display_name'        => null,
                     'contact_type'        => null,
                     'contact_sub_type'    => null,
                     'legal_identifier'    => null,
                     'external_identifier' => null,
                     'contact_source'      => 'source',
                     'first_name'          => null,
                     'last_name'           => null,
                     'middle_name'         => null,
                     'job_title'           => null,
                     'household_name'      => null,
                     'organization_name'   => null,
                     'legal_name'          => null,
                     'sic_code'            => null );
    getTableInfo( 'contact_id', $contactIDs, $values, 'civicrm_contact', $fields, 'id', null, 'contact' );


    /**--------------------------------------------------
    i/* NOTES
    */

    $fields = array( 'body'    => 'note',
                     'contact_id' => 'entity_id',
                     'subject' => null );
    getTableInfo( 'contact_id', $contactIDs, $values, 'civicrm_note', $fields, 'entity_id', "entity_table = 'civicrm_contact'" , 'contact','note');


    /**--------------------------------------------------
    /* ADDRESSES
    */

    $fields = array( 'address_type'   	       => 'lt.name',
                     'contact_id'              => null,
                     'street_address'          => null,
                     'supplemental_address_1'  => null,
                     'supplemental_address_2'  => null,
                     'city'                    => null,
                     'postal_code'             => null,
                     'state'                   => 's.name',
                     'country'                 => 'co.name',
                     );

    $tables = 'civicrm_address a
LEFT JOIN  civicrm_location_type  lt ON lt.id = a.location_type_id
LEFT JOIN  civicrm_state_province s  ON a.state_province_id = s.id
LEFT JOIN  civicrm_country        co ON a.country_id        = co.id
';

    getTableInfo( 'contact_id', $contactIDs, $values, $tables, $fields, 'a.contact_id', null , 'contact','address');

    /**--------------------------------------------------
    /* EMAIL ADDRESSES 
    */

    $fields = array( 'email'                   => null,
                     'type'                    => 'lt.name',
                     'contact_id'              => null,
                     );

    $tables = '
 civicrm_email          e
LEFT JOIN  civicrm_location_type  lt ON lt.id = e.location_type_id
';

    getTableInfo( 'contact_id', $contactIDs, $values, $tables, $fields, 'e.contact_id', null , 'contact','email');


    /**--------------------------------------------------
    /* PHONE NUMBERS
    */

    $fields = array( 'type'                   => 'lt.name',
                     'phone'                    => null,
                     'contact_id'              => null,
                     );

    $tables = '
civicrm_phone          p
LEFT JOIN  civicrm_location_type  lt ON lt.id = p.location_type_id
';

    getTableInfo( 'contact_id', $contactIDs, $values, $tables, $fields, 'contact_id', null , 'contact','phone');

    /**--------------------------------------------------
    /* CASES
    */

    $fields = array( 'type'                   => 'v1.label',
                     'start_date'                    => null,
                     'contact_id'              => null,
                     'status'              => 'v2.label',
                     );

    $tables = "
 civicrm_case_contact      ccon
INNER JOIN  civicrm_case          cs   ON ccon.case_id = cs.id
INNER JOIN  civicrm_option_value  v1   ON v1.option_group_id = (SELECT g.id FROM civicrm_option_group g WHERE g.name = 'case_type') AND REPLACE(cs.case_type_id, '" . CRM_Core_DAO::VALUE_SEPARATOR . "', '') = v1.value
INNER JOIN  civicrm_option_value  v2   ON v2.option_group_id = (SELECT g.id FROM civicrm_option_group g WHERE g.name = 'case_status') AND cs.status_id = v2.value";

    getTableInfo( 'contact_id', $contactIDs, $values, $tables, $fields, 'contact_id', null , 'contact','case');

    /**--------------------------------------------------
    /* CASE ACTIVITIES
    */

    $fields = array( 'type'                   => 'v.label',
                     'date'                    => 'act.activity_date_time',
                     'subject'              => 'act.subject',
                     'details'              => 'act.details',
                     'contact_id'              => null,
                     );

    $tables = "
civicrm_case_contact      ccon
INNER JOIN  civicrm_case          cs   ON ccon.case_id = cs.id
INNER JOIN  civicrm_case_activity cact ON cs.id = cact.case_id
INNER JOIN  civicrm_activity      act  ON cact.activity_id = act.id AND act.is_current_revision = 1
INNER JOIN  civicrm_option_value  v    ON act.activity_type_id = v.value AND
            v.option_group_id = (SELECT g.id FROM civicrm_option_group g WHERE g.name = 'activity_type')
";
    getTableInfo( 'contact_id', $contactIDs, $values, $tables, $fields, 'contact_id', null , 'contact','case.activity');

    /**--------------------------------------------------
    /* ACTIVITIES
    */

    $fields = array( 'type'                   => 'v.label',
                     'date'                    => 'act.activity_date_time',
                     'subject'              => 'act.subject',
                     'details'              => 'act.details',
                     'contact_id'              => 'target_contact_id',
                     );

    $tables = "
civicrm_activity      act  
INNER JOIN  civicrm_option_value  v    ON act.activity_type_id = v.value AND
	act.is_current_revision = 1 AND
        v.option_group_id = (SELECT g.id FROM civicrm_option_group g WHERE g.name = 'activity_type')
LEFT JOIN civicrm_activity_target cat ON cat.activity_id=act.id";
$addWhereClause = 'act.id NOT IN (SELECT activity_id FROM civicrm_case_activity)';
    getTableInfo( 'contact_id', $contactIDs, $values, $tables, $fields, 'target_contact_id', $addWhereClause , 'contact','activity');
}

function getDetailedContactValues( &$contactIDs, &$values ) {

	getBaseContactValues($contactIDs, $values);

	//add details here	
}

//CASES to follow

