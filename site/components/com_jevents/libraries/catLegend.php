<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: modView.php 1151 2008-08-20 13:48:44Z tstahl $
 * @package     JEvents
 * @copyright   Copyright (C) 2006-2008 JEvents Project Group
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://joomlacode.org/gf/project/jevents
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class catLegend {
	function catLegend($id, $name, $color, $description,$parent_id=0)
	{
		$this->id=$id;
		$this->name=$name;
		$this->color=$color;
		$this->description=$description;
		$this->parent_id=$parent_id;
	}
}
