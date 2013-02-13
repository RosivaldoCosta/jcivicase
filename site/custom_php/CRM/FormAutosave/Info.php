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

require_once 'CRM/Core/Component/Info.php';

/**
 * This class introduces component to the system and provides all the 
 * information about it. It needs to extend CRM_Core_Component_Info
 * abstract class.
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2009
 * $Id$
 *
 */
class CRM_FormAutosave_Info extends CRM_Core_Component_Info
{

    // docs inherited from interface
    protected $keyword = 'FormAutosave';

    // docs inherited from interface
    public function getInfo()
    {
        return array( 'name'	             => 'CiviFormAutosave',
                      'translatedName'       => ts('CiviFormAutosave'),
                      'title'                => ts('CiviCRM FormAutosave Component'),
                      'search'               => 0,
                      'showActivitiesInCore' => 0 
                      );
    }


    // docs inherited from interface
    public function getPermissions()
    {
        return array( 'access CiviFormAutosave',
                      'edit FormAutosave',
                      'delete in FormAutosave' );
    }

    // docs inherited from interface
    public function getUserDashboardElement()
    {
        return array( 'name'    => ts( 'FormAutosave' ),
                      'title'   => ts( 'Your FormAutosave(s)' ),
                      // we need to check this permission since you can click on contribution page link for making payment
                      'perm'    => array( 'make online contributions' ), 
                      'weight'  => 16 );
    }

    // docs inherited from interface  
    public function registerTab()
    {
        return array( 'title'   => ts( 'FormAutosave' ),
                      'url'	    => 'form-autosave',
                      'weight'  => 26 );
    }

    // docs inherited from interface  
    public function registerAdvancedSearchPane()
    {
        return array( 'title'   => ts( 'FormAutosave' ),
                      'weight'  => 26 );
    }
    
    // docs inherited from interface    
    public function getActivityTypes()
    {
        return null;
    }

	// add shortcut to Create New
    public function creatNewShortcut( &$shortCuts ) { }

}
