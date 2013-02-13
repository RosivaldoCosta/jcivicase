		{foreach from=$grTree item=block}
				

			<div class='cdu_print_title4'>{$block.title}</div>
			<div class='cdu_print_innerTable'>	
        {if $block.name == 'Group1_head'}
                <div class='group1'>
                    <span>{$block.fields.task_form_type.label}:</span> {$block.fields.task_form_type.element_value} <br />
                    <span>{$block.fields.task_details.label}:</span>{$block.fields.task_details.element_value}
                </div>
                <div class='group2'>
                    <span>{$block.fields.source_contact_id.label}:</span>{$block.fields.source_contact_id.element_value}
                </div>
                <div class='group3'>
                    <span>{$block.fields.activity_date_time.label}:</span>{$block.fields.activity_date_time.element_value} <br />
                    <span>{$block.fields.time.label}:</span>{$block.fields.time.element_value}
                </div>
        {else}
			
			{if $block.help_pre}
				<div class='test11'  style='display:block; padding-bottom:0px;'>{$block.help_pre}</div>	<div class='clr'></div>
			{/if}

			<div class='test11'  style='display:block;'>
			
            {foreach from=$block.fields item=field key=key}
               {if isset($pagebreakArr) && is_array($pagebreakArr) && in_array($key,  $pagebreakArr )}
                    <div class="page_break">
                {else}
                    <div>
                {/if}

				{if $field.html_type EQ 'Radio' or $field.html_type EQ 'CheckBox'}{*$key*}
                        {if isset($supercolumnArr) && is_array($supercolumnArr) && in_array($key,  $supercolumnArr )}
							<div class='left' style="width:97px;"><span>{$field.label}:{*$field.overlenght_len*}</span></div>
                        {else}
                            <div class='left'><span>{$field.label}:{*$field.overlenght_len*}</span></div>
                        {/if}
                            {if !$field.overlenght || $field.countrow == 0}
                    <div class='right'>
                    {foreach from=$field.element_value item=elem}
                            <img src='{$rootURL}images/{$elem.value}.png'> {$elem.text}
                    {/foreach}
                            </div>
                    {else}
                                {assign var="count" value="1"}<br/>
                                <div style="width:95%;">
                                {foreach name=outer key=key item=elem from=$field.element_value}
                                            <span  style="width:{$field.widthrow}%;vertical-align: top;display: inline-block;font-size: 8pt; line-height: 16px;font-weight: normal;"><img src='{$rootURL}images/{$elem.value}.png' style="vertical-align: text-top;"> {$elem.text}</span>
                                    {if $count == $field.countrow}
                                            </div><div  style="width:95%;">

                                            {assign var="count" value="1"}
                                    {else}
                                                {if $smarty.foreach.outer.last} {assign var="countend" value=`$field.countrow-$count`}
                                                    {section name=foo loop=$countend}
                                                    <span  style="width:{$field.widthrow}%;display: inline-block;font-size: 8pt; line-height: 16px;font-weight: normal;">&nbsp;</span>
                                                    {/section}
                                                {else}
                                            {assign var="count" value=`$count+1`}
                                                {/if}
                                    {/if}
                                {/foreach}
                                </div>
                    {/if}
		<br />
                {else}
                    {*if $field.html_type EQ 'Sign'}
                        <table class="form-layout-compressed">
                            <tr>
                                <td class="label">{$field.label}:</td>
                                <td class="view-value">
                                        {$field.element_value}
                                </td>
                            </tr>
                        </table>
                    {else*}
                    <div class='left'><span>{$field.label}:</span></div><div class='right'> {$field.element_value} </div><br />
                    {*/if*}
                {/if}
                </div>
			{/foreach}
			</div>	
     {/if}
	
			</div>
			<div class='clr'></div>
			
		{/foreach}

{*if $embedAttachmentTags}
<table class="form-layout-compressed">
    <tr>
        <td class="label">{ts}Current Signature(s){/ts}</td>
        <td class="view-value">
        {foreach from=$embedAttachmentTags item=tag}
                {$tag}
        {/foreach}
    </tr>
    </table>

{/if*}
