<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.error.log');

/* Example:
* /var/pentaho/design-tools/data-integration/pan.sh -file="/home/pentaho/user_info_rss.ktr" -level:Basic -param="output_rss_file=/var/www/html_demo/metrics/user_stat" -param="dbname=444002_jos_socialvest_primary"
*
*/
class Transformation 
{
        private $_command;
	private $_vars;
	private $_loglevel;
	private $_file;
	private $_log;

        function __construct($params)
        {
		$this->_command = "/home/pentaho/design-tools/data-integration/pan.sh";
		$this->_vars = $params['vars'];
		$this->_file =  $params['file'];
		$this->_loglevel = "Basic";
		$this->_log = JLog::getInstance('transformation-'.date('Y-m-d').'.log');

        }

	private function buildCommand()
	{

		$command = $this->_command . ' -file="'.$this->_file.'" -level:'.$this->_loglevel;
		foreach($this->_vars as $v)
		{
			$command .= ' -param="'.$v.'" ';
		}

		$this->_log->addEntry(array('comment' => 'Running command: '.$command));
		
		return $command;
	}

	public function run($method='shell')
        {
                switch($method)
                {
                        case 'shell':
                                        $result = shell_exec($this->buildCommand());
                                break;
                }

                return $result;
        }

}
