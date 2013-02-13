<?php
/**
 * Tine 2.0
 *
 * @package     Addressbook
 * @subpackage  Frontend
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Json.php 17291 2010-11-23 22:07:42Z p.schuele@metaways.de $
 *
 * @todo        use functions from Tinebase_Frontend_Json_Abstract
 *              -> get/save/getAll
 * @todo        remove deprecated functions afterwards
 */

/**
 * backend class for Zend_Json_Server
 *
 * This class handles all Json requests for the addressbook application
 *
 * @package     Addressbook
 * @subpackage  Frontend
 * @todo        handle timezone management
 */
class Addressbook_Frontend_Json extends Tinebase_Frontend_Json_Abstract
{
    /**
     * app name
     * 
     * @var string
     */
    protected $_applicationName = 'Addressbook';
    
    /**
     * user fields (created_by, ...) to resolve in _multipleRecordsToJson and _recordToJson
     *
     * @var array
     */
    protected $_resolveUserFields = array(
        'Addressbook_Model_Contact' => array('created_by', 'last_modified_by')
    );
    
    /****************************************** get contacts *************************************/

    /**
     * get one contact identified by contactId
     *
     * @param int $id
     * @return array
     */
    public function getContact($id)
    {
        $result = array();
               
        $contact = Addressbook_Controller_Contact::getInstance()->get($id);
        $result = $this->_contactToJson($contact);
        
        return $result;
    }
    
    /**
     * Search for contacts matching given arguments
     *
     * @param  array $filter
     * @param  array $paging
     * @return array
     */
    public function searchContacts($filter, $paging)
    {
        return $this->_search($filter, $paging, Addressbook_Controller_Contact::getInstance(), 'Addressbook_Model_ContactFilter');
    }    

    /**
     * Search for lists matching given arguments
     *
     * @param  array $filter
     * @param  array $paging
     * @return array
     */
    public function searchLists($filter, $paging)
    {
        return $this->_search($filter, $paging, Addressbook_Controller_List::getInstance(), 'Addressbook_Model_ListFilter');
    }    

    /****************************************** save / delete / import contacts ****************************/
    
    /**
     * delete multiple contacts
     *
     * @param array $ids list of contactId's to delete
     * @return array
     */
    public function deleteContacts($ids)
    {
        return $this->_delete($ids, Addressbook_Controller_Contact::getInstance());
    }
    
    /**
     * save one contact
     *
     * if $recordData['id'] is empty the contact gets added, otherwise it gets updated
     *
     * @param  array $recordData an array of contact properties
     * @return array
     */
    public function saveContact($recordData)
    {
        $contact = new Addressbook_Model_Contact();
        $contact->setFromJsonInUsersTimezone($recordData);
        
        if (empty($contact->id)) {
            $contact = Addressbook_Controller_Contact::getInstance()->create($contact);
        } else {
            $contact = Addressbook_Controller_Contact::getInstance()->update($contact);
        }
        
        $result =  $this->getContact($contact->getId());
        return $result;
    }
    
    /**
     * import contacts
     * 
     * @param array $files to import
     * @param array $importOptions
     * @param string $definitionId
     * @return array
     */
    public function importContacts($files, $importOptions, $definitionId)
    {
        return $this->_import($files, $definitionId, $importOptions);
    }
    
    /****************************************** get default adb ****************************/
    
    /**
     * get default addressbook
     * 
     * @return array
     */
    public function getDefaultAddressbook()
    {
        $defaultAddressbook = Addressbook_Controller_Contact::getInstance()->getDefaultAddressbook();
        $defaultAddressbookArray = $defaultAddressbook->toArray();
        $defaultAddressbookArray['account_grants'] = Tinebase_Container::getInstance()->getGrantsOfAccount(Tinebase_Core::getUser(), $defaultAddressbook->getId())->toArray();
        
        return $defaultAddressbookArray;
    }
    
    /****************************************** get salutations ****************************/
    
    /**
     * get salutations
     *
     * @return array
     * @todo   use _getAll() from Tinebase_Frontend_Json_Abstract
     */
   public function getSalutations()
    {
         $result = array(
            'results'     => array(),
            'totalcount'  => 0
        );
        
        if ($rows = Addressbook_Controller_Salutation::getInstance()->getSalutations()) {
            $rows->translate();
            $result['results']      = $rows->toArray();
            $result['totalcount']   = count($result['results']);
        }

        return $result;
    }  
    
    /****************************************** helper functions ***********************************/
    
    /**
     * returns multiple records prepared for json transport
     *
     * @param Tinebase_Record_RecordSet $_records Tinebase_Record_Abstract
     * @param Tinebase_Model_Filter_FilterGroup
     * @return array data
     */
    protected function _multipleRecordsToJson(Tinebase_Record_RecordSet $_records, $_filter=NULL)
    {
        $result = parent::_multipleRecordsToJson($_records, $_filter);
        
        foreach ($result as &$contact) {
            $contact['jpegphoto'] = $this->_getImageLink($contact);
        }
        
        return $result;
    }

    /**
     * returns contact prepared for json transport
     *
     * @param Addressbook_Model_Contact $_contact
     * @return array contact data
     */
    protected function _contactToJson($_contact)
    {   
        $result = parent::_recordToJson($_contact);
        $result['jpegphoto'] = $this->_getImageLink($result);
        
        return $result;
    }

    /**
     * returns a image link
     * 
     * @param  array $contactArray
     * @return string
     * 
     * @todo    get all available salutations first / do not query db for each record
     */
    protected function _getImageLink($contactArray)
    {
        if (! empty($contactArray['jpegphoto'])) {
            $link = 'index.php?method=Tinebase.getImage&application=Addressbook&location=&id=' . $contactArray['id'] . '&width=90&height=90&ratiomode=0';
        } else {
        	if (isset($contactArray['salutation_id']) && $contactArray['salutation_id']) {
        		$salutation = Addressbook_Controller_Salutation::getInstance()->getSalutation($contactArray['salutation_id'])->toArray();
				$link = $salutation['image_path'];	
				if (empty($link)) {
					$link = 'images/empty_photo_blank.png';
				}
        	}
        	else {        	
            	$link = 'images/empty_photo_blank.png';
        	}
        }
        
        return $link;
    }

    /**
     * Returns registry data of addressbook.
     * @see Tinebase_Application_Json_Abstract
     * 
     * @return mixed array 'variable name' => 'data'
     */
    public function getRegistryData()
    {
        $filter = new Tinebase_Model_ImportExportDefinitionFilter(array(
            array('field' => 'plugin', 'operator' => 'equals', 'value' => 'Addressbook_Import_Csv'),
        ));
        $importDefinitions = Tinebase_ImportExportDefinition::getInstance()->search($filter);
        try {
            $defaultDefinitionArray = Tinebase_ImportExportDefinition::getInstance()->getByName('adb_tine_import_csv')->toArray();
        } catch (Tinebase_Exception_NotFound $tenf) {
            if (count($importDefinitions) > 0) {
                $defaultDefinitionArray = $importDefinitions->getFirstRecord()->toArray();
            } else {
                Tinebase_Core::getLogger()->warn(__METHOD__ . '::' . __LINE__ . ' No import definitions found for Addressbook');
                $defaultDefinitionArray = array();
            }
        }
        
        $registryData = array(
            'Salutations'               => $this->getSalutations(),
            'defaultAddressbook'        => $this->getDefaultAddressbook(),
            'defaultImportDefinition'   => $defaultDefinitionArray,
            'importDefinitions'         => array(
                'results'               => $importDefinitions->toArray(),
                'totalcount'            => count($importDefinitions),
            ),
        );        
        return $registryData;    
    }
}
