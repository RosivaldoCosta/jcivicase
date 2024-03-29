{* Custom Data view mode*}
{assign var="showEdit" value=1}

{foreach from=$viewCustomData item=customValues key=customGroupId}
    {foreach from=$customValues item=cd_edit key=cvID}
	<table class="no-border">
	    {assign var='index' value=$groupId|cat:"_$cvID"}
	    {if $editOwnCustomData or ($showEdit and $editCustomData and $groupId)}	
		<tr>
		    <td>
			<a href="{crmURL p="civicrm/contact/view/cd/edit" q="tableId=`$contactId`&cid=`$contactId`&groupId=`$groupId`&action=update&reset=1"}" class="button" style="margin-left: 6px;"><span>&raquo; {ts 1=$cd_edit.title}Edit %1{/ts}</span></a><br/><br/>
		    </td>
		</tr>      
	    {/if}
	    {assign var="showEdit" value=0}
	    <tr id="statusmessg_{$index}" class="hiddenElement">
		<td><span class="success-status"></span></td>
	    </tr>
	    <tr>
		<td id="{$cd_edit.name}_show_{$index}" class="section-hidden section-hidden-border">     
		    <a href="#" onclick="hide('{$cd_edit.name}_show_{$index}'); show('{$cd_edit.name}_{$index}'); return false;"><img src="{$config->resourceBase}TreePlus.gif" class="action-icon" alt="{ts}open section{/ts}"/></a><label>{$cd_edit.title}</label>{if $groupId and $cvID and $editCustomData}&nbsp; <a href="javascript:showDelete( {$cvID}, '{$cd_edit.name}_show_{$index}', {$customGroupId}, {$contactId} );"><img title="delete this record" src="{$config->resourceBase}delete.png" class="action-icon" alt="{ts}delete this record{/ts}" /></a>{/if}<br />
		</td>
	    </tr>
	    
	    <tr>
		<td id="{$cd_edit.name}_{$index}" class="section-shown form-item">		    
		    <fieldset>
			<legend><a href="#" onclick="hide('{$cd_edit.name}_{$index}'); show('{$cd_edit.name}_show_{$index}'); return false;"><img src="{$config->resourceBase}TreeMinus.gif" class="action-icon" alt="{ts}close section{/ts}"/></a>{$cd_edit.title}{if $groupId and $cvID and $editCustomData}&nbsp;&nbsp;&nbsp;<a href="javascript:showDelete( {$cvID}, '{$cd_edit.name}_{$index}', {$customGroupId}, {$contactId} );"><img title="delete this record" src="{$config->resourceBase}delete.png" class="action-icon" alt="{ts}delete this record{/ts}" /></a>{/if}</legend>
	
			{foreach from=$cd_edit.fields item=element key=field_id}
			    <table class="view-layout">
				<tr>
				    {if $element.options_per_line != 0}
					<td class="label">{$element.field_title}</td>
					<td class="html-adjust">
					    {* sort by fails for option per line. Added a variable to iterate through the element array*}
					    {foreach from=$element.field_value item=val}
						{$val}<br/>
					    {/foreach}
					</td>
				    {else}
					<td class="label">{$element.field_title}</td>
					{if $element.field_type == 'File'}
					    {if $element.field_value.displayURL}
						<td class="html-adjust"><a href="javascript:imagePopUp('{$element.field_value.displayURL}')" ><img src="{$element.field_value.displayURL}" height = "100" width="100"></a></td>
					    {else}
						<td class="html-adjust"><a href="{$element.field_value.fileURL}">{$element.field_value.fileName}</a></td>
					    {/if}
					{else}
					    <td class="html-adjust">{$element.field_value}</td>
					{/if}
				    {/if}
				</tr>
			    </table>
			{/foreach}
		    </fieldset>
		</td>
	    </tr>
	</table>

	<script type="text/javascript">
	{if $cd_edit.collapse_display eq 0 }
		hide("{$cd_edit.name}_show_{$index}"); show("{$cd_edit.name}_{$index}");
	{else}
		show("{$cd_edit.name}_show_{$index}"); hide("{$cd_edit.name}_{$index}");
	{/if}
	</script>
    {/foreach}
{/foreach}

{*currently delete is available only for tab custom data*}
{if $groupId}
<script type="text/javascript">
    {literal}
    function hideStatus( valueID, groupID ) {
        cj( '#statusmessg_'  + groupID + '_' + valueID ).hide( );
    }
    function showDelete( valueID, elementID, groupID, contactID ) {
        var confirmMsg = '{/literal}{ts}Are you sure you want to delete this record?{/ts}{literal} &nbsp; <a href="javascript:deleteCustomValue( ' + valueID + ',\'' + elementID + '\',' + groupID + ',' + contactID + ' );" style="text-decoration: underline;">{/literal}{ts}Yes{/ts}{literal}</a>&nbsp;&nbsp;&nbsp;<a href="javascript:hideStatus( ' + valueID + ', ' +  groupID + ' );" style="text-decoration: underline;">{/literal}{ts}No{/ts}{literal}</a>';
        cj( 'tr#statusmessg_' + groupID + '_' + valueID ).show( ).children().find('span').html( confirmMsg );
    }
    function deleteCustomValue( valueID, elementID, groupID, contactID ) {
        var postUrl = {/literal}"{crmURL p='civicrm/ajax/customvalue' h=0 }"{literal};
        cj.ajax({
          type: "POST",
          data:  "valueID=" + valueID + "&groupID=" + groupID +"&contactId=" + contactID,    
          url: postUrl,
          success: function(html){
              cj( '#' + elementID ).hide( );
              var resourceBase   = {/literal}"{$config->resourceBase}"{literal};
              var successMsg = '{/literal}{ts}The selected record has been deleted.{/ts}{literal} &nbsp;&nbsp;<a href="javascript:hideStatus( ' + valueID + ',' + groupID + ');"><img title="{/literal}{ts}close{/ts}{literal}" src="' +resourceBase+'i/close.png"/></a>';
              cj( 'tr#statusmessg_'  + groupID + '_' + valueID ).show( ).children().find('span').html( successMsg );
			  var element = cj( '.ui-tabs-nav #tab_custom_' + groupID + ' a' );
			  cj(element).html(cj(element).attr('title') + ' ('+ html+') ');
          }
        });
    }
    {/literal}
</script>
{/if}

