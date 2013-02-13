{* CiviCase -  view case screen*}

<div class="form-item">
<!--<fieldset><legend>{ts}Client Profile{/ts}</legend>
<div align="center">

    <table width="100%" align="center">
    {if count($contactTags)>0}<tr><td class="tag-image" colspan="5">
    {foreach from=$contactTags item=tag}
    	<img alt="{$tag.name}" src="{$tag.image}" title="{$tag.name}"/>
    {/foreach}
    </td></tr>{/if}
        <tr>
            <td>
      	       	   <label>{ts}Client{/ts}:</label>&nbsp; <a href="{crmURL p='civicrm/contact/view' q="reset=1&cid=`$contactId`"}">{$displayName}</a>
            </td>
            <td>
                <label>{ts}Case Type{/ts}:</label>&nbsp;{$caseDetails.case_type}&nbsp;
{if !$caseNotEdit}
	{if !$isManager}
	<a href="{crmURL p='civicrm/case/activity' q="action=add&reset=1&cid=`$contactId`&caseid=`$caseId`&selectedChild=activity&atype=`$changeCaseTypeId`"}" title="Change case type (creates activity record)">
		<img src="{$config->resourceBase}i/edit.png" border="0">
	</a>
	{/if}
{/if}
            </td>

            <td>
                <label>{ts}Status{/ts}:</label>&nbsp;{$caseDetails.case_status}&nbsp;
{if !$caseNotEdit}<a href="{crmURL p='civicrm/case/activity' q="action=add&reset=1&cid=`$contactId`&caseid=`$caseId`&selectedChild=activity&atype=`$changeCaseStatusId`"}" title="Change case status (creates activity record)"><img src="{$config->resourceBase}i/edit.png" border="0"></a>
{/if}
            </td>
            <td>
                <label>{ts}Start Date{/ts}:</label>&nbsp;{$caseDetails.case_start_date|crmDate}&nbsp;
<a href="{crmURL p='civicrm/case/activity' q="action=add&reset=1&cid=`$contactId`&caseid=`$caseId`&selectedChild=activity&atype=`$changeCaseStartDateId`"}" title="Change case start date (creates activity record)"><img src="{$config->resourceBase}i/edit.png" border="0"></a>
            </td>
            <td>
                <label>{ts}Case ID{/ts}:</label>&nbsp;{$caseID}
            </td>
        </tr>
    </table>
            {include file="CRM/common/auditor.tpl"}
</div>
</fieldset>
-->
    <table width="100%" align="center">
    {if count($contactTags)>0}<tr><td class="tag-image" colspan="5">
    {foreach from=$contactTags item=tag}
    	<img alt="{$tag.name}" src="{$tag.image}" title="{$tag.name}"/>
    {/foreach}
    </td></tr>{/if}
</table>
{include file="CRM/Custom/Form/CustomDataTopUser.tpl"}
<script language="javascript">
//<!--
//document.write('<p>' + screen.width + ' x ' +screen.height+'</p>');
//-->
</script>

{*include activity view js file*}
{include file="CRM/common/activityView.tpl"}

<div id="activities_show" class="section-hidden section-hidden-border">
  <a href="#" onclick="hide('activities_show'); show('activities'); return false;"><img src="{$config->resourceBase}/TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}Case History{/ts}</label><br />

<div id="view-activity">
     <div id="activity-content"></div>
</div>
</div>

<div id="activities" class="section-shown">
<fieldset>
<legend><a href="#" onclick="hide('activities'); show('activities_show'); return false;"><img src="{$config->resourceBase}/TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}Case History{/ts}</legend>
  <div><a id="searchFilter" href="javascript:showHideSearch( );" class="collapsed">{ts}Search Filters{/ts}</a></div>
  <table class="no-border form-layout-compressed" id="searchOptions">
    <tr>
        <td><label for="status_id">{$form.status_id.label}</label><br />
            {$form.status_id.html}
        </td>
        <td colspan="2"><!--<label for="reporter_id">{ts}Reporter/Role{/ts}</label><br />
            {$form.reporter_id.html}
		-->
        </td>
	<td style="vertical-align: bottom;"><input class="form-submit default" name="_qf_Basic_refresh" value="Search" type="button" onclick="search()"; /></td>
    </tr>
    <tr>
        <td>
	        {$form.activity_date_low.label}<br />
            {include file="CRM/common/jcalendar.tpl" elementName=activity_date_low}
        </td>
        <td>
            {$form.activity_date_high.label}<br />
            {include file="CRM/common/jcalendar.tpl" elementName=activity_date_high}
        </td>
        <td>
            {$form.activity_type_filter_id.label}<br />
            {$form.activity_type_filter_id.html}
        </td>
    </tr>
    {if $form.activity_deleted}
    	<tr>
	     <td>
		 {$form.activity_deleted.html}{$form.activity_deleted.label}
	     </td>
	</tr>
	{/if}
  </table>
  <br />

  <table id="activities-selector"  class="nestedActivitySelector" style="display:none"></table>

</fieldset>
</div> <!-- End Activities div -->
{if $isAuditor}
<div id="audit_show" class="section-hidden section-hidden-border">
  <a href="#" onclick="hide('audit_show'); show('audit'); return false;"><img src="{$config->resourceBase}/TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}Clinical View{/ts}</label><br />
</div>

<div id="audit" class="section-shown">
 <fieldset>
  <legend><a href="#" onclick="hide('audit'); show('audit_show'); return false;"><img src="{$config->resourceBase}/TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}Clinical View{/ts}</legend>
<div>
<a href="index.php?option=com_civicrm&task=civicrm/case/report&reset=1&cid={$contactID}&caseid={$caseID}&asn=audit_timeline">Clinical View</a>
</div>
</div>
{/if}

{if !$caseNotEdit}
<div id="tasks_show" class="section-hidden section-hidden-border">
  <a href="#" onclick="hide('tasks_show'); show('tasks'); return false;"><img src="{$config->resourceBase}/TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}Tasks{/ts}</label><br />
</div>

<div id="tasks" class="section-shown">
 <fieldset>
  <legend><a href="#" onclick="hide('tasks'); show('tasks_show'); return false;"><img src="{$config->resourceBase}/TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}Tasks{/ts}</legend>
<div>
<!--<a href="index2.php?option=com_civicrm&task=civicrm/contact/view/activity&action=add&reset=1&cid={$contactID}&selectedChild=activity&atype=2">Phone Call</a><br/>-->
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=68"> Assess Lethality</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=80">Attach Document</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=56"> Confirm Appointment</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=67"> Call Referrals</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=76"> Client's Providers</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=104"> Call to Schedule UCC Dr. Appt</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=111">Call to Schedule UCC Therapist Appt</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=44">Dispatch MCT</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=75"> Give Referrals to Alt</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=34"> How Are You</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=42">Hospital Diversion Follow-Up</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=72"> If No Call Back </a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=105"> IFIT Referral </a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=45">Other Task</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=73"> Offer Services  </a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=74"> Relative </a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=47">Recommend for Closure</a> <br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=77">Send Out PAC Evaluation</a> <br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=78">Supervisor To Review</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=113">Supervisor Note</a><br/>
{if !in_array('Psychiatrist',$contactGroups)}<a href="index2.php?option=com_rsappt_pro2&view=bookingscreengad&cid={$contactID}&caseid={$caseID}">Schedule UCC Appointment</a><br/>
<a href="index.php?option=com_rsappt_pro2&view=ihit&cid={$contactID}&caseid={$caseID}">IFIT Visit</a><br/>{/if}
</div>
</div>
{if !$caseNotEdit}
{if !in_array('Psychiatrist',$contactGroups)}
<div id="phone_show" class="section-hidden section-hidden-border">
  <a href="#" onclick="hide('phone_show'); show('phone'); filterPhoneGrid(); return false;"><img src="{$config->resourceBase}/TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}Phone Contact{/ts}</label><br />
</div>

<div id="phone" class="section-shown">
 <fieldset>
  <legend><a href="#" onclick="hide('phone'); show('phone_show'); return false;"><img src="{$config->resourceBase}/TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}Phone Contact{/ts}</legend>
<div>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=48">Phone Contact</a><br/>

  <table id="phone-selector"  class="nestedActivitySelector" style="display:none"></table>
</div>
</div>
{/if}{/if}
{if !$caseNotEdit}
{if !in_array('Psychiatrist',$contactGroups)}

            {include file="CRM/common/appointments.tpl"}
{/if}{/if}

{if !in_array('Psychiatrist',$contactGroups)}
<div id="opsForms_show" class="section-hidden section-hidden-border">
  <a href="#" onclick="hide('opsForms_show'); show('opsForms'); return false;"><img src="{$config->resourceBase}/TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}New OPS Forms{/ts}</label><br />
</div>

<div id="opsForms" class="section-shown">
 <fieldset>
  <legend><a href="#" onclick="hide('opsForms'); show('opsForms_show'); return false;"><img src="{$config->resourceBase}/TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}New OPS Forms{/ts}</legend>
<div>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=35&depart=ops">Lethality</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=36">Care Plan</a>
 </div>
</div>

<div id="mctForms_show" class="section-hidden section-hidden-border">
  <a href="#" onclick="hide('mctForms_show'); show('mctForms'); return false;"><img src="{$config->resourceBase}/TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}New MCT Forms{/ts}</label><br />
</div>

<div id="mctForms" class="section-shown">
 <fieldset>
  <legend><a href="#" onclick="hide('mctForms'); show('mctForms_show'); return false;"><img src="{$config->resourceBase}/TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}New MCT Forms{/ts}</legend>
<div>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=35&depart=mct">Lethality</a> <br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=37&depart=mct">Assessment And Treatment</a> <br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=38&depart=mct">MCT Dispatch</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=39&depart=mct">Consent For Services</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=40&depart=mct">Authorization To Release</a>
</div>
</div>

{/if}

<div id="uccForms_show" class="section-hidden section-hidden-border">
  <a href="#" onclick="hide('uccForms_show'); show('uccForms'); return false;"><img src="{$config->resourceBase}/TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}New UCC Forms{/ts}</label><br />
</div>

<div id="uccForms" class="section-shown">
 <fieldset>
  <legend><a href="#" onclick="hide('uccForms'); show('uccForms_show'); return false;"><img src="{$config->resourceBase}/TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}New UCC Forms{/ts}</legend>
<div>
{if in_array('Psychiatrist',$contactGroups) || $isAdmin  }
   {if !$isIntern }
		<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=90&depart=ucc">Medication Evaluation</a> <br/>
   {/if}
{/if}
	

<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=102&depart=ucc">Assessment And Treatment</a> <br/>

<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=82&depart=ucc">Informed Consent</a> <br/>

<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=50&depart=ucc">Notes</a><br/>

<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=85&depart=ucc">Human Rights Notification</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=40&depart=ucc">Authorization To Release</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=84&depart=ucc">Acknowledgment for Privacy Practices</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=93&depart=ucc">Informed Consent For Medication</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=98&depart=ucc">Client Medical History</a>
</div>
</div>

{if !in_array('Psychiatrist',$contactGroups)}
	{if !$isIntern}
		<div id="cismForms_show" class="section-hidden section-hidden-border">
  			<a href="#" onclick="hide('cismForms_show'); show('cismForms'); return false;"><img src="{$config->resourceBase}/TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}New CISM Forms{/ts}</label><br />
		</div>

<div id="cismForms" class="section-shown">
 <fieldset>
  <legend><a href="#" onclick="hide('cismForms'); show('cismForms_show'); return false;"><img src="{$config->resourceBase}/TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}New CISM Forms{/ts}</legend>
<div>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=91&depart=cism">CISM Intervention</a> <br/>
</div>
</div>
	{/if}


<div id="ihitForms_show" class="section-hidden section-hidden-border">
  <a href="#" onclick="hide('ihitForms_show'); show('ihitForms'); return false;"><img src="{$config->resourceBase}/TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}New IFIT Forms{/ts}</label><br />
</div>

<div id="ihitForms" class="section-shown">
 <fieldset>
  <legend><a href="#" onclick="hide('ihitForms'); show('ihitForms_show'); return false;"><img src="{$config->resourceBase}/TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}New IFIT Forms{/ts}</legend>
<div>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=50&depart=ihit">Progress Note</a> <br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=37&depart=ihit">Assessment And Treatment</a> <br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=51&depart=ihit">Individual Treatment Plan</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=39&depart=ihit">Consent For Services</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=40&depart=ihit">Authorization To Release</a>
</div>
</div>

{if !$isIntern }
<div id="voucherForms_show" class="section-hidden section-hidden-border">
  <a href="#" onclick="hide('voucherForms_show'); show('voucherForms'); return false;"><img src="{$config->resourceBase}/TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}New Voucher Forms{/ts}</label><br />
</div>

<div id="voucherForms" class="section-shown">
 <fieldset>
  <legend><a href="#" onclick="hide('voucherForms'); show('voucherForms_show'); return false;"><img src="{$config->resourceBase}/TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}New Voucher Forms{/ts}</legend>
<div>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=89">Cab/Transportation Voucher</a> <br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=88">Motel Voucher</a> <br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=86">Pharmacy Voucher</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=87">Temporary Housing Voucher</a><br/>
<!--<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=00">Walmart Voucher</a>-->
</div>
</div>
{/if}
{include file="CRM/common/caseadditionalforms.tpl"}

{/if}
{/if}
{literal}
<script type="text/javascript">

cj("#dialog").hide( );
function createRelationship( relType, contactID, relID, rowNumber ) {
    cj("#dialog").show( );

	cj("#dialog").dialog({
		title: "Assign Case Role",
		modal: true,
		bgiframe: true,
		overlay: {
			opacity: 0.5,
			background: "black"
		},
		beforeclose: function(event, ui) {
            cj(this).dialog("destroy");
        },

		open:function() {
			/* set defaults if editing */
			cj("#rel_contact").val( "" );
			cj("#rel_contact_id").val( null );
			if ( contactID ) {
				cj("#rel_contact_id").val( contactID );
				cj("#rel_contact").val( cj("#relName_" + rowNumber).text( ) );
			}

			var contactUrl = {/literal}"{crmURL p='civicrm/ajax/contactlist' q='context=caseview' h=0 }"{literal};

			cj("#rel_contact").autocomplete( contactUrl, {
				width: 260,
				selectFirst: false,
	                        matchContains: true
			});

			cj("#rel_contact").focus();
			cj("#rel_contact").result(function(event, data, formatted) {
				cj("input[id=rel_contact_id]").val(data[1]);
			});
		},

		buttons: {
			"Ok": function() {
				if ( ! cj("#rel_contact").val( ) ) {
					alert('{/literal}{ts}Select valid contact from the list{/ts}{literal}.');
					return false;
				}

				var sourceContact = {/literal}"{$contactID}"{literal};
				var caseID        = {/literal}"{$caseID}"{literal};

				var v1 = cj("#rel_contact_id").val( );

				if ( ! v1 ) {
					alert('{/literal}{ts}Select valid contact from the list{/ts}{literal}.');
					return false;
				}

				var postUrl = {/literal}"{crmURL p='civicrm/ajax/relation' h=0 }"{literal};
                cj.post( postUrl, { rel_contact: v1, rel_type: relType, contact_id: sourceContact, rel_id: relID, case_id: caseID },
                    function( data ) {
                        var resourceBase   = {/literal}"{$config->resourceBase}"{literal};
                        var contactViewUrl = {/literal}"{crmURL p='civicrm/contact/view' q='action=view&reset=1&cid=' h=0 }"{literal};
                        var deleteUrl      = {/literal}"{crmURL p='civicrm/contact/view/rel' q="action=delete&reset=1&cid=`$contactID`&caseID=`$caseID`&id=" h=0 }"{literal};
                        var html = '<a href=' + contactViewUrl + data.cid +' title="view contact record">' +  data.name +'</a>';
                        cj('#relName_' + rowNumber ).html( html );

                        html = '';
                        html = '<img src="' +resourceBase+'i/edit.png" title="edit case role" onclick="createRelationship( ' + relType +','+ data.cid +', ' + data.rel_id +', ' + rowNumber +' );">&nbsp;&nbsp; <a href=' + deleteUrl + data.rel_id +' onclick = "if (confirm(\'{/literal}{ts}Are you sure you want to delete this relationship{/ts}{literal}?\') ) this.href +=\'&confirmed=1\'; else return false;"><img title="remove contact from case role" src="' +resourceBase+'i/delete.png"/></a>';
                        cj('#edit_' + rowNumber ).html( html );

                        html = '';
                        if ( data.phone ) {
                            html = data.phone;
                        }
                        cj('#phone_' + rowNumber ).html( html );

                        html = '';
                        if ( data.email ) {
                            var activityUrl = {/literal}"{crmURL p='civicrm/contact/view/activity' q="atype=3&action=add&reset=1&caseid=`$caseID`&cid=" h=0 }"{literal};
                            html = '<a href=' + activityUrl + data.cid + '><img src="'+resourceBase+'i/EnvelopeIn.gif" alt="Send Email"/></a>&nbsp;';
                        }
                        cj('#email_' + rowNumber ).html( html );

                        }, 'json'
                    );

				cj(this).dialog("close");
				cj(this).dialog("destroy");
			},

			"Cancel": function() {
				cj(this).dialog("close");
				cj(this).dialog("destroy");
			}
		}

	});
}

function showHideSearch( ) {
   cj("#searchOptions").toggle( );
   if ( cj("#searchFilter").hasClass('collapsed') ) {
       cj("#searchFilter").removeClass('collapsed');
       cj("#searchFilter").addClass('expanded');
   } else {
       cj("#searchFilter").removeClass('expanded');
       cj("#searchFilter").addClass('collapsed');
   }
}

cj(document).ready(function(){
   cj("#searchOptions").hide( );
   cj("#view-activity").hide( );
});
</script>
{/literal}
{if !$caseNotEdit}
{literal}
<script type="text/javascript">
show('tasks_show');
hide('tasks');
</script>
{/literal}

{if $isAuditor}
	{literal}
	<script type="text/javascript">
		show('audit_show');
		hide('audit');
	</script>
	{/literal}
{/if}

{if !in_array('Psychiatrist',$contactGroups)}

{literal}
<script type="text/javascript">
show('mctForms_show');
hide('mctForms');
</script>
{/literal}


{if !$isIntern}
{literal}
<script type="text/javascript">
show('cismForms_show');
hide('cismForms');
</script>
{/literal}
{/if}

{literal}
<script type="text/javascript">
show('phone_show');
hide('phone');
</script>
{/literal}

{literal}
<script type="text/javascript">
show('ihitForms_show');
hide('ihitForms');
</script>
{/literal}{/if}

{literal}
<script type="text/javascript">
show('uccForms_show');
hide('uccForms');
</script>
{/literal}

{if !in_array('Psychiatrist',$contactGroups)}{literal}
<script type="text/javascript">
show('opsForms_show');
hide('opsForms');
</script>
{/literal}

{if !$isIntern}
{literal}
<script type="text/javascript">
show('voucherForms_show');
hide('voucherForms');
</script>

{/literal}
{/if}


{literal}
<script type="text/javascript">
show('additionalForms_show');
hide('additionalForms');
</script>
{/literal}
{/if}
{/if}
{include file="CRM/common/phoneflexigrid.tpl"}

{literal}
<script type="text/javascript">
cj(document).ready(function(){

	resizeFlexiGrid();
	resizePhoneGrid();
	//resizeAppointmentGrid();

});

function resizeFlexiGrid()
{

    var dataUrl = {/literal}"{crmURL p='civicrm/ajax/activity' h=0 q='snippet=4&caseID='}{$caseID}"{literal};

        dataUrl = dataUrl + '&cid={/literal}{$contactID}{literal}';

        var gridw = 1030;
    	var diff = 0;

        if(cj(window).width() >= gridw)
    	{
    		diff = cj(window).width() - gridw;
    		diff = parseInt(diff/8);
    		gridw = gridw + (diff * 8);
    	}
        //cj("#activities-selector").remove();
        cj("#activities-selector").flexigrid
        (
            {
                url: dataUrl,
                dataType: 'json',
                colModel : [

                {display: 'Assigned Date',    name : 'display_date', width : 100+diff,  sortable : true, align: 'left'},
                /*{display: 'Type',    name : 'type',        width : 100+diff,  sortable : true, align: 'left'},*/
                {display: 'Title', name : 'subject',     width : 105+diff, sortable : true, align: 'left'},
                {display: 'Date Due',    name : 'display_date', width : 100+diff,  sortable : true, align: 'left'},
                {display: 'Reporter / Assignee',name : 'reporter',    width : 100+diff,  sortable : true, align: 'left'},
                {display: 'Completed?',  name : 'status',      width : 65+diff,  sortable : true, align: 'left'},
                {display: 'Completed By',  name : 'completed_contact',      width : 65+diff,  sortable : true, align: 'left'},
                {display: 'Completed Date',    name : 'display_date', width : 100+diff,  sortable : true, align: 'left'},
                {display: '',        name : 'links',       width : 110+diff,  align: 'left'},
                {name : 'class', hide: true, width: 1}  // this col is use for applying css classes
                ],
                usepager: true,
                useRp: true,
		rpOptions: [10,20,40,80,100],
                rp: 80,
                showToggleBtn: false,
                width: '100%',
                height: 'auto',
                nowrap: false,
                onSuccess:setSelectorClass
            }
        );
}

function search(com)
{
    var activity_date_low  = cj("#activity_date_low").val();
    var activity_date_high = cj("#activity_date_high").val();

    var activity_deleted = 0;
    if ( cj("#activity_deleted:checked").val() == 1 ) {
        activity_deleted = 1;
    }
    cj('#activities-selector').flexOptions({
	    newp:1,
		//params:[{name:'reporter_id', value: cj("select#reporter_id").val()},
		params:[{name:'status_id', value: cj("select#status_id").val()},
			{name:'activity_type_id', value: cj("select#activity_type_filter_id").val()},
			{name:'activity_date_low', value: activity_date_low},
			{name:'activity_date_high', value: activity_date_high},
			{name:'activity_deleted', value: activity_deleted }
			]
		});

    cj("#activities-selector").flexReload();
}

function checkSelection( field ) {
    var validationMessage = '';
    var validationField   = '';
    var successAction     = '';

    var fName = field.name;

    switch ( fName )  {
        case '_qf_CaseView_next' :
            validationMessage = 'Please select an activity set from the list.';
            validationField   = 'timeline_id';
            successAction     = "confirm('{/literal}{ts}Are you sure you want to add a set of scheduled activities to this case{/ts}{literal}?');";
            break;

        case 'new_activity' :
            validationMessage = 'Please select an activity type from the list.';
            validationField   = 'activity_type_id';
            successAction     = "window.location='{/literal}{$newActivityUrl}{literal}' + document.getElementById('activity_type_id').value";
            break;

        case 'case_report' :
            validationMessage = 'Please select a report from the list.';
            validationField   = 'report_id';
            successAction     = "window.location='{/literal}{$reportUrl}{literal}' + document.getElementById('report_id').value";
            break;
    }

    if ( document.getElementById( validationField ).value == '' ) {
        alert( validationMessage );
        return false;
    } else {
        return eval( successAction );
    }
}


function setSelectorClass( ) {
    cj("#activities-selector td:last-child").each( function( ) {
       cj(this).parent().attr( 'class', cj(this).text() );
    });
}
// FlexiGrid should be rebuilt only after data change (use in popap View Activity)
var flexReloads = false;

</script>
{/literal}

{literal}
<script type="text/javascript">
    hide('activities_show');
</script>
{/literal}

</div>
