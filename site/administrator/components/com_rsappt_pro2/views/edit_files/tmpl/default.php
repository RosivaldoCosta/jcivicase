<?php
/*
 ****************************************************************
 Copyright (C) 2008-2010 Soft Ventures, Inc. All rights reserved.
 ****************************************************************
 * @package	Appointment Booking Pro - ABPro
 * @copyright	Copyright (C) 2008-2010 Soft Ventures, Inc. All rights reserved.
 * @license	GNU/GPL, see http://www.gnu.org/licenses/gpl-2.0.html
 *
 * ABPro is distributed WITHOUT ANY WARRANTY, or implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header must not be removed. Additional contributions/changes
 * may be added to this header as long as no information is deleted.
 *
 ************************************************************
 The latest version of ABPro is available to subscribers at:
 http://www.appointmentbookingpro.com/
 ************************************************************
*/


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//DEVNOTE: import html tooltips
JHTML::_('behavior.tooltip');

?>

<style type="text/css">
<!--
.icon-48-editfiles { background-image: url(./components/com_rsappt_pro2/images/log.png); }
-->
}
</style>
<form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" >
<link href="../components/com_rsappt_pro2/sv_apptpro.css" rel="stylesheet">
<?php  
     // instantiate new tab system
	jimport( 'joomla.html.pane' );
	
	$pane =& JPane::getInstance('Tabs');
	echo $pane->startPane('myPane');
	{
	// start tab pane
	//$tabs->startPane("TabPaneOne");
	echo $pane->startPanel(JText::_('RS1_ADMIN_SCRN_EDIT_FILES_CSS_TAB'), 'panel1');
?>

  <table width="80%" border="0" cellpadding="2" cellspacing="0">
    <tr>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_EDIT_FILES_CSS');?></td>
    </tr>
    <tr>
      <td><textarea rows="20" cols="80" name="cssfile" id="cssfile"><?php $fn = JPATH_SITE."/components/com_rsappt_pro2/sv_apptpro.css";
            print htmlspecialchars(implode("",file($fn)));?> 
		</textarea></td>
    </tr>
<!--    <tr>
      <td><p>
        <input name="Reset" type="button" value="<?php echo JText::_('RS1_ADMIN_SCRN_EDIT_FILES_CSS_RESET');?>" onclick="resetCSS()" />
      </p>
      <p><?php echo JText::_('RS1_ADMIN_SCRN_EDIT_FILES_CSS_NOTE');?></p></td>
    </tr>-->
  </table>
   <?php    
	echo $pane->endPanel();
	echo $pane->startPanel(JText::_('RS1_ADMIN_SCRN_EDIT_FILES_LANG_TAB'), 'panel1');
	
	?>
  <table width="80%" border="0" cellpadding="2" cellspacing="0">
<!--    <tr>
      <td><?php echo JText::_('RS1_ADMIN_SCRN_EDIT_FILES_LANG_REMEMBER_TO_SAVE');?></td>
    </tr>
    <tr>
      <td><select id="langfiles" name="langfiles">
--><?php
	$lang =& JFactory::getLanguage();
	$directory = JPATH_SITE.DS."language".DS;
	$file = scandir($directory);
	//print_r($file);
//	foreach( $file as $this_file ) {
//		if( $this_file != "." && $this_file != ".." ) {
//			if( is_dir("$directory/$this_file") ) {
//				$file2 = scandir("$directory/$this_file");
//				//print_r($file2);
//				foreach( $file2 as $this_file2 ) {
//					if( $this_file2 != "." && $this_file2 != ".." ) {
//						if( !is_dir("$directory/$this_file/$this_file2") ) {
//							if(strpos($this_file2, "com_rsappt_pro" ) >0 ){
//								echo "<option value='".$directory.$this_file.DS.$this_file2."' ";
//								if($lang->getTag() == $this_file){
//									echo " selected ";
//								}
//								echo ">".DS."language".DS.$this_file.DS.$this_file2."</option>\n";
////								$langfiles[$i] = "$this_file".DS."$this_file2";
////								$i++;
//							}
//						}
//					}
//				}
//			}
//		}
//	}
	//print_r($langfiles);
?>      
      </select>
<!--      </td>
    </tr>
--><?php   
	$lang_file_count = 0;
	foreach( $file as $this_file ) {
		if( $this_file != "." && $this_file != ".." ) {
			if( is_dir("$directory/$this_file") ) {
				$file2 = scandir("$directory".DS."$this_file");
				//print_r($file2);
				foreach( $file2 as $this_file2 ) {
					if( $this_file2 != "." && $this_file2 != ".." ) {
						if( !is_dir("$directory/$this_file/$this_file2") ) {
							if(strpos($this_file2, "com_rsappt_pro" ) >0 ){
								$filename = "langfile".$lang_file_count ?>
                            	<tr><td><b><?php echo "$directory".$this_file.DS.$this_file2 ?></b></td></tr>
								<tr>
								  <td><textarea rows="20" cols="80" wrap="off" name="<?php echo $filename?>" id="<?php echo $filename?>"><?php $fn = "$directory/$this_file/$this_file2";
										print implode("",file($fn));?> 
									</textarea><br/><br/>&nbsp;
                                    <input type="hidden" name="save_<?php echo $filename?>" value="<?php echo "$directory".$this_file.DS.$this_file2 ?>"/>
                                  </td>
								</tr>
                             <?php   
							 $lang_file_count++;
							}
						}
					}
				}
			}
		}
	}
 ?>   
<!--    <tr>
      <td><textarea rows="20" cols="80" wrap="off" name="langfile" id="langfile"><?php $fn = JPATH_SITE."/language/en-GB/en-GB.com_rsappt_pro2.ini";
            print htmlspecialchars(implode("",file($fn)));?> 
		</textarea></td>
    </tr>-->
  </table>
  <input type="hidden" name="lang_file_count" id="lang_file_count" value="<?php echo $lang_file_count;?>" />
  <?php    
	echo $pane->endPanel();

	}
	// end tab pane
	echo $pane->endPane();

?>

  <p>&nbsp;</p>
  <p>
  <input type="hidden" name="controller" value="edit_files" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="hidemainmenu" value="0" />  
  <input type="hidden" name="task" value="" />
  <br />
  <span style="font-size:10px"> Appointment Booking Pro Ver. 2.0 - Copyright 2008-20<?php echo date("y");?> - <a href='http://www.softventures.com' target="_blank">Soft Ventures, Inc.</a></span>
</form>
