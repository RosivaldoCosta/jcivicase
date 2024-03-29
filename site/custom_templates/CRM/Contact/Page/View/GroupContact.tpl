<div id="groupContact">
<div class="view-content">
<div class="spacer"></div>
{if $groupCount eq 0 }
  <div class="messages status">
    <dl>
      <dt><img src="{$config->resourceBase}Inform.gif" alt="{ts}status{/ts}" /></dt>
      <dd>{ts}This contact does not currently belong to any groups.{/ts}</dd>
    </dl>
  </div>
 {/if}
    {include file="CRM/common/jsortable.tpl"}
  	{if $groupIn }
	<div><label>{ts}Current Groups{/ts}</label></div>
	{strip}
    <table id="current_group" class="display">
    <thead>
        <tr>
		<th>{ts}Group{/ts}</th>
		<th>{ts}Status{/ts}</th>
		<th>{ts}Date Added{/ts}</th>
		<th></th>
	</tr>
    </thead>
       	{foreach from=$groupIn item=row}
        <tr class="{cycle values="odd-row,even-row"}">
        	<td class="bold"><a href="{crmURL p='civicrm/group/search' q="reset=1&force=1&context=smog&gid=`$row.group_id`"}">{$row.title}</a></td>
	    	<td>{ts 1=$row.in_method}Added (by %1){/ts}</td> 
            <td>{$row.in_date|crmDate}</td>
	        <td>{if $permission EQ 'edit'}<a href="{crmURL p='civicrm/contact/view/group' q="gcid=`$row.id`&action=delete&st=o"}" onclick ="return confirm('{ts 1=$displayName 2=$row.title}Are you sure you want to remove %1 from %2?{/ts}');">[ {ts}Remove{/ts} ]</a>{/if}</td> 
        </tr>
     	{/foreach}
        </table>
	{/strip}
	{/if}
    
	{* Include 'add to new group' form if session has edit contact permissions *}
    {if $permission EQ 'edit'}
        {include file="CRM/Contact/Form/GroupContact.tpl"}	
    {/if}
    
    {if $groupPending }
        <div class="label status-pending">{ts}Pending{/ts}</div> 
        <div class="description">{ts}Joining these group(s) is pending confirmation by this contact.{/ts}</div>	
	{strip}
    <table id="pending_group" class="display">
    <thead>
	<tr>
		<th>{ts}Group{/ts}</th>
		<th>{ts}Status{/ts}</th>
		<th>{ts}Date Pending{/ts}</th>
		<th></th>
	</tr>
    </thead>
   	{foreach from=$groupPending item=row}
        <tr class="{cycle values="odd-row,even-row"}">
            <td class="bold"><a href="{crmURL p='civicrm/group/search' q="reset=1&force=1&context=smog&gid=`$row.group_id`"}">{$row.title}</a></td>
            <td>{ts 1=$row.pending_method}Pending (by %1){/ts}</td> 
            <td>{$row.pending_date|crmDate}</td>
            <td>{if $permission EQ 'edit'}<a href="{crmURL p='civicrm/contact/view/group' q="gcid=`$row.id`&action=delete&st=o"}" onclick ="return confirm('{ts 1=$displayName 2=$row.title}Are you sure you want to remove %1 from %2?{/ts}');">[ {ts}Remove{/ts} ]</a>{/if}</td> 
    	</tr>
     	{/foreach}
	</table>
	{/strip}
	{/if}

	{if $groupOut }
	<div class="label status-removed">{ts}Past Groups{/ts}</div>
    <div class="description">{ts 1=$displayName}%1 is no longer part of these group(s).{/ts}</div>
    {strip}
    <table id="past_group" class="display">
    <thead>
	<tr>
		<th>{ts}Group{/ts}</th>
		<th>{ts}Status{/ts}</th>
        <th>{ts}Date Added{/ts}</th>
		<th>{ts}Date Removed{/ts}</th>
		<th></th>
	</tr>
    </thead>
    {foreach from=$groupOut item=row}
        <tr class="{cycle values="odd-row,even-row"}">
            <td class="bold"><a href="{crmURL p='civicrm/group/search' q="reset=1&force=1&context=smog&gid=`$row.group_id`"}">{$row.title}</a></td>
	    	<td class="status-removed">{ts 1=$row.out_method}Removed (by %1){/ts}</td> 
            <td>{$row.date_added|crmDate}</td>
            <td>{$row.out_date|crmDate}</td>
	        <td>{if $permission EQ 'edit'}<a href="{crmURL p='civicrm/contact/view/group' q="gcid=`$row.id`&action=delete&st=i"}" onclick ="return confirm('{ts 1=$displayName 2=$row.title}Are you sure you want to add %1 back into %2?{/ts}');">[ {ts}Rejoin Group{/ts} ]</a>{/if}</td>
    	</tr>
    {/foreach}
   	</table>
	{/strip}
	{/if}
</div>
</div>