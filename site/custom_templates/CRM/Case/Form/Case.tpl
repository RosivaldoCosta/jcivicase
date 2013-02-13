{* Base template for Open Case. May be used for other special activity types at some point ..
   Note: 1. We will include all the activity fields here however each activity type file may build (via php) only those required by them.
         2. Each activity type file can include its case fields in its own template, so that they will be included during activity edit.
*}
{if $action neq 8 && $action neq 32768}
<div class="html-adjust">{$form.buttons.html}</div>
{/if}

<fieldset><legend>{if $action eq 8}{ts}Delete Case{/ts}{elseif $action eq 32768}{ts}Restore Case{/ts}{else}{$activityType}{/if}</legend>
<table class="form-layout">
{if $action eq 8 or $action eq 32768 }
      <div class="messages status">
        <dl>
          <dt><img src="{$config->resourceBase}Inform.gif" alt="{ts}status{/ts}" /></dt>
          <dd>
          {if $action eq 8}
            {ts}Click Delete to move this case and all associated activities to the Trash.{/ts}
          {else}
            {ts}Click Restore to retrieve this case and all associated activities from the Trash.{/ts}
          {/if}
          </dd>
       </dl>
      </div>
{else}
{if $clientName}
    <tr><td class="label font-size12pt">{ts}Client{/ts}</td><td class="font-size12pt bold view-value">{$clientName}</td></tr>
{elseif !$clientName and $action eq 1}
    <tr class="form-layout-compressed" border="0">
        {if $context eq 'standalone'}
            {include file="CRM/Contact/Form/NewContact.tpl"}
        {/if}
    </tr>
{/if}
{* activity fields *}
{if $form.medium_id.html and $form.activity_location.html}
    <tr>
        <td class="label">{$form.medium_id.label}</td>
        <td class="view-value">{$form.medium_id.html}&nbsp;&nbsp;&nbsp;<!--{$form.activity_location.label} &nbsp;{$form.activity_location.html}--></td>
    </tr>
{/if}

{if $form.activity_details.html}
    <tr>
        <td class="label">{$form.activity_details.label}<br />{help id="id-details" file="CRM/Case/Form/Case.hlp"}</td>
        <td class="view-value">{$form.activity_details.html}{*$form.activity_details.html|crmReplace:class:huge40*}</td>
    </tr>
{/if}
{if $form.activity_subject.html}
    <tr><td class="label">Case Title:<br />{help id="id-activity_subject" file="CRM/Case/Form/Case.hlp"}</td><td>{$form.activity_subject.html}</td></tr>
{/if}

{* inject activity type-specific form fields *}
{if $activityTypeFile}
    {include file="CRM/Case/Form/Activity/$activityTypeFile.tpl"}
{/if}

{* custom data group *}
{if $groupTree}
    <tr>
       <td colspan="2">{include file="CRM/Custom/Form/CustomData.tpl"}</td>
    </tr>
{/if}
<tr>
              <td colspan="2">
                <div id="follow-up_show" class="section-hidden section-hidden-border">
                 <a href="#" onclick="hide('follow-up_show'); show('follow-up'); return false;"><img border="0" src="{$config->resourceBase}TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}Schedule Next Task{/ts}</label><br />
                </div>

                <div id="follow-up" class="section-shown">
                <fieldset><legend><a href="#" onclick="hide('follow-up'); show('follow-up_show'); return false;"><img border="0" src="{$config->resourceBase}TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}Schedule Next Task{/ts}</legend>
                    <table class="form-layout-compressed">
                        <tr><td class="label">{ts}Schedule Next Task{/ts}</td>
                            <td>{$form.followup_activity_type_id.html}&nbsp;{$form.interval.label}&nbsp;{$form.interval.html}&nbsp;{$form.interval_unit.html}</td>
                        </tr>
                        <tr>
                           <td class="label">{$form.followup_activity_subject.label}</td>
                           <td>{$form.followup_activity_subject.html}</td>
                        </tr>
                    </table>
                </fieldset>
                </div>
              </td>
           </tr>
{if $form.duration.html}
    <!--<tr>
      <td class="label">{$form.duration.label}</td>
      <td class="view-value">
        {$form.duration.html}
         <span class="description">{ts}Total time spent on this activity (in minutes).{/ts}
      </td>
</tr>-->
{/if}


{/if}

</table>
</fieldset>
<div class="html-adjust">{$form.buttons.html}</div>
{literal}
<script type="text/javascript">
//show('follow-up_show');
hide('follow-up_show');
</script>
{/literal}
{* include jscript to warn if unsaved form field changes *}
{include file="CRM/common/formNavigate.tpl"}
{* include file="CRM/common/customData.tpl" *}
{if $activityType eq 'Intake'}
{* include jscript to handle auto calculating age *}
{include file="CRM/common/autocalcAge.tpl"}
{include file="CRM/common/autoFormatPhone.tpl"}
{include file="CRM/common/autosave.tpl"}
{* include jscript to handle create new Lethality Rating Sheet *}
{include file="CRM/common/newLethalityStep1.tpl"}
{/if}
