<?php
/**
 * @version		$Id: menu.php 10381 2008-06-01 03:35:53Z pasamio $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.base.tree');
require_once(dirname(__FILE__).DS.'..'.DS.'..'.DS.'jpanenamedsliders.php');

class JAdminCSSMenu extends JTree
{
	/**
	 * CSS string to add to document head
	 * @var string
	 */
	var $_css = null;
	var $_sliders = null;
	var $_subSliders = null;
	var $_openMenusOnMouseOver = false;

	function __construct()
	{
		$this->_root = new JMenuNode('ROOT');
		$this->_current = & $this->_root;
		$document = &JFactory::getDocument();
		$this->_openMenusOnMouseOver = ($document->params->get('leftMenuOpen') == 'hover');
	}

	function addSeparator()
	{
		$this->addChild(new JMenuNode(null, null, 'separator', false));
	}

	function renderMenu($id = 'menu', $class = '')
	{
		global $mainframe;

		$depth = 1;

		if(!empty($id)) {
			$id='id="'.$id.'"';
		}

		if(!empty($class)) {
			$class='class="'.$class.'"';
		}

		$this->_sliders = new JPaneNamedSliders(array( 'paneClassName' => 'praise_jpane', 'panelClassName' => 'praise_panel', 'allowAllClose' => true, 'openOnMouseOver' => $this->_openMenusOnMouseOver ));
		echo $this->_sliders->startPane('praise_menu_pane');

		/*
		 * Recurse through children if they exist
		 */
		while ($this->_current->hasChildren())
		{
			echo "<ul ".$id." ".$class.">\n";
			foreach ($this->_current->getChildren() as $child)
			{
				$this->_current = & $child;
				$this->renderLevel($depth++);
			}
			echo "</ul>\n";
		}

		echo $this->_sliders->endPane();

		if ($this->_css) {
			// Add style to document head
			$doc = & JFactory::getDocument();
			$doc->addStyleDeclaration($this->_css);
		}
	}

	function renderLevel($depth, $useSubSliders = false)
	{
		/*
		 * Build the CSS class suffix
		 */
		$class = '';
		if ($this->_current->hasChildren()) {
			$class = ' class="node"';
		}

		if($this->_current->class == 'separator') {
			$class = ' class="separator"';
		}

		if($this->_current->class == 'disabled') {
			$class = ' class="disabled"';
		}


		/*
		 * Print the item
		 */
		echo "<li".$class.">";

		/*
		 * Print a link if it exists
		 */
		$closeSlider = false;
		if ($this->_current->link != null) {
			if(!$useSubSliders)
			{
				echo "<a class=\"".$this->getIconClass($this->_current->class)."\" href=\"".$this->_current->link."\">".$this->_current->title."</a>";
			}
			else
			{
				$html = "<a class=\"".$this->getIconClass($this->_current->class)."\" href=\"".$this->_current->link."\">".$this->_current->title."</a>";
				echo $this->_subSliders->startPlusPanel($html, 'praise_submenu_panel', $this->_current->hasChildren());
			}
		} elseif ($this->_current->title != null) {
			#echo "<a>".$this->_current->title."</a>\n";
			echo $this->_sliders->startPanel($this->_current->title, 'praise_menu_panel');
			$closeSlider = true;
		} else {
			echo "<span></span>";
		}

		$newUseSubSliders = ($this->_current->title == 'Components');

		/*
		 * Recurse through children if they exist
		 */
		while ($this->_current->hasChildren())
		{
			if($newUseSubSliders)
			{
				$this->_subSliders = new JPaneNamedSliders(array( 'paneClassName' => 'praise_jpane3', 'panelClassName' => 'praise_panel3', 'allowAllClose' => true, 'startOffset' => -1, 'startTransition' => false, 'openOnMouseOver' => $this->_openMenusOnMouseOver ));
				echo $this->_subSliders->startPane('praise_submenu_pane');
			}

			if ($this->_current->class) {
				echo '<ul id="menu-'.strtolower($this->_current->id).'"'.
					' class="menu-component">'."\n";
			} else {
				echo '<ul>'."\n";
			}
			foreach ($this->_current->getChildren() as $child)
			{
				$this->_current = & $child;
				$this->renderLevel($depth++, $newUseSubSliders);
			}
			echo "</ul>\n";

			if($newUseSubSliders)
			{
				echo $this->_subSliders->endPane();
			}
		}

		if ($useSubSliders) {
			echo $this->_subSliders->endPanel();
		}
		if ($closeSlider) {
			echo $this->_sliders->endPanel();
		}

		echo "</li>\n";
	}

	/**
	 * Method to get the CSS class name for an icon identifier or create one if
	 * a custom image path is passed as the identifier
	 *
	 * @access	public
	 * @param	string	$identifier	Icon identification string
	 * @return	string	CSS class name
	 * @since	1.5
	 */
	function getIconClass($identifier)
	{
		global $mainframe;

		static $classes;

		// Initialize the known classes array if it does not exist
		if (!is_array($classes)) {
			$classes = array();
		}

		/*
		 * If we don't already know about the class... build it and mark it
		 * known so we don't have to build it again
		 */
		if (!isset($classes[$identifier])) {
			if (substr($identifier, 0, 6) == 'class:') {
				// We were passed a class name
				$class = substr($identifier, 6);
				$classes[$identifier] = "icon-16-$class";
			} else {
				// We were passed an image path... is it a themeoffice one?
				if (substr($identifier, 0, 15) == 'js/ThemeOffice/') {
					// Strip the filename without extension and use that for the classname
					$class = preg_replace('#\.[^.]*$#', '', basename($identifier));
					$classes[$identifier] = "icon-16-$class";
				} else {
					if ($identifier == null) {
						return null;
					}
					// Build the CSS class for the icon
					$class = preg_replace('#\.[^.]*$#', '', basename($identifier));
					$class = preg_replace( '#\.\.[^A-Za-z0-9\.\_\- ]#', '', $class);

					$this->_css  .= "\n.icon-16-$class {\n" .
							"\tbackground: url($identifier) no-repeat;\n" .
							"}\n";

					$classes[$identifier] = "icon-16-$class";
				}
			}
		}
		return $classes[$identifier];
	}
}

class JMenuNode extends JNode
{
	/**
	 * Node Title
	 */
	var $title = null;

	/**
	 * Node Id
	 */
	var $id = null;


	/**
	 * Node Link
	 */
	var $link = null;

	/**
	 * CSS Class for node
	 */
	var $class = null;

	/**
	 * Active Node?
	 */
	var $active = false;


	function __construct($title, $link = null, $class = null, $active = false)
	{
		$this->title	= $title;
		$this->link		= JFilterOutput::ampReplace($link);
		$this->class	= $class;
		$this->active	= $active;
		$this->id		= str_replace(" ","-",$title);

	}
}
