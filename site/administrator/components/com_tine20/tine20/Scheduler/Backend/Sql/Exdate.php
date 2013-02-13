<?php
/**
 * Sql Scheduler
 * 
 * @package     Scheduler 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Cornelius Weiss <c.weiss@metaways.de>
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Exdate.php 7717 2009-04-15 18:45:13Z c.weiss@metaways.de $
 */

/**
 * native tine 2.0 events sql backend exdate class
 *
 * @package Calendar
 */
class Scheduler_Backend_Sql_Exdate extends Tinebase_Backend_Sql_Abstract
{
    /**
     * appointment foreign key column
     */
    const FOREIGNKEY_EVENT = 'id_requests';
    
    /**
     * Table name without prefix
     *
     * @var string
     */
    protected $_tableName = 'jos_sv_apptpro2_requests';
    
    /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = 'Calendar_Model_Appointment';

}
