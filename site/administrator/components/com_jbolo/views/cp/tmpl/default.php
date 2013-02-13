<?php 
defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.pane');
$document =& JFactory::getDocument();
$document->addStyleSheet(JURI::base().'components/com_jbolo/css/jbolo.css');

jimport('joomla.html.pane');
//1st Parameter: Specify 'tabs' as appearance 
//2nd Parameter: Starting with third tab as the default (zero based index)
//open one!
//Create a JSimpleXML object
$xml = new JSimpleXML(); 
$currentversion = '';
//Load the xml file
$xml->loadFile(JPATH_SITE.'/administrator/components/com_jbolo/jbolo.xml');
foreach($xml->document->_children as $var)
{
	if($var->_name=='version')
		$currentversion = $var->_data;
}
?>
<script type="text/javascript">
function vercheck()
{
	callXML('<?php echo $currentversion; ?>');
	if(document.getElementById('NewVersion').innerHTML.length<220)
	{
		document.getElementById('NewVersion').style.display='inline';
	}
}

function callXML(currversion)
{
	if (window.XMLHttpRequest)
	  {
	 	 xhttp=new XMLHttpRequest();
	  }
	else // Internet Explorer 5/6
	  {
	 	xhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }

	xhttp.open("GET","<?php echo JURI::base(); ?>index.php?option=com_jbolo&task=getVersion",false);
	xhttp.send("");
	latestver=xhttp.responseText;

	if(latestver!=null)
	{
		if(currversion == latestver)
		{
			document.getElementById('NewVersion').innerHTML='<span style="display:inline; color:#339F1D;">Latest Version:'+latestver+' &nbsp;';
			//Your Current Version is <b>:'+currversion+"</b></span>";
		}
		else
		{
			document.getElementById('NewVersion').innerHTML='<span style="display:inline; color:#FF0000;">Latest Version:'+latestver+' &nbsp;';
			//Your Current Version is <b>:'+currversion+"</b></span>";
		}
	}
}
</script>

<table style="margin-bottom:5px;width:100%;table-layout:fixed;">
<tbody>
	<tr>
		<td style="text-align:left;width:70%;vertical-align:top;">
			<table style="margin-bottom:5px;width:100%;table-layout:fixed;">
				<tr>		
					<td style="width:50%;vertical-align:top;">
						<div id="cpanel" style="display:inline;">
							<div class="icon">
								<a href="index.php?option=com_jbolo&view=config">
									<img src="<?php JURI::base()?>components/com_jbolo/images/process.png" alt="Config"/>
										<span><?php echo JText::_('CONFIG'); ?></span>
								</a>
								
								<a href="http://techjoomla.com/documentation-for-jbolo/jbolo-faqs.html" target="_blank">
									<img src="<?php echo JURI::base(); ?>components/com_jbolo/images/faq.png" />
										<span><?php echo JText::_('FAQs');?></span>
								</a>
								<a href="http://techjoomla.com/table/documentation-for-jbolo/" target="_blank">
									<img src="<?php echo JURI::base(); ?>components/com_jbolo/images/doc.png" />
										<span><?php echo JText::_('DOCs');?></span>
								</a>
								<a href="http://techjoomla.com/jbolo.-chat-for-cb-jomsocial-joomla/feed/rss.html" target="_blank">
									<img src="<?php echo JURI::base(); ?>components/com_jbolo/images/rss.png" />
										<span><?php echo JText::_('SUBSCRIBE_RSS_FEED');?></span>
								</a>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</td>
		
		<td style="text-align:left; width:30%; vertical-align:top;">
			<div style="float:right; width:100%; position:relative">
				<?php
				$pane =& JPane::getInstance('tabs', array('startOffset'=>0)); 
				echo $pane->startPane( 'pane' );
				echo $pane->startPanel( '<b>'.JText::_('JBolo! Stats').'</b>', 'panel1' );
				?>
				<p style="font-weight:bold;text-align:left;font-size:12px;">
						<?php 
							$user_stats=$this->getJboloStats;
							$invisible_count=0;
							$active_count=0;
							$away_count=0;
							$custom_status_count=0; 
						
						if($user_stats)
						{
							//print_r($user_stats);
							for($i=0;$i<count($user_stats);$i++)
							{
								if($user_stats[$i]->status==0)
									$invisible_count=$user_stats[$i]->count;
								if($user_stats[$i]->status==1)
									$active_count=$user_stats[$i]->count;
								if($user_stats[$i]->status==2)
									$away_count=$user_stats[$i]->count;
								if($user_stats[$i]->status==3)
									$custom_status_count=$user_stats[$i]->count;
							}
						
						}
						$total_count=$invisible_count + $active_count + $away_count +	$custom_status_count;
					?>
					
					<table class="adminlist" style="width:100%">
					<thead>
						<tr>
							<th colspan="3"><?php echo JText::_( "Current JBolo Users Stats" ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>
								 <?php echo JText::_( "Available" ); ?>
							</th>
							<td style="text-align: right;"><?php echo $active_count; ?></td>
						</tr>
						<tr>
							<th>
								 <?php echo JText::_( "Away" ); ?>
							</th>
							<td style="text-align: right;"><?php echo $away_count; ?></td>
						</tr>
						<tr>
							<th>
								 <?php echo JText::_( "Invisible" ); ?>
							</th>
							<td style="text-align: right;"><?php echo $invisible_count; ?></td>
						</tr>
						<tr>
							<th>
								 <?php echo JText::_( "Custom Status" ); ?>
							</th>
							<td style="text-align: right;"><?php echo $custom_status_count; ?></td>
						</tr>
						<tr>
							<th>
								 <?php echo JText::_( "Total" ); ?>
							</th>
							<td style="text-align: right;"><?php echo $total_count; ?></td>
						</tr>
					</tbody>
					</table>
				</p>
				
				<?php
				echo $pane->endPanel();
				echo $pane->startPanel('<b>'.JText::_('SETUP_CHECKLIST').'</b>', 'panel2' );
				$complete='<img src="'.JURI::base().'components/com_jbolo/images/thumbs_up.gif" alt="done" style="vertical-align:text-top;"/>';
				$incomplete='<img src="'.JURI::base().'components/com_jbolo/images/thumbs_down.gif" alt="warning" style="vertical-align:text-top;"/>';
				$enabled='<span style="color:green;">Enabled-</span>';
				$disabled='<span style="color:red;">Disabled-</span>';
				?>
				<h4><?php echo JText::_('SETUP_CHECKLIST');?>:</h4>
				<fieldset>
					<span style="padding-left:40px;">
						<br/>
						<br/><?php echo (($this->isplugin==1)?$complete.$enabled :$incomplete.$disabled );?> Plugin (plg_sys_jbolo_asset)
						<br/><br/><?php echo (($this->ismodule==1)?$complete.$enabled :$incomplete.$disabled );?> Module (mod_jbolo)
						<br/>
					</span>
				</fieldset>
				<?php
				echo $pane->endPanel();
				echo $pane->endPane();
				?>
			</div>
		</td>
	</tr>
</tr>
</tbody>
</table>

<?php
$logo_path='<img src="'.JURI::base().'components/com_jbolo/images/techjoomla.png" alt="TechJoomla" style="vertical-align:text-top;"/>';
?>
<table style="margin-bottom:5px; width:100%; border-top:thin solid #e5e5e5;table-layout:fixed;">
<tbody>
	<tr>
		<td style="text-align:left; width:33%;">
			<a href="http://techjoomla.com/index.php?option=com_billets&view=tickets&layout=form&Itemid=18" target="_blank"><?php echo JText::_('TechJoomla Support Center'); ?></a>
			<br/>
			<a href="http://twitter.com/techjoomla" target="_blank"><?php echo JText::_("Follow Us on Twitter"); ?></a>
			<br/>
			<a href="http://www.facebook.com/techjoomla" target="_blank"><?php echo JText::_("Follow Us on FaceBook"); ?></a>
			<br/>
			<a href="http://extensions.joomla.org/extensions/communication/instant-messaging/9344" target="_blank"><?php echo JText::_( "Leave JED Feedback" ); ?>
			</a>
		</td>
		
		<td style="text-align:center; width: 33%;">
			<?php echo JText::_("JBOLO_INTRO" ); ?>
			<br/>
			<?php echo JText::_("COPYRIGHT"); ?>
			<br/>
			<?php echo JText::_("Version 2.9.1"); ?>
			<span class="latestbutton" onclick="vercheck();">
				<?php echo JText::_('CHECK_LATEST_VERSION');?>
			</span>
			<span id='NewVersion' style='display:none; padding-top:5px; color:#000000; font-weight:bold; padding-left:5px;'></span>
		</td>
		
		<td style="text-align:right; width: 33%;">
			<a href='http://techjoomla.com/' taget='_blank'>
			<?php echo $logo_path;?>
			</a>
		</td>
	</tr>
</tbody>
</table>
