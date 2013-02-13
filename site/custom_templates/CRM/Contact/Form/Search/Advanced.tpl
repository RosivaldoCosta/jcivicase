{* Master tpl for Advanced Search *}

{include file="CRM/Contact/Form/Search/Intro.tpl"}

{assign var="showBlock" value="'searchForm'"}
{assign var="hideBlock" value="'searchForm_show','searchForm_hide'"}

<div id="searchForm_show" class="form-item">
  <a href="#" onclick="hide('searchForm_show'); show('searchForm'); return false;"><img src="{$config->resourceBase}TreePlus.gif" class="action-icon" alt="{ts}open section{/ts}" /></a>
  <label>
  {if $savedSearch}
    {ts 1=$savedSearch.name}Edit %1 Smart Group Criteria{/ts}
  {else}
    {ts}Edit Search Criteria{/ts}</label>
  {/if}
</div>
{capture assign=newCaseURL}{crmURL p="civicrm/contact/view/case" q="action=add&context=standalone&reset=1&depart=mct"}{/capture}

   <table border="0">
        <tr>
      <td>
        <a href="{$newCaseURL}" class="button"><span>&raquo; {ts}New Caller{/ts}</span></a>
      </td>
    </tr>
        </table>

<div id="searchForm">
    {include file="CRM/Contact/Form/Search/AdvancedCriteria.tpl"}
</div>

{if $rowsEmpty}
    {include file="CRM/Contact/Form/Search/EmptyResults.tpl"}
{/if}

{if $rows}
    {* Search request has returned 1 or more matching rows. Display results and collapse the search criteria fieldset. *}

    {if ! $ssID}
        {* Don't collapse search criteria when we are editing smart group criteria. *}
        {assign var="showBlock" value="'searchForm_show'"}
        {assign var="hideBlock" value="'searchForm'"}
    {/if}
    
    <fieldset>
    
       {* This section handles form elements for action task select and submit *}
       {include file="CRM/Contact/Form/Search/ResultTasks.tpl"}

       {* This section displays the rows along and includes the paging controls *}
       <p>
       {include file="CRM/Contact/Form/Selector.tpl"}
       </p>

    </fieldset>
    {* END Actions/Results section *}

{/if}

<script type="text/javascript">
    var showBlock = new Array({$showBlock});
    var hideBlock = new Array({$hideBlock});

{* hide and display the appropriate blocks *}
    on_load_init_blocks( showBlock, hideBlock );
</script>

