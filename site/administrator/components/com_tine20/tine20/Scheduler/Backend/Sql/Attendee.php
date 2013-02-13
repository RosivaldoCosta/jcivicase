<?php
/**
 * Sql Scheduler 
 * 
 * @package     Scheduler
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Attendee.php 8432 2009-06-09 09:14:15Z c.weiss@metaways.de $
 */

/**
 * native tine 2.0 events sql backend attendee class
 *
 * @package Scheduler
 */
class Scheduler_Backend_Sql_Attendee extends Tinebase_Backend_Sql_Abstract
{
    /**
     * appointment foreign key column
     */
    const FOREIGNKEY_APPOINTMENT = 'id';
    
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'civicrm_contact';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Scheduler_Model_Attender';
    
}
