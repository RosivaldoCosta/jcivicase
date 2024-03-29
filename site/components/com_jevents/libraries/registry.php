<?php
/**
 * JEvents Component for Joomla 1.5.x
 *
 * @version     $Id: config.php 1117 2008-07-06 17:20:59Z tstahl $
 * @package     JEvents
 * @copyright   Copyright (C) 2006-2008 JEvents Project Group
 * @license     GNU/GPLv2, see http://www.gnu.org/licenses/gpl-2.0.html
 * @link        http://joomlacode.org/gf/project/jevents
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class JevRegistry extends JRegistry {
		
	function &getInstance($id, $namespace = 'default')
	{
		static $instances;

		if (!isset ($instances)) {
			$instances = array ();
		}

		if (empty ($instances[$id])) {
			$instances[$id] = new JevRegistry($namespace);
		}

		return $instances[$id];
	}

	function setReference($regpath, & $value)
	{
		// Explode the registry path into an array
		$nodes = explode('.', $regpath);

		// Get the namespace
		$count = count($nodes);

		if ($count < 2) {
			$namespace = $this->_defaultNameSpace;
		} else {
			$namespace = array_shift($nodes);
			$count--;
		}

		if (!isset($this->_registry[$namespace])) {
			$this->makeNameSpace($namespace);
		}

		$ns = & $this->_registry[$namespace]['data'];

		$pathNodes = $count - 1;

		if ($pathNodes < 0) {
			$pathNodes = 0;
		}

		for ($i = 0; $i < $pathNodes; $i ++)
		{
			// If any node along the registry path does not exist, create it
			if (!isset($ns->$nodes[$i])) {
				$ns->$nodes[$i] = new stdClass();
			}
			$ns =& $ns->$nodes[$i];
		}

		// Get the old value if exists so we can return it
		$ns->$nodes[$i] =& $value;

		return $ns->$nodes[$i];
	}

	function & getReference($regpath, $default=null)
	{
		$result = $default;

		// Explode the registry path into an array
		if ($nodes = explode('.', $regpath))
		{
			// Get the namespace
			//$namespace = array_shift($nodes);
			$count = count($nodes);
			if ($count < 2) {
				$namespace	= $this->_defaultNameSpace;
				$nodes[1]	= $nodes[0];
			} else {
				$namespace = $nodes[0];
			}

			if (isset($this->_registry[$namespace])) {
				$ns = & $this->_registry[$namespace]['data'];
				$pathNodes = $count - 1;

				//for ($i = 0; $i < $pathNodes; $i ++) {
				for ($i = 1; $i < $pathNodes; $i ++) {
					if((isset($ns->$nodes[$i]))) $ns =& $ns->$nodes[$i];
				}

				if(isset($ns->$nodes[$i])) {
					$result = $ns->$nodes[$i];
				}
			}
		}
		return $result;
	}
	
}
