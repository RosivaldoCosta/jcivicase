{* include jscript to handle create new Lethality Rating Sheet *}
<div style="display: none;" id="lethality-dialog"></div>
<script type="text/javascript">
//<![CDATA[
    var cid = parseInt('{$contactId}');
	var caseid = parseInt('{$caseId}');
	var newLethalityNeed = true;
{if $newLethality}
{literal}
cj(document).ready(function($)
		  {
	newLethality(1);
		  });
{/literal}
{/if}
{literal}

function newLethality(is_auto)
{
	allowAutoSave = false;
    if(newLethalityNeed && cid > 0 && caseid > 0)
    {
    	// edit case
		cj('div#customData').attr('id','customData-1');
		cj('div#crm-container').attr('id','crm-container-1');
		cj('form#Activity').attr('id','Activity-1');
        cj("#lethality-dialog").show( ).html( "<div id='crm-container'>Please wait...</div>" ).dialog({
	    	title: "Create Lethality Rating Sheet",
   		modal: true,
        width: 1020,
   		position: [16,1000],
   		overlay: {
   			opacity: 0.5,
   			background: "black"
   		},

       beforeclose: function(event, ui) {
  			cj('div#customData-1').attr('id','customData');
   			cj('div#crm-container-1').attr('id','crm-container');
   			cj('form#Activity-1').attr('id','Activity');
           cj(this).dialog("destroy");
       },
       open: function(event, ui) {
   		cj('a.ui-dialog-titlebar-close').hide();
      }
   	});
        if (is_auto == 1)
        {
	    var dataURL = {/literal}"{crmURL p='civicrm/case/activity' q='reset=1&action=add&snippet=1&context=dialog' h=0 }"{literal};
	    dataURL = dataURL + '&cid='+cid+'&caseid='+caseid+'&atype=35&depart=ops&newlethality=1';
	    cj.ajax({
	       url: dataURL,
	       success: function( content ) {
	    	newLethalityNeed = false;
	           cj("#crm-container").html( content );

	           cj(document).unbind('mousedown.dialog-overlay');
	           cj('.ui-widget-overlay').bind('mousedown.dialog-overlay', function (){return false;});
	       
	       }
	    });
        }
        else
        {
	        cj('input.form-submit[value=Save]').after('<input type="hidden" value="1" name="newlethality">').click();
        }
	}
}

//]]>
</script>
{/literal}
