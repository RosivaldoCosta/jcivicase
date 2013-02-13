{* this template is used for adding/editing activities for a case. *}
{if $pdf}
	{include file="CRM/Case/Form/ActivityPDF.tpl"}
{else}

	{include file="CRM/Custom/Form/CustomDataTopUser.tpl"}
	{if $cdType }
	   {include file="CRM/Custom/Form/CustomData.tpl"}
	{else}
		{if $action neq 8 and $action  neq 32768 }

	{* added onload javascript for source contact*}
	{literal}

	<script type="text/javascript">


	var assignee_contact = '';

	{/literal}
	{if isset($depart) && $depart != ''}
	var depart='{$depart}';
	{/if}

	{if $assigneeContactCount}
	{foreach from=$assignee_contact key=id item=name}
		 {literal} assignee_contact += '{"name":"'+{/literal}"{$name}"{literal}+'","id":"'+{/literal}"{$id}"{literal}+'"},';{/literal}
	{/foreach}
	{literal} eval( 'assignee_contact = [' + assignee_contact + ']'); {/literal}
	{/if}
	{literal}

	cj(document).ready( function( ) {
		if (typeof(depart) != 'undefined')
		{
			cj('input[value='+depart+'][name=custom_594_-1]' ).attr('checked','checked');
		}

			cj('.label, #crm-container label, .form-item label').css('font-weight', 'bold');
			cj('.columnheader th').css('font-weight', 'bold');
			cj('#Client_Profile_ .label').attr('style', 'font-weight: normal !important');
			
			//cj('.form-item label').attr('style', '"font-weight: bold !important"');
			
			

			/*if ( cj('#crm-content form').is("form") )
			{
				getAllInputs();
			}*/

	{/literal}

	{if $source_contact and $admin and $action neq 4}
	{literal} cj( '#source_contact_id' ).val( "{/literal}{$source_contact}{literal}");{/literal}
	{/if}
	{literal}

	eval( 'tokenClass = { tokenList: "token-input-list-facebook", token: "token-input-token-facebook", tokenDelete: "token-input-delete-token-facebook", selectedToken: "token-input-selected-token-facebook", highlightedToken: "token-input-highlighted-token-facebook", dropdown: "token-input-dropdown-facebook", dropdownItem: "token-input-dropdown-item-facebook", dropdownItem2: "token-input-dropdown-item2-facebook", selectedDropdownItem: "token-input-selected-dropdown-item-facebook", inputToken: "token-input-input-token-facebook" } ');

	var sourceDataUrl = "{/literal}{$dataUrl}{literal}";
	var tokenDataUrl  = "{/literal}{$tokenUrl}{literal}";

	var hintText = "{/literal}{ts}Type in a partial or complete name or email address of an existing contact.{/ts}{literal}";
	cj( "#assignee_contact_id").tokenInput( tokenDataUrl, { prePopulate: assignee_contact, classes: tokenClass, hintText: hintText });
	cj( 'ul.token-input-list-facebook, div.token-input-dropdown-facebook' ).css( 'width', '450px' );
	cj( "#source_contact_id").autocomplete( sourceDataUrl, { width : 180, selectFirst : false, matchContains:true
								}).result( function(event, data, formatted) { cj( "#source_contact_qid" ).val( data[1] );
								}).bind( 'click', function( ) { cj( "#source_contact_qid" ).val(''); });
	});

	</script>
	{/literal}

		{/if}
	{if $activityTypeName ne 'Change Case Status' &&
		$newLethality ne true}
	{include file="CRM/common/autosave.tpl"}
	{/if}
		<fieldset>
			<legend>
			   {if $action eq 8}
				  {ts}Delete{/ts}
			   {elseif $action eq 4}
				  {ts}View{/ts}
			   {elseif $action eq 32768}
				  {ts}Restore{/ts}
			   {/if}
			   {$activityTypeName}
			</legend>
			<table class="form-layout">
			   {if $action eq 8 or $action eq 32768 }
				<div class="messages status">
				  <dl>
					 <dt><img src="{$config->resourceBase}Inform.gif" alt="{ts}status{/ts}" /></dt>
					 <dd>
					 {if $action eq 8}
						{ts 1=$activityTypeName}Click Delete to move this &quot;%1&quot; activity to the Trash.{/ts}
					 {else}
						{ts 1=$activityTypeName}Click Restore to retrieve this &quot;%1&quot; activity from the Trash.{/ts}
					 {/if}
					 </dd>
				  </dl>
				</div>
			   {else}
				{if $activityTypeDescription }
			   <tr>
				  <div id="help">{$activityTypeDescription}</div>
			   </tr>
				{/if}
			   <tr>
				  <td class="label font-size12pt">{ts}Case{/ts}</td>
				  <td class="view-value font-size12pt bold"><a href="index2.php?option=com_civicrm&task=civicrm/contact/view/case&reset=1&id={$caseId}&cid={$contactId}&action=view">{$caseId}</a></td>
			   </tr>
			   <tr>
				  <td class="label font-size12pt">{ts}Client{/ts}</td>
				  <td class="view-value font-size12pt bold">{$client_name|escape}</td>
			   </tr>
			   <tr>
				  <td class="label">{ts}Task/Form Type{/ts}</td>
				  <td class="view-value bold">{$activityTypeName|escape}</td>
			   </tr>
			   <tr>
				  <td class="label">{$form.source_contact_id.label}</td>
				  <td class="view-value"> {if $admin}{$form.source_contact_id.html}{/if}</td>
				</tr>
	<tr>
		{if $form.completedby_contact_id}
					<td class="label">{$form.completedby_contact_id.label}</td>
					<td class="view-value">
						{$form.completedby_contact_id.html}
					</td>
				 </tr>
		{/if}

				<!--<tr>
					<td class="label">{ts}Assigned To {/ts}</td>
					<td>{$form.assignee_contact_id.html}
						{edit}<span class="description">
							   {ts}You can optionally assign this activity to someone.{/ts}<br />
							   {ts}A copy of this activity will be emailed to each Assignee.{/ts}</span>
						{/edit}
					</td>
				</tr>
			-->
		   {if $activityTypeFile eq 'OpenCase'}
			   <tr>
				  <td class="label">{$form.details.label}{*Reason for Contact:*}</td><td class="view-value">{$form.details.html}{*$form.details.html|crmReplace:class:huge*}</td>
			   </tr>
		   {/if}

				{* Include special processing fields if any are defined for this activity type (e.g. Change Case Status / Change Case Type). *}
				{if $activityTypeFile}
					{include file="CRM/Case/Form/Activity/$activityTypeFile.tpl"}
				{/if}
			{if $activityTypeFile neq 'ChangeCaseStartDate'}
			{if $activityTypeFile eq 'OpenCase'}
						<tr>
							<td class="label">Case Title:</td><td class="view-value">{$form.subject.html}</td>
						</tr>
			{else}
						<tr>
							<td class="label">Task Details:</td><td class="view-value">{$form.subject.html}</td>
						</tr>

			{/if}
			{/if}
			   <!--<tr>
				  <td class="label">{$form.medium_id.label}</td>
				  <td class="view-value">{$form.medium_id.html}&nbsp;&nbsp;&nbsp;{$form.location.label} &nbsp;{$form.location.html}</td>
			   </tr>
			-->
			   <tr>
				  <td class="label">{if $isTask}Date Due{else}{$form.activity_date_time.label}{/if}</td>
				  {if $activityTypeName eq 'Lethality'}
					<!--  <td class="view-value">{include file="CRM/common/jcalendar.tpl" elementName='activity_date_time'}</td> -->
					
					<td class="view-value">
						<input type="text" class="dateplugin" 
							id="activity_date_lethal_time" value="{$smarty.now|date_format:'%m/%d/%Y'}" 
							name="activity_date_time" readonly="1" format="mm/dd/yy" 
							endoffset="10" startoffset="20" timeformat="2" addtime="1" formattype="activityDateTime">
						<input type="text" class="hiddenElement" id="activity_date_lethal_time_hidden" 
							name="activity_date_time_hidden">&nbsp;&nbsp;
						<label for="activity_date_time_time">  
							Time
						<span title="This field is required." class="marker">*</span>
						</label>&nbsp;&nbsp;
						<input type="text" class="six" id="activity_date_lethal_time_time" 
							value="{$smarty.now|date_format:'%H:%M:%S'}" name="activity_date_time_time" timeformat="1">
						(<a href="javascript:clearDateTime( 'activity_date_lethal_time' );">clear</a>)&nbsp;
					</td>

					<script type="text/javascript">

						  var element_date   = "#activity_date_lethal_time"; 
						  {if $timeElement}
							  var element_time  = "#activity_date_lethal_time_time";
							  var time_format   = cj( element_time ).attr('timeFormat');
							  {literal}
								  cj(element_time).timeEntry({ show24Hours : time_format });
							  {/literal}
						  {/if}
						  var currentYear = new Date().getFullYear();
						  var date_format = cj( element_date ).attr('format');
						  var alt_field   = 'input#activity_date_lethal_time_hidden';
						  var yearRange   = currentYear - parseInt( cj( element_date ).attr('startOffset') ); 
							  yearRange  += ':';
							  yearRange  += currentYear + parseInt( cj( element_date ).attr('endOffset'  ) ); 
						  {literal}
			 
						  cj(element_date).datepicker({
														closeAtTop        : true, 
														dateFormat        : date_format,
														changeMonth       : true,
														changeYear        : true,
														altField          : alt_field,
														altFormat         : 'mm/dd/yy',
														yearRange         : yearRange
													});
						
						  cj(element_date).click( function( ) {
							  hideYear( this );
						  });  
						  cj('.ui-datepicker-trigger').click( function( ) {
							  hideYear( cj(this).prev() );
						  });  

						
						function hideYear( element ) {
							var format = cj( element ).attr('format');
							if ( format == 'dd-mm' || format == 'mm/dd' ) {
								cj(".ui-datepicker-year").css( 'display', 'none' );
							}
						}
						
						function clearDateTime( element ) {
							cj('input#' + element + ',input#' + element + '_time').val('');
						}
						{/literal}
					</script>             
					
				  {else}
					<td class="view-value">{include file="CRM/common/jcalendar.tpl" elementName='activity_date_time'}</td>
				  {/if}
			   </tr>
			   <tr>
				  <td colspan="2"><div id="customData"></div></td>
			   </tr>
			   <tr>
				  <td colspan="2">{include file="CRM/Form/attachment.tpl"}</td>
			   </tr>
			   {if $searchRows} {* We've got case role rows to display for "Send Copy To" feature *}
				<tr>
					<td colspan="2">
						<div id="sendcopy_show" class="section-hidden section-hidden-border">
							<a href="#" onclick="hide('sendcopy_show'); show('sendcopy'); return false;"><img border="0" src="{$config->resourceBase}TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}Send a Copy{/ts}</label><br />
						</div>

						<div id="sendcopy" class="section-shown">
						<fieldset><legend><a href="#" onclick="hide('sendcopy'); show('sendcopy_show'); return false;"><img border="0" src="{$config->resourceBase}TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}Send a Copy{/ts}</legend>
						<div class="description">{ts}Email a complete copy of this activity record to other people involved with the case. Click the top left box to select all.{/ts}</div>
					   {strip}
					   <table>
						  <tr class="columnheader">
							  <th>{$form.toggleSelect.html}&nbsp;</th>
							  <th>{ts}Case Role{/ts}</th>
							  <th>{ts}Name{/ts}</th>
							  <th>{ts}Email{/ts}</th>
						   </tr>
						   {foreach from=$searchRows item=row key=id}
						   <tr class="{cycle values="odd-row,even-row"}">
							   <td>{$form.contact_check[$id].html}</td>
							   <td>{$row.role}</td>
							   <td>{$row.display_name}</td>
							   <td>{$row.email}</td>
						   </tr>
						   {/foreach}
					   </table>
					   {/strip}
					  </fieldset>
					  </div>
					</td>
				</tr>
				{/if}
		{if $activityTypeName eq "Signature Captured" || 
			($isPrint && $activityTypeName eq "Authorization To Release") || 
			($isPrint && $activityTypeName eq "Consent For Services")
			}

			   <tr>
				  <td colspan="2">
					<div id="signature-capture1_show" class="section-hidden section-hidden-border">
					 <a href="#" onclick="hide('signature-capture1_show'); show('signature-capture1'); return false;"><img border="0" src="{$config->resourceBase}TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}Signature Capture{/ts}</label><br />
					</div>

					<div id="signature-capture1" class="section-shown">
					<fieldset><legend><a href="#" onclick="hide('signature-capture1'); show('signature-capture1_show'); return false;"><img border="0" src="{$config->resourceBase}TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}Signature Capture {/ts}</legend>
						<table class="form-layout-compressed">
							<tr>
							   <td class="label">
				  </td>
							   <td> {include file="CRM/common/signatureCaptureFlash.tpl"}</td>
							</tr>
						</table>
					</fieldset>
					</div>
				  </td>
			   </tr>
		{if $isFlash }
		{literal}
			<script type="text/javascript">
				show('signature-capture1');
				hide('signature-capture1_show');
			</script>
		{/literal}
		{else}
		{literal}
			<script type="text/javascript">
				hide('signature-capture1');
				show('signature-capture1_show');
			</script>
		{/literal}
		{/if}
		{/if}
		{if $activityTypeName eq "Signature Captured" || 
			($isPrint && $activityTypeName eq "Human Rights Notification") ||
			($isPrint && $activityTypeName eq "Informed Consent for Medication") ||
			($isPrint && $activityTypeName eq "Privacy Practices") ||
			($isPrint && $activityTypeName eq "Informed Consent") 
			}
			   <tr>
				  <td colspan="2">
					<div id="signature-capture2_show" class="section-hidden section-hidden-border">
					 <a href="#" onclick="hide('signature-capture2_show'); show('signature-capture2'); return false;"><img border="0" src="{$config->resourceBase}TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}Signature Pad{/ts}</label><br />
					</div>

					<div id="signature-capture2" class="section-shown">
					<a href="#" onclick="hide('signature-capture2'); show('signature-capture2_show'); return false;"><img border="0" src="{$config->resourceBase}TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}Signature Pad{/ts}
						<table class="form-layout-compressed">
							<tr><td class="label"></td>
								<td>
						{include file="CRM/common/signatureCaptureNPAPI.tpl"}
					</td>
							</tr>
						</table>
					
					</div>
				  </td>
			   </tr>
		{if $isFlash}
		{literal}
			<script type="text/javascript">
				hide('signature-capture2');
				show('signature-capture2_show');
			</script>
		{/literal}
		{else}
		{literal}
			<script type="text/javascript">
				show('signature-capture2');
				hide('signature-capture2_show');
			</script>
		{/literal}
		{/if}
		{/if}

			   <tr>
				  <td class="label">{$form.status_id.label}</td><td class="view-value">{$form.status_id.html}</td>
			   </tr>
		   <!--<tr>
				  <td class="label">{$form.priority_id.label}</td><td class="view-value">{$form.priority_id.html}</td>
			   </tr>-->
			   {/if}
		{if !$isPrint}
			   <tr>
				  <td>&nbsp;</td><td class="buttons">{$form.buttons.html}</td>
				</tr>
		{/if}
			</table>
		</fieldset>
		{if $activityTypeName eq "Signature Captured" }
		<div id="div_msg" title=""/>
		{literal}
		<script>
				function show_dialog_msg($msg, $title){
				
					div_msg = cj('#div_msg');
					
					//if( div_msg.length == 0 ){
						//div_msg = cj('<div id="div_msg" title=""></div>');
						div_msg.attr('title', $title);
						div_msg.html($msg);
						
						div_msg.appendTo('body'); 
					//}
					
					div_msg.dialog({
						modal: true,
						width: 400,
						buttons: {
							Ok: function() {
								cj( this ).dialog( "close" );
							}
						}
					});
				}
				
				function popupSuccess(){
					show_dialog_msg("Signature successfully saved!<br/>Be sure to save the activity to update the Case History.", "Message");
				}
			</script>
			{/literal}
		{/if}
		{if $action eq 1 or $action eq 2}
			{*include custom data js file*}
			{include file="CRM/common/customData.tpl"}
			{literal}
			<script type="text/javascript">
				cj(document).ready(function() {
					{/literal}
	//		            buildCustomData( '{$customDataType}', false,  false, false, false, false,'{$depart}' );
					{if $customDataSubType}
						buildCustomData( '{$customDataType}', {$customDataSubType}, false,  false, false, false,'{$depart}');
					{else}
						buildCustomData( '{$customDataType}', false,  false, false, false, false,'{$depart}' );
					{/if}
					{literal}
				});
			</script>
			{/literal}
		{/if}

		{if $action neq 8 and $action neq 32768}
			<script type="text/javascript">
				{if $searchRows}
					hide('sendcopy');
					show('sendcopy_show');
				{/if}

				//hide('follow-up');
				//show('follow-up_show');
				//hide('signature-capture1');
				//show('signature-capture1_show');
				//hide('signature-capture2');
				//show('signature-capture2_show');

			</script>
		{/if}

		{* include jscript to warn if unsaved form field changes *}
		{include file="CRM/common/formNavigate.tpl"}

	{/if } {* end of main if block*}
	</script>

	{if $activityTypeFile eq 'OpenCase' or $activityTypeName eq 'Phone Contact' or $activityTypeName eq 'Authorization To Release'}
	{* include jscript to handle auto formatting phone numbers *}
	{include file="CRM/common/autoFormatPhone.tpl"}

	{/if}


	{if $activityTypeName eq 'MCT Dispatch'}
	{* include jscript to handle auto calculating total time and total call time*}
	{include file="CRM/common/autocalcTotalTime.tpl"}
	{/if}

	{if $activityTypeName eq 'Lethality'}
	{* include jscript to handle auto calculating points *}
	{include file="CRM/common/autocalc.tpl"}
	{/if}

	{if $activityTypeName eq 'Discharge Summary'}
	{* include jscript to handle auto calculating points *}
	{include file="CRM/common/autocalcSatisfaction.tpl"}
	{/if}

	{if $activityTypeName eq 'Intake'}
	{* include jscript to handle auto calculating age *}
	{include file="CRM/common/autocalcAge.tpl"}
	{* include jscript to handle create new Lethality Rating Sheet *}
	{include file="CRM/common/newLethalityStep2.tpl"}
	{literal}
	<script type="text/javascript">
	cj(document).ready( function( ) {
		cj('#ui-datepicker-div').css('z-index', '2000');
	});
	</script>
	{/literal}
	{/if}

	{if $activityTypeName eq 'Care Plan'}
	{literal}
	<script type="text/javascript">
	cj(document).ready( function( ) {
			cj('[name^=custom_102_]').focus(function () {
				if(cj(this).attr('value').trim()=='Enter the plan of care...')cj(this).attr('value','');
			});
			cj('[name^=custom_102_]').blur(function () {
				if(cj(this).attr('value').trim()=='')cj(this).attr('value','Enter the plan of care...');
			});
			
	});



	</script>
	{/literal}
	{/if}
{/if}
