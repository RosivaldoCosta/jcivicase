{literal}
<script type="text/javascript">

function buildCustomData( type, subType, subName, cgCount, groupID, isMultiple, depart )
{
{/literal}{if $isPrint!=1}
	var dataUrl = "{crmURL p=$urlPath h=0 q='snippet=4&type='}" + type;
{else}
	var dataUrl = "{crmURL p=$urlPath h=0 q='snippet=2&type='}" + type;
{/if}{literal}
	if ( subType ) {
		dataUrl = dataUrl + '&subType=' + subType;
	}

	if ( depart ) {
		dataUrl = dataUrl + '&depart=' + depart;
	}

	if ( subName ) {
		dataUrl = dataUrl + '&subName=' + subName;
		cj('#customData' + subName ).show();
	} else {
		cj('#customData').show();
	}

	{/literal}
		{if $urlPathVar}
			dataUrl = dataUrl + '&' + '{$urlPathVar}'
		{/if}
		{if $groupID}
			dataUrl = dataUrl + '&groupID=' + '{$groupID}'
		{/if}
		{if $qfKey}
			dataUrl = dataUrl + '&qfKey=' + '{$qfKey}'
		{/if}
		{if $entityID}
			dataUrl = dataUrl + '&entityID=' + '{$entityID}'
		{/if}
	{literal}

	if ( !cgCount ) {
		cgCount = 1;
		var prevCount = 1;
	} else if ( cgCount >= 1 ) {
		var prevCount = cgCount;
		cgCount++;
	}

	dataUrl = dataUrl + '&cgcount=' + cgCount;

//	Today = new Date();
//	var time = Today.getTime();
//	dataUrl = dataUrl + '&tt=' + time;

	{/literal}{if $isPrint}dataUrl = dataUrl + '&print=1'{/if}{literal}

	if ( isMultiple ) {
		var fname = '#custom_group_' + groupID + '_' + prevCount;
		cj("#add-more-link-"+prevCount).hide();
	} else {
		if ( subName && subName != 'null' ) {
			var fname = '#customData' + subName ;
		} else {
			var fname = '#customData';
		}
	}

	var response = cj.ajax({
						url: dataUrl,
						async: false
					}).responseText;

	cj( fname ).html( response );

	if (typeof(depart) != 'undefined')
	{
		cj('input[value='+depart+'][name=custom_594_-1]' ).attr('checked','checked');
	}
	cj('.form-item label').attr('style', 'font-weight: bold !important');
}

//cj('#customData div table label').css('font-weight', 'bold');
</script>
{/literal}
