<div id="additionalForms_show" class="section-hidden section-hidden-border">
  <a href="#" onclick="hide('additionalForms_show'); show('additionalForms'); return false;"><img src="{$config->resourceBase}/TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}Additional Forms{/ts}</label><br />
</div>
<div id="additionalForms" class="section-shown">
  <fieldset>
    <legend><a href="#" onclick="hide('additionalForms'); show('additionalForms_show'); return false;"><img src="{$config->resourceBase}/TreeMinus.gif" class="action-icon" alt="close section"/></a>
{ts}Additional Forms{/ts}</legend>
<div>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=100">Clinical Notes</a> <br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=101">Referrals Given</a> <br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=113">Supervisor Note</a><br/>
</div>
</div>

