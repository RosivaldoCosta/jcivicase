<?php defined('_JEXEC') or die('Restricted access'); ?>


function jb_doLinks(text)
	{
		if( !text ) return text;

		if(text.match(/Download Link: /i))
		{	
			text = text.replace(/((https?\:\/\/|ftp\:\/\/)|(www\.))(\S+)(\w{2,4})(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/gi,function(url){
				
				if(!url.match('^https?:\/\/'))
					url = 'http://'+url;

				var txt='<a target="_blank" rel="nofollow" href="'+ url +'">';
				txt += 'Click Here';
				txt += '</a>';
				return txt;
			});
		}
		else
		{
			text = text.replace(/((https?\:\/\/|ftp\:\/\/)|(www\.))(\S+)(\w{2,4})(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/gi,function(url){
				
				if(!url.match('^https?:\/\/'))
					url = 'http://'+url;

				return '<a target="_blank" rel="nofollow" href="'+ url +'">'+ url +'</a>';
			});
		}

		return text;
	}


function jb_doSmileys(text) {
<?php

	foreach ($this->smileys as $smiley) {
		if (trim($smiley) == '') { continue; }
	
		$pcs = explode('=', $smiley);
		$pcs[0] = addslashes($pcs[0]);
	
		$img = 'administrator/components/com_jbolo/img/smileys/default/' . $pcs[1];
		$imgsrc = "<img src=\"{$img}\" border=\"0\" />";
		echo "\ttext = text.replace('{$pcs[0]}', '{$imgsrc}');\n";
	
	}
?>

	return text;
}

function jb_doReplace(text) {

	text = jb_doLinks(text);
	text = jb_doSmileys(text);
	
	return text;
	
}
var trans_me = '<?php echo JText::_('me'); ?>';
var jb_says = '<?php echo JText::_('says'); ?>';
var jb_offlinemsg = '<?php echo JText::_('offlinems'); ?>';
var jb_awaymsg = '<?php echo JText::_('awayms'); ?>';
var jb_abs_link = '<?php echo JURI::root().'administrator/';?>';
var jb_self_id = '<?php echo JFactory::getUser()->id; ?>';
var jb_maxChatHeartbeat='<?php echo $this->configarray['maxChatHeartbeat']; ?>';
jb_maxChatHeartbeat=jb_maxChatHeartbeat * 1000;


var chat_status_array = new Array();
chat_status_array.push('<?php echo JText::_('INVISBLE_TEXT'); ?>');
chat_status_array.push('<?php echo JText::_('AVAILABLE_TEXT'); ?>');
chat_status_array.push('<?php echo JText::_('AWAY_TEXT'); ?>');

var chat_config_array = new Array();
chat_config_array.push('<?php echo $this->configarray['chathistory']; ?>');
chat_config_array.push('<?php echo $this->configarray['sendfile']; ?>');
chat_config_array.push('<?php echo $this->configarray['groupchat']; ?>');

var jb_transstring = {
									"VIEW_HIST":"<?php echo JText::_('VIEW_HIST');?>",
									"SEND_FILE":"<?php echo JText::_('SEND_FILE');?>",
									"CLEAR_CONV":"<?php echo JText::_('CLEAR_CONV');?>",
									"STATUS_MES_LIM": "<?php echo JText::_('STATUS_MES_LIM');?>",
									"STATUS_MES_PROMPT": "<?php echo JText::_('STATUS_MES_PROMPT');?>",
									"FILE_UPLOAD_ERR" : "<?php echo JText::_('FILE_UPLOAD_ERR');?>",
									"FILE_UPLOADING":"<?php echo JText::_('FILE_UPLOADING');?>",
									}
function showchatdiv(obj1)
{
	jQuery("#ch_box_status").toggle();
}

