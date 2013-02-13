<?php
/**
 * Tine 2.0
 *
 * @package     Addressbook
 * @subpackage  Frontend
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 * @copyright   Copyright (c) 2007-2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Http.php 17997 2010-12-21 21:27:22Z c.weiss@metaways.de $
 */

/**
 * Addressbook http frontend class
 *
 * This class handles all Http requests for the addressbook application
 *
 * @package     Addressbook
 * @subpackage  Frontend
 */
class Addressbook_Frontend_Http extends Tinebase_Frontend_Http_Abstract
{
    /**
     * app name
     *
     * @var string
     */
    protected $_applicationName = 'Addressbook';
    
    /**
     * export contact
     * 
     * @param string $filter JSON encoded string with contact ids for multi export or contact filter
     * @param string $options format or export definition id
     */
    public function exportContacts($filter, $options)
    {
        $decodedFilter = Zend_Json::decode($filter);
        if (! is_array($decodedFilter)) {
            $decodedFilter = array(array('field' => 'id', 'operator' => 'equals', 'value' => $decodedFilter));
        }
        
        $filter = new Addressbook_Model_ContactFilter($decodedFilter);
        parent::_export($filter, Zend_Json::decode($options), Addressbook_Controller_Contact::getInstance());
    }
    
    /**
     * Returns all JS files which must be included for Addressbook
     * 
     * @return array array of filenames
     */
    public function getJsFilesToInclude ()
    {
        return array(
            'Addressbook/js/Model.js',
            'Addressbook/js/Addressbook.js',
            'Addressbook/js/ContactGridDetailsPanel.js',
            'Addressbook/js/ContactGrid.js',
            'Addressbook/js/ContactFilterModel.js',
            'Addressbook/js/ContactEditDialog.js',
            'Addressbook/js/SearchCombo.js',
        );
    }
}
