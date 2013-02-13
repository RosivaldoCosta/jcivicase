<?php defined('_JEXEC') or die('Restricted access'); 

$user =& JFactory::getUser();
$touser = JRequest::getInt('tuser');
require(JPATH_SITE.DS."components".DS."com_jbolo".DS."config".DS."config.php");
if($chat_config['chatusertitle'])
			$chattitle	= 'username';
		else
			$chattitle	= 'name';

$sizesetting = isset($chat_config['uploadlimit'])?$chat_config['uploadlimit']:'5';
$touser = JFactory::getUser($touser);
$doc = JFactory::getDocument();
$doc->addScript(JURI::base().'components/com_jbolo/js/jquery-1.3.2.pack.js');
$doc->addScriptDeclaration("jQuery.noConflict();");
$doc->addScript(JURI::base().'components/com_jbolo/js/swfupload.js');
$doc->addScript(JURI::base().'components/com_jbolo/js/jquery-asyncUpload-0.1.js');

$new_title=JText::_('SEND_FILE').' '.JText::_('TO').' '.$touser->$chattitle;
$doc->addScriptDeclaration(
"jQuery(document).ready(function() {
        document.title ='$new_title';
        jQuery('#jb_send_input').makeAsyncUploader({
						file_size_limit:'".$sizesetting."MB',
            upload_url: '".JURI::base()."index.php?option=com_jbolo&action=uploadFile&fuser=".$user->id."&tuser=".$touser->id."', 
            flash_url: '".JURI::base()."/components/com_jbolo/js/swfupload.swf',
            button_image_url: '".JURI::base()."/components/com_jbolo/img/blankButton.png'
        });
});");
/*
$doc->addScriptDeclaration(
"jQuery(document).ready(function() {
        document.title ='$new_title';
        jQuery('#jb_send_input').makeAsyncUploader({
						file_size_limit:'".$sizesetting."MB',
            upload_url: '".JURI::base()."index.php?option=com_jbolo&action=uploadFile&fuser=".$user->id."&tuser=".$touser->id."', 
            flash_url: '".JURI::base()."/components/com_jbolo/js/swfupload.swf',
            button_image_url: '".JURI::base()."/components/com_jbolo/img/blankButton.png',
            //upload_success_handler:function(){
		        alert('File uploaded and sent.');
				window.close();
		}	
        });
});");
*/
?>
<!--<div class="jb_backgrey"><div class="componentheading"><?php echo JText::_('SEND_FILE').' '.$touser->$chattitle; ?></div></div>-->
<!--<div id="jb_send_file">-->
	<?php echo JText::_('SELECT_FILE');?> <input type="file" name="jb_send_input" id="jb_send_input" />
	<i style="font-size:11px;">* <?php echo JText::sprintf('MAX_FILE_LIM',$sizesetting); ?></i>
<!--</div>-->
