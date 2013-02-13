<?php
/**
 * Tine 2.0
 *
 * @package     Admin
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @copyright   Copyright (c) 2009 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 * @version     $Id: RemoveGroupMember.php 10346 2009-09-04 13:32:50Z l.kneschke@metaways.de $
 */

/**
 * event class for newly created group
 *
 * @package     Admin
 */
class Admin_Event_RemoveGroupMember extends Tinebase_Event_Abstract
{
    /**
     * the group id
     *
     * @var string
     */
    public $groupId;

    /**
     * the user id
     *
     * @var string
     */
    public $userId;
    
}
