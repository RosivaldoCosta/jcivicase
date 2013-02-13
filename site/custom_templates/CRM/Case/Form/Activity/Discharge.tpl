{if $context ne 'caseActivity'}
    <tr><td class="label">{$form.case_type_id.label}<br />{help id="id-case_type" file="CRM/Case/Form/Case.hlp"}</td><td>{$form.case_type_id.html}</td></tr>
<tr><td class="label">{$form.case_status_id.label}</td><td>{$form.case_status_id.html}</td></tr>
{/if}

{include file="CRM/common/additionalBlocks.tpl"}
