{* Custom Data form*}
{foreach from=$userinfo_groupTree item=cd_edit key=group_id}
    <div id="{$cd_edit.name}_show_{$cgCount}" class="section-hidden section-hidden-border">
            <a href="#" onclick="cj('#{$cd_edit.name}_show_{$cgCount}').hide(); cj('#{$cd_edit.name}_{$cgCount}').show(); return false;"><img border="0" src="{$config->resourceBase}TreePlus.gif" class="action-icon" alt="{ts}open section{/ts}"/></a>
            <label><!--{$cd_edit.title}-->Client Profile</label><br />
    </div>

    <div id="{$cd_edit.name}_{$cgCount}" class="form-item">
	<fieldset>
	    <legend><a href="#" onclick="cj('#{$cd_edit.name}_{$cgCount}').hide(); cj('#{$cd_edit.name}_show_{$cgCount}').show(); return false;"><img border="0" src="{$config->resourceBase}TreeMinus.gif" class="action-icon" alt="{ts}close section{/ts}"/></a>{$cd_edit.title}</legend>
            {*if $cd_edit.help_pre}
                <div class="messages help">{$cd_edit.help_pre}</div>
            {/if*}
                {foreach from=$cd_edit.fields item=group}
    	        <table class="form-layout-compressed" style='float: left; width:23%'>
	                {foreach from=$group item=element}
	                <tr>
	                	<td class="label font-size12pt" style='white-space: nowrap;'>{$element.label}:</td>
	                	<td class='view-value font-size12pt bold'>
				{if $element.label eq 'Client' } 
					<a href="{crmURL p='civicrm/contact/view' q="reset=1&cid=`$contactId`"}">{$element.value}</a>
				{else}
					{$element.value}

				{/if}
				<!--
				{if $element.label eq 'Case Type'}
					{if !$caseNotEdit}
        					{if !$isManager}
        						<a href="{crmURL p='civicrm/case/activity' q="action=add&reset=1&cid=`$contactId`&caseid=`$caseId`&selectedChild=activity&atype=`$changeCaseTypeId`"}" title="Change case type (creates activity record)">
                						<img src="{$config->resourceBase}edit.png" border="0">
        						</a>
        					{/if}
					{/if}
				{/if}
				-->

				{if $element.label eq 'Status'}
					{if !$caseNotEdit}
						<a href="{crmURL p='civicrm/case/activity' q="action=add&reset=1&cid=`$contactId`&caseid=`$caseId`&selectedChild=activity&atype=`$changeCaseStatusId`"}" 						   title="Change case status (creates activity record)">
							<img src="{$config->resourceBase}edit.png" border="0">
						</a>
					{/if}
				{/if}

				
				</td>
	                </tr>
	                {/foreach}
	            </table>
                {/foreach}
            <div class="spacer" style='clear:both;'></div>
		<table>
			<td>
        			{include file="CRM/common/lastmodified.tpl"}
  			</td>  			
			<td>
				{include file="CRM/common/auditor.tpl"}
  			</td>
		</table>


            {*if $cd_edit.help_post}<div class="messages help">{$cd_edit.help_post}</div>{/if*}
        </fieldset>
        {if $cd_edit.is_multiple and ( ( $cd_edit.max_multiple eq '' )  or ( $cd_edit.max_multiple > 0 and $cd_edit.max_multiple >= $cgCount ) ) }
            <div id="add-more-link-{$cgCount}"><a href="javascript:buildCustomData('{$cd_edit.extends}',{if $cd_edit.subtype}'{$cd_edit.subtype}'{else}'{$cd_edit.extends_entity_column_id}'{/if}, '', {$cgCount}, {$group_id}, true );">{ts 1=$cd_edit.title}Add another %1 record{/ts}</a></div>
        {/if}
    </div>
    <div id="custom_group_{$group_id}_{$cgCount}"></div>

    <script type="text/javascript">
    {if $cd_edit.collapse_display eq 0 }
            cj('#{$cd_edit.name}_show_{$cgCount}').hide(); cj('#{$cd_edit.name}_{$cgCount}').show();
    {else}
            cj('#{$cd_edit.name}_show_{$cgCount}').show(); cj('#{$cd_edit.name}_{$cgCount}').hide();
    {/if}
    </script>
{/foreach}
