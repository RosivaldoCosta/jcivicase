<?php
/**
 * @version		$Id: example.php 11720 2009-03-27 21:27:42Z ian $
 * @package		Joomla
 * @subpackage	JFramework
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');
jimport('kettle.transformation');
ini_set('display_errors','On');
error_reporting(E_ALL);


/**
 * Kettle Base Class for Plugins
 *
 * @package		Joomla
 * @subpackage	JFramework
 * @since 		1.5
 */
class KPlugin extends JPlugin {

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @param object $subject The object to observe
	 * @param 	array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */

	var $_name = null;

	function KPlugin(& $subject, $config, $name)
	{
		parent::__construct($subject, $config);

		// load plugin parameters
            	$this->_plugin = JPluginHelper::getPlugin( 'kettle', $name );
            	$this->_params = new JParameter( $this->_plugin->params );

	}

	public function onRun($args = null)
	{
		$file = $this->_params->get('ktr_file_name');
		$export_path = $this->_params->get('out_path'); //.'/'.$this->_params->get('excel_filename');
		$startdate = 'start.date='.str_replace('-','',$this->_params->get('start_date'));
		$enddate = 'end.date='.str_replace('-','',$this->_params->get('end_date'));
		$excel_file = 'excel.filename='.$this->_params->get('excel_filename');
		$dbname = 'db.name='.$this->_params->get('dbname');
		$dbuser = 'db.user='.$this->_params->get('dbuser');
		$dbhost = 'db.host=192.168.100.241'; //.$this->_params->get('dbhost');
		
		$params = array('file' => $file,
				'vars' => array($export_path, 
						$startdate,
						$enddate,
						$excel_file,
						$dbname,
						$dbuser,
						$dbhost)
				);
		//echo '<pre>'.print_r($params,true).'</pre>';

                $k = new Transformation($params);
                $result = $k->run();

		echo '<pre>'.print_r($result,true).'</pre>';
               
	}

}
