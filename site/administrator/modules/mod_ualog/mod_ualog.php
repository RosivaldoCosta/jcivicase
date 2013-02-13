<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$civicrm_activity = array();
$db = &JFactory::getDBO();
//error_reporting(E_ALL);
// get module settings
$show_date    = (int) $params->get( 'show_date' );
$show_filter  = (int) $params->get( 'show_filter' );$conf_name    = (int) $params->get( 'conf_name' );
$dformat      = $params->get( 'dateformat' );
$cf           = (int) JRequest::getVar('ualog_filter_id');
$cfo          = JRequest::getVar('ualog_filter_option');$rp           = $params->get('limit');
$f = "";
$user			=& JFactory::getUser();$user_id 		= $user->get('id');$user_usertype 	= $user->get('usertype');switch ($user_usertype){	case 'Administrator':	    $show_filter = false;    case 'Super Administrator':		// Output info about activity
		// get logged items		if($show_filter && ($cf || $cfo)) {		    if($cf) {		        $f = "\n WHERE u.id = '$cf'";		        if($cfo) {		            $f.= "\n AND l.option = ".$db->quote($cfo);		        }		    }		    else {		        if($cfo) {		            $f = "\n WHERE l.option = ".$db->quote($cfo);		        }		    }		}
		// start filter		if($show_filter) {		    $query = "SELECT id,name,username FROM #__users WHERE gid >= 23 ORDER BY name,username ASC";		           $db->setQuery($query);		           $fusers = $db->loadObjectList();
		    $query = "SELECT `option` FROM #__ualog GROUP BY `option` ORDER BY `option` ASC";		           $db->setQuery($query);		           $foptions = $db->loadResultArray();
		    if(!is_array($foptions)) { $foptions = array(); }
/*		    echo "<form action='index.php' method='get' name='ualog_form' id='ualog_form'>";		    
echo "<select name='ualog_filter_id' onchange='this.form.submit();'>";		    
echo "<option value='0'>".JText::_('Filter by user')."</option>";		    
foreach($fusers AS $f)		    {		        
$ps = "";		        
if($cf == $f->id) { $ps = ' selected="selected"'; }		        
echo "<option value='$f->id'$ps>".$f->name." (".$f->username.")</option>";		    }		    
echo "</select>&nbsp;";		    

echo "<select name='ualog_filter_option' onchange='this.form.submit();'>";		    
echo "<option value='0'>".JText::_('Filter by component')."</option>";		    
foreach($foptions AS $f)		    {		        
$ps = "";		        
if($cfo == $f) { $ps = ' selected="selected"'; }		        
echo "<option value='$f'$ps>".$f."</option>";		    }		    
echo "</select>";		    
echo "</form><hr/>";	
*/
	}		
// CiviCRM activities?><div id="civicrm-activity-log"></div>
<script type="text/javascript" src="components/com_civicrm/civicrm/packages/jquery/jquery.js"></script>
<script type="text/javascript">
var cj = jQuery.noConflict();	
cj.ajaxSetup({ cache : false });
cj(document).ready(function(){    
var loading = '<img src="components/com_civicrm/civicrm/i/loading.gif" alt="loading" />&nbsp;Loading...';    
cj('#civicrm-activity-log').html(loading);	var dataUrl = "?option=com_civicrm&task=civicrm/ajax/activity&log=1";	Today = new Date();	var time = Today.getTime();	dataUrl = dataUrl + '&tt=' + time;	cj.ajax({    	  type: 'POST',    	  url: dataUrl,    	  data: { 	rp: '<?php echo $rp; ?>',    		  		show_filter: '<?php echo $show_filter; ?>',    		  		conf_name: '<?php echo $conf_name; ?>',    		  		dformat: '<?php echo $dformat; ?>',    		  		cf: '<?php echo $cf; ?>',    		  		cfo: '<?php echo $cfo; ?>',        	  		show_date :'<?php echo $show_date; ?>'            	},    	  success:	 function( data ) {    			cj('#civicrm-activity-log').html(data);    		}    });});</script><?php		break;	case 'Manager':	default:
}
