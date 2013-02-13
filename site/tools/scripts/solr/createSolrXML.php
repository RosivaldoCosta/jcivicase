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

require_once '../../../civicrm.config.php';
require_once 'CRM/Core/Config.php';

define( 'CHUNK_SIZE', 128 );

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

/**
 * Given an array of values, generate the XML in the Solr format
 */
function &generateSolrXML( $values ) {
    $result = "<add>\n";
    foreach ( $values as $cid => $tokens ) {
        if ( empty( $tokens ) ) {
            continue;
        }

        $result .= 
            <<<EOT
  <doc>
    <field name="id">$cid</field>\n
EOT;

        foreach ( $tokens as $t ) {
            $result .= 
                <<<EOT
    <field name="$t[0]">$t[1]</field>\n
EOT;
        }

        $result .= "  </doc>\n";

    }
    $result .= "</add>\n";

    
    return $result;
}

/**
 * Given a set of contact IDs get the values
 */
function getValues( &$contactIDs, &$values ) {
    $values = array( );
    
    foreach ( $contactIDs as $cid ) {
        $values[$cid] = array( );
    }

    getContactInfo( $contactIDs, $values );
    getAddressInfo( $contactIDs, $values );
    getEmailInfo  ( $contactIDs, $values );
    getPhoneInfo  ( $contactIDs, $values );
    // getIMInfo     ( $contactIDs, $values );
    getCaseInfo   ( $contactIDs, $values );
    getCaseActivityInfo($contactIDs, $values);    
    return $values;
}

function getTableInfo( &$contactIDs, &$values, $tableName, &$fields, $whereField, $additionalWhereCond = null ) {
    $selectString = implode( ',', array_keys( $fields ) );
    $idString     = implode( ',', $contactIDs );

    $sql = "
SELECT $selectString, $whereField as contact_id
  FROM $tableName
 WHERE $whereField IN ( $idString )
";
    
    if ( $additionalWhereCond ) {
        $sql .= " AND $additionalWhereCond";
    }

    $dao =& CRM_Core_DAO::executeQuery( $sql );
    while ( $dao->fetch( ) ) {
        foreach ( $fields as $fld => $name ) {
            if ( empty( $dao->$fld ) ) {
                continue;
            }
            if ( ! $name ) {
                $name = $fld;
            }
            $values[$dao->contact_id][] = array( $name, $dao->$fld ); 
        }
    }
}

function getContactInfo( &$contactIDs, &$values ) {
    $fields = array( 'sort_name'           => null,
                     'display_name'        => null,
                     'contact_type'        => null,
                     'contact_sub_type'    => null,
                     'legal_identifier'    => null,
                     'external_identifier' => null,
                     'source'              => 'contact_source',
                     'first_name'          => null,
                     'last_name'           => null,
                     'middle_name'         => null,
                     'job_title'           => null,
                     'household_name'      => null,
                     'organization_name'   => null,
                     'legal_name'          => null,
                     'sic_code'            => null );
    getTableInfo( $contactIDs, $values, 'civicrm_contact', $fields, 'id' );


    $fields = array( 'note'    => 'note_body',
                     'subject' => 'note_subject' );
    getTableInfo( $contactIDs, $values, 'civicrm_note', $fields, 'entity_id', "entity_table = 'civicrm_contact'" );
}

function getLocationInfo( &$contactIDs, &$values ) {
    $ids = implode( ',', $contactIDs );

    $sql = "
SELECT
  c.id as contact_id,
  a.street_address, a.supplemental_address_1, a.supplemental_address_2,
  a.city, a.postal_code, 
  s.name as state, co.name as country,
  e.email, p.phone, i.name as im
FROM      civicrm_contact        c
LEFT JOIN civicrm_address        a  ON a.contact_id        = c.id
LEFT JOIN civicrm_email          e  ON e.contact_id        = c.id
LEFT JOIN civicrm_phone          p  ON p.contact_id        = c.id
LEFT JOIN civicrm_im             i  ON i.contact_id        = c.id
LEFT JOIN civicrm_state_province s  ON a.state_province_id = s.id
LEFT JOIN civicrm_country        co ON a.country_id        = co.id
WHERE c.id IN ( $ids )
";

    $fields = array( 'location_name', 
                     'street_address',
                     'supplemental_address_1',
                     'supplemental_address_2',
                     'city',
                     'postal_code',
                     'county',
                     'state',
                     'country',
                     'email',
                     'phone',
                     'im' );
    $dao =& CRM_Core_DAO::executeQuery( $sql );
    while ( $dao->fetch( ) ) {
        foreach ( $fields as $fld ) {
            if ( empty( $dao->$fld ) ) {
                continue;
            }
            $values[$dao->contact_id][] = array( $fld, $dao->$fld );
        }
    }
}

function getSQLInfo( &$values, &$sql, &$fields, $usePrefix = false ) {
    $dao = CRM_Core_DAO::executeQuery( $sql );

    while ( $dao->fetch( ) ) {
        foreach ( $fields as $fld => $name ) {
            if ( empty( $dao->$fld ) ) {
                continue;
            }

            if ( ! $name ) {
                $name = $fld;
            }

            if ( $usePrefix ) {
                $name = "{$dao->prefix}_{$name}";
            }

            $values[$dao->contact_id][] = array( $name, $dao->$fld );
        }
    }
}

function getAddressInfo( &$contactIDs, &$values ) {
    $ids = implode( ',', $contactIDs );

    $sql = "
SELECT
  a.contact_id as contact_id,
  a.street_address, a.supplemental_address_1, a.supplemental_address_2,
  a.city, a.postal_code,
  s.name as state, co.name as country,
  lt.name as address_location_type
FROM       civicrm_address        a
LEFT JOIN  civicrm_location_type  lt ON lt.id = a.location_type_id
LEFT JOIN  civicrm_state_province s  ON a.state_province_id = s.id
LEFT JOIN  civicrm_country        co ON a.country_id        = co.id
WHERE a.contact_id IN ( $ids )
";

    $fields = array( 'address_location_type'   => null, 
                     'street_address'          => null, 
                     'supplemental_address_1'  => null,
                     'supplemental_address_2'  => null,
                     'city'                    => null, 
                     'postal_code'             => null, 
                     'state'                   => null, 
                     'country'                 => null,
                     );

    getSQLInfo( $values, $sql, $fields );
}

function getEmailInfo( &$contactIDs, &$values ) {
    $ids = implode( ',', $contactIDs );

    $sql = "
SELECT
  e.contact_id as contact_id,
  e.email,
  lt.name as email_location_type
FROM       civicrm_email          e
LEFT JOIN  civicrm_location_type  lt ON lt.id = e.location_type_id
WHERE e.contact_id IN ( $ids )
";

    $fields = array( 'email_location_type' => null, 
                     'email'               => null,
                     );

    getSQLInfo( $values, $sql, $fields );
}

function getPhoneInfo( &$contactIDs, &$values ) {
    $ids = implode( ',', $contactIDs );

    $sql = "
SELECT
  p.contact_id as contact_id,
  p.phone,
  lt.name as phone_location_type
FROM       civicrm_phone          p
LEFT JOIN  civicrm_location_type  lt ON lt.id = p.location_type_id
WHERE p.contact_id IN ( $ids )
";

    $fields = array( 'phone_location_type' => null, 
                     'phone'               => null,
                     );

    getSQLInfo( $values, $sql, $fields );
}

function getCaseInfo( &$contactIDs, &$values ) {
    $ids = implode( ',', $contactIDs );

    $sql = "
SELECT
  ccon.contact_id as contact_id,
  v1.label        as case_type,
  cs.start_date   as case_start_date,
  v2.label        as case_status

FROM    civicrm_case_contact      ccon
INNER JOIN  civicrm_case          cs   ON ccon.case_id = cs.id
INNER JOIN  civicrm_option_value  v1   ON v1.option_group_id = (SELECT g.id FROM civicrm_option_group g WHERE g.name = 'case_type') AND REPLACE(cs.case_type_id, '" . CRM_Core_DAO::VALUE_SEPARATOR . "', '') = v1.value
INNER JOIN  civicrm_option_value  v2   ON v2.option_group_id = (SELECT g.id FROM civicrm_option_group g WHERE g.name = 'case_status') AND cs.status_id = v2.value
WHERE ccon.contact_id IN ( $ids )
";

    $fields = array( 'case_type'       => null, 
                     'case_start_date' => null,
                     'case_status'  => null,
                     );

    getSQLInfo( $values, $sql, $fields );
}

function getCaseActivityInfo( &$contactIDs, &$values ) {
    $ids = implode( ',', $contactIDs );

    $sql = "
SELECT
  ccon.contact_id   as contact_id,
  v.label           as case_activity_type,
  act.activity_date_time as case_activity_date_time,
  act.subject       as case_activity_subject

FROM    civicrm_case_contact      ccon
INNER JOIN  civicrm_case          cs   ON ccon.case_id = cs.id
INNER JOIN  civicrm_case_activity cact ON cs.id = cact.case_id
INNER JOIN  civicrm_activity      act  ON cact.activity_id = act.id AND act.is_current_revision = 1
INNER JOIN  civicrm_option_value  v    ON act.activity_type_id = v.value AND 
            v.option_group_id = (SELECT g.id FROM civicrm_option_group g WHERE g.name = 'activity_type')
WHERE ccon.contact_id IN ( $ids )
ORDER BY act.activity_date_time
";

    $fields = array( 'case_activity_type'   => null,
                     'case_activity_date_time' => null,
                     'case_activity_subject' => null,
                     );

    getSQLInfo( $values, $sql, $fields );
}

function run( & $contactIDs ) {
    $chunks =& splitContactIDs( $contactIDs );

    foreach ( $chunks as $chunk ) {
        $values = array( );
        getValues( $chunk, $values );
        $xml =& generateSolrXML( $values );
        echo $xml;
    }

}

$config =& CRM_Core_Config::singleton();

$sql = "
SELECT id 
FROM civicrm_contact
LIMIT 50;
";

$dao =& CRM_Core_DAO::executeQuery( $sql );

$contactIDs = array( );
while ( $dao->fetch( ) ) {
    $contactIDs[] = $dao->id;
}

run( $contactIDs );
