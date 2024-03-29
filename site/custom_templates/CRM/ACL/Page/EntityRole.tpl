{capture assign=aclURL}{crmURL p='civicrm/acl' q='reset=1'}{/capture}
{capture assign=rolesURL}{crmURL p='civicrm/admin/options/acl_role' q='group=acl_role&reset=1'}{/capture}
{capture assign=docLink}{docURL page='Access Control' text='Access Control Documentation'}{/capture}
<div id="help">
    <p>{ts 1=$docLink}ACLs allow you control access to CiviCRM data. An ACL consists of an <strong>Operation</strong> (e.g. 'View' or 'Edit'), a <strong>set of data</strong> that the operation can be performed on (e.g. a group of contacts), and a <strong>Role</strong> that has permission to do this operation. Refer to the %1 for more info.{/ts}</p>
    <p>{ts 1=$aclURL 2=$rolesURL}An ACL Role represents a collection ACL&rsquo;s (permissions). You can assign roles to groups of CiviCRM contacts who are users of your site below. You can add or modify ACLs <a href='%1'>here</a>. You can create additional ACL Roles <a href='%2'>here</a>.{/ts}</p>
</div>

{if $action eq 1 or $action eq 2 or $action eq 8}
   {include file="CRM/ACL/Form/EntityRole.tpl"}
{/if}

{if $rows}
<div id="ltype">
    {strip}
	{* handle enable/disable actions*}
 	{include file="CRM/common/enableDisable.tpl"}
    {include file="CRM/common/jsortable.tpl"}   
    <table id="options" class="display">
        <thead>
        <tr class="columnheader">
            <th id="sortable">{ts}ACL Role{/ts}</th>
            <th>{ts}Assigned To{/ts}</th>
            <th>{ts}Enabled?{/ts}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$rows item=row}
	    <tr id="row_{$row.entity_id}"class="{cycle values="odd-row,even-row"} {$row.class}{if NOT $row.is_active} disabled{/if}">
	        <td>{$row.acl_role}</td>	
	        <td>{$row.entity}</td>	
	        <td id="row_{$row.id}_status">{if $row.is_active eq 1} {ts}Yes{/ts} {else} {ts}No{/ts} {/if}</td>
	        <td>{$row.action}</td>
        </tr>
        {/foreach}
        </tbody>
    </table>
    {/strip}

        {if $action ne 1 and $action ne 2}
	    <div class="action-link">
            <a href="{crmURL q="action=add&reset=1"}" id="newACL" class="button"><span>&raquo; {ts}New Role Assignment{/ts}</span></a>
        </div>
        {/if}
</div>
{elseif $action ne 1 and $action ne 2 and $action ne 8}
    <div class="messages status">
    <dl>
        <dt><img src="{$config->resourceBase}Inform.gif" alt="{ts}status{/ts}"/></dt>
        {capture assign=crmURL}{crmURL q="action=add&reset=1"}{/capture}
        <dd>{ts 1=$crmURL}There are no Role Assignments. You can <a href='%1'>add one</a> now.{/ts}</dd>
        </dl>
    </div>    
{/if}
