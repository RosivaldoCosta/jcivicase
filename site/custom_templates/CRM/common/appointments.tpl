<div id="appointment_show" class="section-hidden section-hidden-border">
  <a href="#" onclick="hide('appointment_show'); show('appointment'); filterAppointmentGrid(); return false;"><img src="{$config->resourceBase}/TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}Appointments{/ts}</label><br />
</div>

<div id="appointment" class="section-shown">
 <fieldset>
  <legend><a href="#" onclick="hide('appointment'); show('appointment_show'); return false;"><img src="{$config->resourceBase}/TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}Appointments{/ts}</legend>
<div>
  <table id="appointment-selector"  class="nestedActivitySelector" style="display:none"></table>
</div>
</div>

{include file="CRM/common/appointmentflexigrid.tpl"}

{literal}               
<script type="text/javascript">
cj(document).ready(function(){

        resizeAppointmentGrid();

});

show('appointment_show');       
hide('appointment');    
</script>       
{/literal}


