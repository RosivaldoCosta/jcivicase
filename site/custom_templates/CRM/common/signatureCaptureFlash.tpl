{if $embedAttachmentTags}
<table class="form-layout-compressed">
    <tr>
        <td class="label">{ts}Current Signature(s){/ts}</td>       
        <td class="view-value">
	{foreach from=$embedAttachmentTags item=tag}
		{$tag}		
	{/foreach}
    </tr>
    </table>

{else}

<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="450" height="300" id="linedrawer"
 align="middle">
        <param name="allowScriptAccess" value="sameDomain" />
        <param name="allowFullScreen" value="false" />        
        <param name="movie" value="../flash/exported_linedrawer.swf?caseid={$caseId}&atype={$activityTypeName|escape:'url'}&cid={$contactId}&depart={$depart}&id={$activityId}&hostUrl=ehr.santegroup.org/{$sitename}/custom_php" />
        <!--<param name="movie" value="../flash/exported_linedrawer.swf" />-->
        <param name="quality" value="high" />
	<param name="bgcolor" value="#ffffff" />
        <embed src="../flash/exported_linedrawer.swf?caseid={$caseId}&atype={$activityTypeName|escape:'url'}&cid={$contactId}&depart={$depart}&id={$activityId}&hostUrl=ehr.santegroup.org/{$sitename}/custom_php" 
		quality="high" 
		bgcolor="#ffffff" 
		width="450" 
		height="300" 
		name="linedrawer" 
		align="middle" 
		allowScriptAccess="sameDomain" 
		allowFullScreen="false" 
		type="application/x-shockwave-flash" 
		pluginspage="http://www.adobe.com/go/getflashplayer" />
</object>
{/if}
<input name="targetId" type="hidden" id="targetId" value="{$activityId}" />