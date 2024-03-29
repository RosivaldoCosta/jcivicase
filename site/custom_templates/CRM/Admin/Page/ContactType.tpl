<div id="help">
    {ts}CiviCRM comes with 3 basic (built-in) contact types: Individual, Household, and Organization. You can create additional contact types based on these types to further differentiate contacts (for example you might create Student, Parent, Staff, and /or Volunteer types from the basic Individual type...).{/ts} {help id="id-contactSubtype-intro"}
</div>

{if $action eq 1 or $action eq 2 or $action eq 8}
   {include file="CRM/Admin/Form/ContactType.tpl"}
{/if}

{if $rows}
<div>
    {strip}
    {* handle enable/disable actions*}	
    {include file="CRM/common/enableDisable.tpl"}
    {include file="CRM/common/jsortable.tpl"}
    <table id="options" class="display">
    <thead>
    <tr>
        <th>{ts}Contact Type{/ts}</th>
        <th>{ts}Based On{/ts}</th>
        <th id="nosort">{ts}Description{/ts}</th>
        <th></th>
    </tr>
    </thead>
    {foreach from=$rows item=row}
    	<tr id="row_{$row.id}" class="{cycle values="odd-row,even-row"} {$row.class}{if NOT $row.is_active} disabled{/if}">
        <td>{$row.label}</td>
        <td>{if $row.parent}{$row.parent_label}{else}{ts}(built-in){/ts}{/if}</td>
        <td>{$row.description}</td>
        <td>{$row.action|replace:'xx':$row.id}</td>
    </tr>
    {/foreach}
    </table>
    {/strip}
    {if $action ne 1 and $action ne 2}
    <div class="action-link">
	<a href="{crmURL q="action=add&reset=1"}" class="button"><span>&raquo; {ts}New Contact Type{/ts}</span></a>
    </div>
    {/if}
</div>
{else}
    <div class="messages status">
     <dl>
        <dt><img src="{$config->resourceBase}Inform.gif" alt="{ts}status{/ts}"/></dt>
        {capture assign=crmURL}{crmURL p='civicrm/admin/ContactType' q="action=add&reset=1"}{/capture}
        <dd>{ts 1=$crmURL}There are currently no Contact Types entered. You can <a href='%1'>add one</a>.{/ts}</dd>
     </dl>
    </div>    
{/if}