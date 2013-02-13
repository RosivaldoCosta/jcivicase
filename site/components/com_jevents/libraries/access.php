<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: helper.php 1149 2008-08-19 16:58:34Z tstahl $
 * @package     JEvents
 * @copyright   Copyright (C) 2006-2008 JEvents Project Group
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://joomlacode.org/gf/project/jevents
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class JEVAccess {
	var $access;

	function JEVAccess(){
		// Editor usertype check
		global $acl;
		$user =& JFactory::getUser();

		$this->access = new stdClass();
		$acl =& JFactory::getACL();
		$this->access->canEdit	= $acl->acl_check( 'action', 'edit', 'users', $user->usertype, 'content', 'all' );
		$this->access->canEditOwn = $acl->acl_check( 'action', 'edit', 'users', $user->usertype, 'content', 'own' );
		$this->access->canPublish = $acl->acl_check( 'action', 'publish', 'users', $user->usertype, 'content', 'all' );
	}

	function canEdit(){
		return $this->access->canEdit;
	}

	function canEditOwn(){
		return $this->access->canEditOwn;
	}

	function canPublish(){
		return $this->access->canPublish;
	}

}
