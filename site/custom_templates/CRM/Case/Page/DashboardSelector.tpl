{capture assign=expandIconURL}<img src="{$config->resourceBase}i/TreePlus.gif" alt="{ts}open section{/ts}"/>{/capture}
{strip}
<table class="caseSelector" width="100%">
  <tr class="columnheader" align="center">
    <th align="center"><a href="#">{ts}Active{/ts}</a></th>
    <th align="center"><a href="#">{ts}Unresolved{/ts}</a></th>
    <th>{ts}Follow-Up{/ts}</th>
    <th>{ts}IFIT{/ts}</th>
    <th>{ts}MCT{/ts}</th>
   </tr>



  <tr>
	<td>
	{counter start=0 skip=1 print=false}
        {foreach from=$rows item=row}
        {if $row.case_status eq 'Active'}
                             <a href="index2.php?option=com_civicrm&task=civicrm/contact/view/case&reset=1&id={$row.case_id}&cid={$row.contact_id}&action=view&context=dashboard&selectedChild=case">{$row.case_id}</a><br/>
        {/if}

        {/foreach}
	</td>
	<td>
	{counter start=0 skip=1 print=false}
        {foreach from=$rows item=row}
        {if $row.case_status eq 'Unresolved'}
                             <a href="index2.php?option=com_civicrm&task=civicrm/contact/view/case&reset=1&id={$row.case_id}&cid={$row.contact_id}&action=view&context=dashboard&selectedChild=case">{$row.case_id}</a><br/>
        {/if}

        {/foreach}
	</td>
	<td>
	{counter start=0 skip=1 print=false}
        {foreach from=$rows item=row}
        {if $row.case_status eq 'Follow-Up'}
              <a href="index2.php?option=com_civicrm&task=civicrm/contact/view/case&reset=1&id={$row.case_id}&cid={$row.contact_id}&action=view&context=dashboard&selectedChild=case">{$row.case_id}</a><br/>
        {/if}

        {/foreach}
	</td>
	<td>
	{counter start=0 skip=1 print=false}
        {foreach from=$rows item=row}
        {if $row.case_status eq 'IFIT'}
              <a href="index2.php?option=com_civicrm&task=civicrm/contact/view/case&reset=1&id={$row.case_id}&cid={$row.contact_id}&action=view&context=dashboard&selectedChild=case">{$row.case_id}</a><br/>
        {/if}

        {/foreach}
	</td>
	<td>
	{counter start=0 skip=1 print=false}
        {foreach from=$rows item=row}
                 {if $row.case_status eq 'MCT'}
                 <a href="index2.php?option=com_civicrm&task=civicrm/contact/view/case&reset=1&id={$row.case_id}&cid={$row.contact_id}&action=view&context=dashboard&selectedChild=case">{$row.case_id}</a><br/>
        {/if}

        {/foreach}
	</td>
{*<!--	<td>
	{counter start=0 skip=1 print=false}
        {foreach from=$rows item=row}
        {if $row.case_status eq 'Frequent Caller'}
                  <a href="index2.php?option=com_civicrm&task=civicrm/contact/view/case&reset=1&id={$row.case_id}&cid={$row.contact_id}&action=view&context=dashboard&selectedChild=case">{$row.case_id}</a><br/>
        {/if}

        {/foreach}
	</td>
-->*}
    </tr>

    {* Dashboard only lists 10 most recent casess. *}
    {if $context EQ 'dashboard' and $limit and $pager->_totalItems GT $limit }
      <tr class="even-row">
        <td colspan="10"><a href="{crmURL p='civicrm/case/search' q='reset=1'}">&raquo; {ts}Find more cases{/ts}... </a></td>
      </tr>
    {/if}

</table>
{/strip}

{* Build case details*}
{literal}
<script type="text/javascript">

function {/literal}{$list}{literal}CaseDetails( caseId, contactId )
{

  var dataUrl = {/literal}"{crmURL p='civicrm/case/details' h=0 q='snippet=4&caseId='}{literal}" + caseId +'&cid=' + contactId;
  cj.ajax({
            url     : dataUrl,
            dataType: "html",
            timeout : 5000, //Time in milliseconds
            success : function( data ){
                           cj( '#{/literal}{$list}{literal}CaseDetails' + caseId ).html( data );
                      },
            error   : function( XMLHttpRequest, textStatus, errorThrown ) {
                              console.error( 'Error: '+ textStatus );
                    }
         });
}
</script>
{/literal}