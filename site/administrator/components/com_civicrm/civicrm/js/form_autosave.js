cj(document).ready(function (){
		if ( cj('#crm-content form').is("form") )
		{
			getAllInputs();
		}
});

function getAllInputs ( )
{
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
				cj(this).attr('type') == 'select-one' || 
				cj(this).attr('type') == 'textarea')  && 
				cj(this).val() != '')
		{
			dataSend[cj(this).attr('name')] = cj(this).val();
			astr = astr + '&' + cj(this).attr('name') + '=' + cj(this).val();
			formElemName = formElemName + cj(this).attr('name') + ',';
			i++;
		}
	});
	dataSend['form_emel_name'] = formElemName;
	dataSend['form_url'] = document.location.href;
	cj.ajax({
	    url: '/easternshore/administrator/index2.php?option=com_civicrm&task=civicrm/ajax/formAutosave',
	    type: 'POST',
	    data: dataSend,
	    success: function( content ) {
			cj(content).insertBefore('form');
	       	}
	    });
	setTimeout ( "getAllInputs()", 20000 );
}
