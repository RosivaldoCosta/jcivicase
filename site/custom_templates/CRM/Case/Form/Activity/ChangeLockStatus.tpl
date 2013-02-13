{* Template for "Change Case Status" activities. *}
    <tr><td class="label">{$form.current_lock_status_id.label}</td><td>{$form.current_lock_status_id.html}</td></tr>     
    <tr><td class="label">{$form.lock_status_id.label}</td><td>{$form.lock_status_id.html}</td></tr>     
    {if $groupTree}
        <tr>
            <td colspan="2">{include file="CRM/Custom/Form/CustomData.tpl" noPostCustomButton=1}</td>
        </tr>
    {/if}
