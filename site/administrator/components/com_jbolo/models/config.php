<?php
jimport('joomla.application.component.model');
class JboloModelConfig extends JModel
{
	function store(){

		global $mainframe; 
		$post	= $this->_state->get( 'request' );
		$file = 	JPATH_SITE.DS."components".DS."com_jbolo".DS."config".DS;

		if(!file_exists($file))
			@fopen($file."config.php",'w+');
			
		if ($post)
		{
			function row2text($row,$dvars=array())
			{
				reset($dvars);
				while(list($idx,$var)=each($dvars))
					unset($row[$var]);
				$text='';
				reset($row);
				$flag=0;
				$i=0;
				while(list($var,$val)=each($row))
				{
					if($flag==1)
						$text.=",\n";
					elseif($flag==2)
						$text.=",\n";
					$flag=1;
	
					if(is_numeric($var))
						if($var{0}=='0')
							$text.="'$var'=>";
						else
							{
							if($var!==$i)
								$text.="$var=>";
							$i=$var;
							}
					else
						$text.="'$var'=>";
					$i++;
	
					if(is_array($val))
						{
						$text.="array(".row2text($val,$dvars).")";
						$flag=2;
						}
					else
						$text.="'$val'";
					}
				return($text);
			}	
			
			
			$config = array();
			
			$config['purge'] 			= JRequest::getVar('purge');
			$config['badwords']			= JRequest::getVar('badwords');
			$config['days']				= JRequest::getVar('days');
			$config['key']				= JRequest::getVar('key');
			$config['chatusertitle'] 	= JRequest::getVar('chatusertitle');
			$config['community']		= JRequest::getVar('community');
			$config['fonly']			= JRequest::getVar('fonly');
			$config['chathistory']		= JRequest::getVar('chathistory');
			$config['sendfile']			= JRequest::getVar('sendfile');
			$config['groupchat']		= JRequest::getVar('groupchat');
			$config['uploadlimit']		= JRequest::getVar('uploadlimit');
			$config['maxChatHeartbeat']		= JRequest::getVar('maxChatHeartbeat');
			$file_contents="<?php \n\n";
			$file_contents.="\$chat_config=array(\n".row2text($config)."\n);\n";
			$file_contents.="\n?>";
			
			file_put_contents($file.'config.php',$file_contents);
			@chmod ($file.'config.php', 0644);
		}
		return 1;
	}
}
?>
