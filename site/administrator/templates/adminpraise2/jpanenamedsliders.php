<?php
/**
 * @version		$Id: pane.php 10381 2008-06-01 03:35:53Z pasamio $
 * @package		Joomla.Framework
 * @subpackage	HTML
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
if(!class_exists('JPane')) {
   jimport('joomla.html.pane');
}
class JPaneNamedSliders extends JPane
{
	var $_paneClassName = null;
	var $_panelClassName = null;

	/**
	 * Constructor
	 *
	 * @param int useCookies, if set to 1 cookie will hold last used tab between page refreshes
	 */
	function __construct( $params = array() )
	{
		static $loaded = false;

		parent::__construct($params);

		$this->_paneClassName = (isset($params['paneClassName'])) ? $params['paneClassName'] : 'jpane';
		$this->_panelClassName = (isset($params['panelClassName'])) ? $params['panelClassName'] : 'panel';

		# Always load
		#if(!$loaded) {
			$this->_loadBehavior($params);
		#	$loaded = true;
		#}
	}

	/**
	 * Creates a pane and creates the javascript object for it
	 *
	 * @param string The pane identifier
	 */
	function startPane( $id )
	{
		return '<div id="'.$id.'" class="pane-sliders">';
	}

    /**
	 * Ends the pane
	 */
	function endPane() {
		return '</div>';
	}

	/**
	 * Creates a tab panel with title text and starts that panel
	 *
	 * @param	string	$text - The name of the tab
	 * @param	string	$id - The tab identifier
	 */
	function startPanel( $text, $id )
	{
		return '<div class="'.$this->_panelClassName.'">'
			.'<h3 class="'.$this->_paneClassName.'-toggler title" id="'.$id.'"><span>'.$text.'</span></h3>'
			.'<div class="'.$this->_paneClassName.'-slider content">';
	}

	/**
	 * Creates a tab panel with plus, html, and starts that panel
	 *
	 * @param	string	$html - The html of the tab
	 * @param	string	$id - The tab identifier
	 */
	function startPlusPanel( $html, $id, $hasChildren )
	{
		return '<div class="'.$this->_panelClassName.'">'
			.'<h3 class="'.$this->_paneClassName.'-toggler title" id="'.$id.'">'
			.($hasChildren ? '<span>+</span> ' : '')
			.'</h3>'
			.$html
			.'<div class="'.$this->_paneClassName.'-slider content">';
	}

	/**
	 * Ends a tab page
	 */
	function endPanel()
	{
		return '</div></div>';
	}

	/**
	 * Load the javascript behavior and attach it to the document
	 *
	 * @param	array 	$params		Associative array of values
	 */
	function _loadBehavior($params = array())
	{
		// Include mootools framework
		JHTML::_('behavior.mootools');

		$document =& JFactory::getDocument();

		$options = '{';
		$opt['onActive']	 = 'function(toggler, i) { toggler.addClass(\''.$this->_paneClassName.'-toggler-down\'); toggler.removeClass(\''.$this->_paneClassName.'-toggler\'); }';
		$opt['onBackground'] = 'function(toggler, i) { toggler.addClass(\''.$this->_paneClassName.'-toggler\'); toggler.removeClass(\''.$this->_paneClassName.'-toggler-down\'); }';
		$opt['duration']	 = (isset($params['duration'])) ? (int)$params['duration'] : 300;
		$opt['display']		 = (isset($params['startOffset']) && ($params['startTransition'])) ? (int)$params['startOffset'] : null ;
		$opt['show']		 = (isset($params['startOffset']) && (!$params['startTransition'])) ? (int)$params['startOffset'] : null ;
		$opt['opacity']		 = (isset($params['opacityTransition']) && ($params['opacityTransition'])) ? 'true' : 'false' ;
		$opt['alwaysHide']	 = (isset($params['allowAllClose']) && ($params['allowAllClose'])) ? 'true' : null ;
		$openOnMouseOver	 = (isset($params['openOnMouseOver']) && ($params['openOnMouseOver'])) ? 'true' : null ;
		if($openOnMouseOver) { $opt['alwaysHide'] = 'false'; }
		foreach ($opt as $k => $v)
		{
			if ($v) {
				$options .= $k.': '.$v.',';
			}
		}
		if (substr($options, -1) == ',') {
			$options = substr($options, 0, -1);
		}
		$options .= '}';

		$js = '		window.addEvent(\'domready\', function(){ var accordion = new Accordion($$(\'.'.$this->_panelClassName.' h3.'.$this->_paneClassName.'-toggler\'), $$(\'.'.$this->_panelClassName.' div.'.$this->_paneClassName.'-slider\'), '.$options.'); '.($openOnMouseOver ? 'for(i = 0; i < accordion.togglers.length; i++) { accordion.togglers[i].addEvent(\'mouseover\', accordion.display.bind(accordion, i)); }' : '').' });';

		$document->addScriptDeclaration( $js );
	}
}
