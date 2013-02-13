<?php

require_once 'CiviTest/CiviUnitTestCase.php';
require_once 'CRM/Contact/BAO/ContactType.php';
require_once 'api/v2/Contact.php';

class api_v2_ContactTypeTest extends CiviUnitTestCase
{
    
    function setUp()
    {
        parent::setUp();
        
        $params = array( 'label'     => 'sub_individual',
                         'name'      => 'sub_individual',
                         'parent_id' => 1,//Individual
                         'is_active' => 1
                         );
        $result = CRM_Contact_BAO_ContactType::add( $params );
        $this->subTypeIndividual = $params['name'];  
        
        $params = array( 'label'     => 'sub_organization',
                         'name'      => 'sub_organization',
                         'parent_id' => 3,//Organization
                         'is_active' => 1
                         );
        $result = CRM_Contact_BAO_ContactType::add( $params );
        $this->subTypeOrganization = $params['name'];
        
        $params = array( 'label'     => 'sub_household',
                         'name'      => 'sub_household',
                         'parent_id' => 2,//Household
                         'is_active' => 1
                         );
        $result = CRM_Contact_BAO_ContactType::add( $params );
        $this->subTypeHousehold = $params['name'];
        
    }
   
    /*
     * test add methods with valid data
     * success expected
     */
    function testContactAdd() {
        
        // check for Type:Individual Subtype:sub_individual
        $contactParams = array(
                               'first_name'       => 'Anne',
                               'last_name'        => 'Grant',
                               'contact_type'     => 'Individual',
                               'contact_sub_type' => $this->subTypeIndividual
                               );  
        $contact =& civicrm_contact_add( $contactParams );
        $this->assertEquals( $contact['is_error'], 0, "In line " . __LINE__ );
        
        $params      = array( 'contact_id' => $contact['contact_id'] );
        $getContacts = civicrm_contact_get( $params );
        $result      = $getContacts[$contact['contact_id']];
        $this->assertEquals( $result['first_name'], $contactParams['first_name'], "In line " . __LINE__ );
        $this->assertEquals( $result['last_name'], $contactParams['last_name'], "In line " . __LINE__ );
        $this->assertEquals( $result['contact_type'], $contactParams['contact_type'], "In line " . __LINE__ );
        $this->assertEquals( $result['contact_sub_type'], $contactParams['contact_sub_type'], "In line " . __LINE__ );
        civicrm_contact_delete( $params );

        // check for Type:Organization Subtype:sub_organization
        $contactParams = array( 
                               'organization_name' => 'Compumentor' ,     
                               'contact_type'      => 'Organization',
                               'contact_sub_type'  => $this->subTypeOrganization 
                                );
        $contact =& civicrm_contact_add( $contactParams );
        $this->assertEquals( $contact['is_error'], 0, "In line " . __LINE__ );
        
        $params      = array( 'contact_id' => $contact['contact_id'] );
        $getContacts = civicrm_contact_get( $params );
        $result      = $getContacts[$contact['contact_id']];
        $this->assertEquals( $result['organization_name'], $contactParams['organization_name'], "In line " . __LINE__ );
        $this->assertEquals( $result['contact_type'], $contactParams['contact_type'], "In line " . __LINE__ );
        $this->assertEquals( $result['contact_sub_type'], $contactParams['contact_sub_type'], "In line " . __LINE__ ); 
        civicrm_contact_delete( $params ); 
    }
    
    /*
     * test add with invalid data
     */
    function testContactAddInvalidData() {
        
        // check for Type:Individual Subtype:sub_household
        $contactParams = array(
                               'first_name'       => 'Anne',
                               'last_name'        => 'Grant',
                               'contact_type'     => 'Individual',
                               'contact_sub_type' => $this->subTypeHousehold
                               );  
        $contact =& civicrm_contact_add( $contactParams );
        $this->assertEquals( $contact['is_error'], 1, "In line " . __LINE__ );
        
        // check for Type:Organization Subtype:sub_individual
        $contactParams = array( 
                               'organization_name' => 'Compumentor' ,     
                               'contact_type'      => 'Organization',
                               'contact_sub_type'  => $this->subTypeIndividual 
                                );
        $contact =& civicrm_contact_add( $contactParams );
        $this->assertEquals( $contact['is_error'], 1, "In line " . __LINE__ );
        
    }

    
    /*
     * test update with no subtype to valid subtype
     * success expected
     */ 

    function testContactUpdateNoSubtypeValid() {
        
        // check for Type:Individual 
        $contactParams = array(
                               'first_name'       => 'Anne',
                               'last_name'        => 'Grant',
                               'contact_type'     => 'Individual',
                               );  
        $contact =& civicrm_contact_add( $contactParams );
        // subype:sub_individual
        $updateParams = array(
                              'first_name'        => 'John',
                              'last_name'         => 'Grant',
                              'contact_id'        => $contact['contact_id'],
                              'contact_type'      => 'Individual',
                              'contact_sub_type'  => $this->subTypeIndividual 
                              );
        $updateContact =& civicrm_contact_add( $updateParams );

        $this->assertEquals( $updateContact['is_error'], 0, "In line " . __LINE__ );
        $this->assertEquals( $updateContact['contact_id'], $contact['contact_id'], "In line " . __LINE__ );

        $params = array( 'contact_id' => $contact['contact_id'] );
        $getContacts = civicrm_contact_get( $params );
        $result      = $getContacts[$contact['contact_id']];
        
        $this->assertEquals( $result['first_name'], $updateParams['first_name'], "In line " . __LINE__ );
        $this->assertEquals( $result['last_name'], $updateParams['last_name'], "In line " . __LINE__ );
        $this->assertEquals( $result['contact_type'], $updateParams['contact_type'], "In line " . __LINE__ );
        $this->assertEquals( $result['contact_sub_type'], $updateParams['contact_sub_type'], "In line " . __LINE__ );
        civicrm_contact_delete( $params ); 

        // check for Type:Organization
        $contactParams = array(
                               'organization_name' => 'Compumentor' ,     
                               'contact_type'      => 'Organization'
                               );  
        $contact =& civicrm_contact_add( $contactParams );
        
        // subype:sub_organization
        $updateParams = array(
                              'organization_name' => 'Intel Arts' , 
                              'contact_id'        => $contact['contact_id'],
                              'contact_type'      => 'Organization',
                              'contact_sub_type'  => $this->subTypeOrganization 
                              );
        $updateContact =& civicrm_contact_add( $updateParams );

        $this->assertEquals( $updateContact['is_error'], 0, "In line " . __LINE__ );
        $this->assertEquals( $updateContact['contact_id'], $contact['contact_id'], "In line " . __LINE__ );

        $params = array( 'contact_id' => $contact['contact_id'] );
        $getContacts = civicrm_contact_get( $params );
        $result      = $getContacts[$contact['contact_id']];
        
        $this->assertEquals( $result['organization_name'], $updateParams['organization_name'], "In line " . __LINE__ );
        $this->assertEquals( $result['contact_type'], $updateParams['contact_type'], "In line " . __LINE__ );
        $this->assertEquals( $result['contact_sub_type'], $updateParams['contact_sub_type'], "In line " . __LINE__ );
        civicrm_contact_delete( $params ); 
    }

    
    /*
     * test update with no subtype to invalid subtype
     */ 
    function testContactUpdateNoSubtypeInvalid() {
        
        // check for Type:Individual 
        $contactParams = array(
                               'first_name'       => 'Anne',
                               'last_name'        => 'Grant',
                               'contact_type'     => 'Individual',
                               );  
        $contact =& civicrm_contact_add( $contactParams );

        // subype:sub_household
        $updateParams = array(
                              'first_name'        => 'John',
                              'last_name'         => 'Grant',
                              'contact_id'        => $contact['contact_id'],
                              'contact_type'      => 'Individual',
                              'contact_sub_type'  => $this->subTypeHousehold 
                              );
        $updateContact =& civicrm_contact_add( $updateParams );

        $this->assertEquals( $updateContact['is_error'], 1, "In line " . __LINE__ );
        $params = array('contact_id' => $contact['contact_id'] );
        civicrm_contact_delete( $params ); 

        // check for Type:Organization
        $contactParams = array(
                               'organization_name' => 'Compumentor' ,     
                               'contact_type'      => 'Organization',
                               );  
        $contact =& civicrm_contact_add( $contactParams );
        
        $updateParams = array(
                              'organization_name' => 'Intel Arts' , 
                              'contact_id'        => $contact['contact_id'],
                              'contact_type'      => 'Organization',
                              'contact_sub_type'  => $this->subTypeIndividual 
                              );
        $updateContact =& civicrm_contact_add( $updateParams );

        $this->assertEquals( $updateContact['is_error'], 1, "In line " . __LINE__ );
        $params = array('contact_id' => $contact['contact_id'] );
        civicrm_contact_delete( $params ); 
    }

    /*
     * test update with no subtype to valid subtype
     * success expected
     */ 
    function testContactUpdateSubtypeValid() {

        $params = array( 'label'     => 'sub2_individual',
                         'name'      => 'sub2_individual',
                         'parent_id' => 1,//Individual
                         'is_active' => 1
                         );
        $getSubtype = CRM_Contact_BAO_ContactType::add( $params );
        $subtype    = $params['name'];
 
        // check for Type:Individual subype:sub_individual
        $contactParams = array(
                               'first_name'       => 'Anne',
                               'last_name'        => 'Grant',
                               'contact_type'     => 'Individual',
                               'contact_sub_type' => $this->subTypeIndividual
                               );  
        $contact =& civicrm_contact_add( $contactParams );
        // subype:sub2_individual
        $updateParams = array(
                              'first_name'        => 'John',
                              'last_name'         => 'Grant',
                              'contact_id'        => $contact['contact_id'],
                              'contact_type'      => 'Individual',
                              'contact_sub_type'  => $subtype
                              );
        $updateContact =& civicrm_contact_add( $updateParams );
     
        $this->assertEquals( $updateContact['is_error'], 0, "In line " . __LINE__ );
        $this->assertEquals( $updateContact['contact_id'], $contact['contact_id'], "In line " . __LINE__ );

        $params = array( 'contact_id' => $contact['contact_id'] );
        $getContacts = civicrm_contact_get( $params );
        $result      = $getContacts[$contact['contact_id']];
        
        $this->assertEquals( $result['first_name'], $updateParams['first_name'], "In line " . __LINE__ );
        $this->assertEquals( $result['last_name'], $updateParams['last_name'], "In line " . __LINE__ );
        $this->assertEquals( $result['contact_type'], $updateParams['contact_type'], "In line " . __LINE__ );
        $this->assertEquals( $result['contact_sub_type'], $updateParams['contact_sub_type'], "In line " . __LINE__ );
        civicrm_contact_delete( $params ); 


        $params = array( 'label'     => 'sub2_organization',
                         'name'      => 'sub2_organization',
                         'parent_id' => 3,//Organization
                         'is_active' => 1
                         );
        $getSubtype = CRM_Contact_BAO_ContactType::add( $params );
        $subtype    = $params['name'];  
        
        // check for Type:Organization subype:sub_organization
        $contactParams = array(
                               'organization_name' => 'Compumentor' ,     
                               'contact_type'      => 'Organization',
                               'contact_sub_type'  => $this->subTypeOrganization 
                               );  
        $contact =& civicrm_contact_add( $contactParams );
        
        // subype:sub2_organization
        $updateParams = array(
                              'organization_name' => 'Intel Arts' , 
                              'contact_id'        => $contact['contact_id'],
                              'contact_type'      => 'Organization',
                              'contact_sub_type'  => $subtype
                              );
        $updateContact =& civicrm_contact_add( $updateParams );
        
        $this->assertEquals( $updateContact['is_error'], 0, "In line " . __LINE__ );
        $this->assertEquals( $updateContact['contact_id'], $contact['contact_id'], "In line " . __LINE__ );
        
        $params = array( 'contact_id' => $contact['contact_id'] );
        $getContacts = civicrm_contact_get( $params );
        $result      = $getContacts[$contact['contact_id']];
        
        $this->assertEquals( $result['organization_name'], $updateParams['organization_name'], "In line " . __LINE__ );
        $this->assertEquals( $result['contact_type'], $updateParams['contact_type'], "In line " . __LINE__ );
        $this->assertEquals( $result['contact_sub_type'], $updateParams['contact_sub_type'], "In line " . __LINE__ );
        civicrm_contact_delete( $params ); 
    }
  
    /*
     * test update with no subtype to invalid subtype
     */ 
    function testContactUpdateSubtypeInvalid() {
        
        // check for Type:Individual subtype:sub_individual
        $contactParams = array(
                               'first_name'       => 'Anne',
                               'last_name'        => 'Grant',
                               'contact_type'     => 'Individual',
                               'contact_sub_type' => $this->subTypeIndividual
                               );  
        $contact =& civicrm_contact_add( $contactParams );
        
        // subype:sub_household
        $updateParams = array(
                              'first_name'        => 'John',
                              'last_name'         => 'Grant',
                              'contact_id'        => $contact['contact_id'],
                              'contact_type'      => 'Individual',
                              'contact_sub_type'  => $this->subTypeHousehold 
                              );
        $updateContact =& civicrm_contact_add( $updateParams );

        $this->assertEquals( $updateContact['is_error'], 1, "In line " . __LINE__ );
        $params = array('contact_id' => $contact['contact_id'] );
        civicrm_contact_delete( $params ); 

        // check for Type:Organization subtype:
        $contactParams = array(
                               'organization_name' => 'Compumentor' ,     
                               'contact_type'      => 'Organization',
                               'contact_sub_type'  => $this->subTypeOrganization 
                               );  
        $contact =& civicrm_contact_add( $contactParams );
        
        $updateParams = array(
                              'organization_name' => 'Intel Arts' , 
                              'contact_id'        => $contact['contact_id'],
                              'contact_sub_type'  => $this->subTypeIndividual
                              );
        $updateContact =& civicrm_contact_add( $updateParams );

        $this->assertEquals( $updateContact['is_error'], 1, "In line " . __LINE__ );
        $params = array('contact_id' => $contact['contact_id'] );
        civicrm_contact_delete( $params ); 
    }  
    
}