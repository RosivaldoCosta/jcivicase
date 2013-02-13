{* include jscript to handle auto saving forms*}
{literal}
<script type="text/javascript">
var allowAutoSave = true;
cj(document).ready( function (){
    if ( cj('#crm-content form').is("form") )
    {
      //getAllInputs();
  	setTimeout ( "getAllInputs()", 20000 );
    }
});



function getAllInputs ( )
{
	if(!allowAutoSave){return false;}
	cj('#crm-content div.form-autosave-message').remove();
	cj('#crm-container div.form-autosave-message').remove();
	var astr = '';
	var dataSend = {};
	var formElemName = '';
	inputs = cj("form :input");
    var i = 0;
  cj(inputs).each(function(index) {
    if ((   cj(this).attr('type') == 'text'       || 
        cj(this).attr('type') == 'radio'      || 
        cj(this).attr('type') == 'checkbox'      || 
        cj(this).attr('type') == 'hidden'      || 
        cj(this).attr('type') == 'select-one' || 
        cj(this).attr('type') == 'textarea')  && 
        cj(this).val() != '')
    {
	var n = cj(this).attr('name');
	var t = cj(this).attr('type');
	var e = cj(this);
	var v = cj(this).val();
	var ch = cj(this).is(':checked');

	if(t == 'checkbox') // || t == 'radio')
	{
		if(cj(this).is(':checked'))
		{
      			dataSend[cj(this).attr('name')] = '1';//cj(this).is(':checked');
      			astr = astr + '&' + cj(this).attr('name') + '=1';
			formElemName = formElemName + cj(this).attr('name') + ',';
  			dataSend['form_emel_name'] = formElemName;
		}
	}
	else if(t == 'radio')
	{

		if(cj(this).is(':checked'))
		{
      			dataSend[cj(this).attr('name')] = cj(this).attr('value');//cj(this).is(':checked');
      			astr = astr + '&' + cj(this).attr('name') + '=1';
			formElemName = formElemName + cj(this).attr('name') + ',';
  			dataSend['form_emel_name'] = formElemName;
		}
	}
	else 
	{
      		dataSend[cj(this).attr('name')] = cj(this).val();
      		astr = astr + '&' + cj(this).attr('name') + '=' + cj(this).val();
		formElemName = formElemName + cj(this).attr('name') + ',';
  		dataSend['form_emel_name'] = formElemName;
	}

      i++;
    }
  });
  dataSend['form_url'] = document.location.href;
  cj.ajax({
      url: 'index2.php?option=com_civicrm&task=civicrm/ajax/formAutosave&autosave=1',
      type: 'POST',
      data: dataSend,
      success: function( content ) {
			var id = cj('input[name="id"]').attr('value');
			if(id != null)
			{
				cj('input[name="id"]').remove();
				cj('input[name="action"]').remove();
				//add new values
			} 
				cj(content).insertAfter('#source_contact_id');
			
          }
      });
  
  setTimeout ( "getAllInputs()", 20000 );
	
}
</script>
{/literal}
