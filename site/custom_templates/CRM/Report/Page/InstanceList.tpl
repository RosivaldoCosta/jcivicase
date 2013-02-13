{strip}
{if $list}
<div class="accordion ui-accordion ui-widget ui-helper-reset" style="width:99%">    
	{foreach from=$list item=rows key=report}		
	  <h3 class="head"><span class="ui-icon ui-icon-triangle-1-e"></span>
		<a href="#">{if $title}{$title}{elseif $report EQ 'Contribute'}{ts}Contribution Reports{/ts}{else}{$report} {ts}Reports{/ts}{/if}</a>
	  </h3>
	<div id="{$report}" class="ui-accordion-content boxBlock ui-corner-bottom">
	    <table class="report-layout">
		{foreach from=$rows item=row}
		    <tr>
			<td style="width:35%"><a href="{$row.url}" title="{ts}Run this report{/ts}">&raquo; <strong>{$row.title}</strong></a></td>
			<td>{$row.description}</td>
			{if $row.deleteUrl}
			    <td style = "width:5%"><a href="{$row.deleteUrl}" onclick="return window.confirm('{ts}Are you sure you want delete this report? This action can not be undone.{/ts}');">{ts}Delete{/ts}</a></td>
			{/if}
		    </tr>
		{/foreach}
	    </table>
	</div>
	<div class="spacer"> </div>
    {/foreach}
</div>
    {if $reportUrl}
	<a href="{$reportUrl}" class="button"><span>&raquo; {ts}View All Reports{/ts}</span></a>
    {/if}
{else}
    <div class="messages status">
        <dl>
            <dt>
                <img src="{$config->resourceBase}Inform.gif" alt="{ts}status{/ts}"/>
            </dt>
            <dd>
                {ts}No report instances have been created for your site.{/ts} &nbsp;
                {if $templateUrl}
                    {ts 1=$templateUrl}You can create reports by selecting from the <a href="%1">list of report templates here.</a>{/ts}
                {else}
                    {ts}Contact your site administrator for help creating reports.{/ts}
                {/if}
            </dd>
        </dl>
    </div>
{/if}
{/strip}
{literal}
<script type="text/javascript">
cj(function() {
	cj('.accordion .head').addClass( "ui-accordion-header ui-helper-reset ui-state-default ui-corner-all ");
	cj('.accordion .head').hover( function() { cj(this).addClass( "ui-state-hover");
	}, function() { cj(this).removeClass( "ui-state-hover");
}).bind('click', function() { 
	var checkClass = cj(this).find('span').attr( 'class' );
	var len        = checkClass.length;
	if ( checkClass.substring( len - 1, len ) == 's' ) {
		cj(this).find('span').removeClass().addClass('ui-icon ui-icon-triangle-1-e');
		cj("span#help"+cj(this).find('span').attr('id')).hide();
	} else {
		cj(this).find('span').removeClass().addClass('ui-icon ui-icon-triangle-1-s');
		cj("span#help"+cj(this).find('span').attr('id')).show();
	}
	cj(this).next().toggle(); return false; }).next().hide();

	cj('div.accordion div.ui-accordion-content').each(function() {
		cj(this).parent().find('h3 span').removeClass( ).addClass('ui-icon ui-icon-triangle-1-s');
		cj(this).show();
	});
});
</script>
{/literal}
