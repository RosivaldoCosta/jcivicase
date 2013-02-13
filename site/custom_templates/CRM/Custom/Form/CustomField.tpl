{assign var="element_name" value=$element.element_name}
{if $element.is_view eq 0}{* fix for CRM-3510 *}
    {if $element.html_type eq 'Phone'}
        {include file="CRM/common/autoFormatPhone.tpl"}
    {/if}
    {if $element.help_pre}
        <tr>
            <td>&nbsp;</td>
            <td class="html-adjust description">{$element.help_pre}</td>
        </tr>
    {/if}
     {if $element.options_per_line != 0 }
        <tr>
            <td class="label">{$form.$element_name.label}</td>
            <td class="html-adjust">
                {assign var="count" value="1"}
                <table class="form-layout-compressed" style="margin-top: -0.5em;">
                    <tr>
                        {* sort by fails for option per line. Added a variable to iterate through the element array*}
                        {assign var="index" value="1"}
                        {foreach name=outer key=key item=item from=$form.$element_name}
                            {if $index < 10}
                                {assign var="index" value=`$index+1`}
                            {else}
                                	<td class="labels font-light">{$form.$element_name.$key.html}</td>
                                	{if $count == $element.options_per_line}
                                    		</tr>
                                    		<tr>
                                    		{assign var="count" value="1"}
                                	{else}
                                    		{assign var="count" value=`$count+1`}
                                	{/if}
                            {/if}
                        {/foreach}
                        {if $element.html_type eq 'Radio'}
                            {if !isset($isPrint) || !$isPrint}
                                <td>&nbsp;&nbsp;(&nbsp;<a href="#" title="unselect" onclick="unselectRadio('{$element_name}', '{$form.formName}'); return false;" >{ts}unselect{/ts}</a>&nbsp;)</td>
                            {/if}
                        {/if}
                    </tr>
                </table>
            </td>
        </tr>

        {if $element.help_post}
            <tr>
                <td>&nbsp;</td>
                <td class="description">{$element.help_post}<br />&nbsp;</td>
            </tr>
             {/if}
    {else}
        <tr>
            <td class="label">{$form.$element_name.label}</td>
            <td class="html-adjust">
                {if $element.data_type neq 'Date'}
                    {$form.$element_name.html}&nbsp;
                {elseif $element.skip_calendar NEQ true }
                    {include file="CRM/common/jcalendar.tpl" elementName=$element_name}
                {/if}

                {if $element.html_type eq 'Radio'}
                    {if !isset($isPrint) || !$isPrint}
                        &nbsp;&nbsp;(&nbsp;<a href="#" title="unselect" onclick="unselectRadio('{$element_name}', '{$form.formName}'); return false;" >{ts}unselect{/ts}</a>&nbsp;)
                    {/if}
                {elseif $element.data_type eq 'File'}
                    {if $element.element_value.data}
                        <span class="html-adjust"><br />
                            &nbsp;{ts}Attached File{/ts}: &nbsp;
                            {if $element.element_value.displayURL }
                                <a href="javascript:popUp('{$element.element_value.displayURL}')" ><img border="0" src="{$element.element_value.displayURL}" height = "100" width="100"></a>
                            {else}
                                <a href="{$element.element_value.fileURL}">{$element.element_value.fileName}</a>
                            {/if}
                            {if $element.element_value.deleteURL }
                                <br />
                            {$element.element_value.deleteURL}
                            {/if}
                        </span>
                    {/if}
                {elseif $element.html_type eq 'Multi-Select Case'}
                    {include file="CRM/Custom/Form/CaseReference.tpl"}
                {elseif $element.html_type eq 'Autocomplete-Select'}
                    {include file="CRM/Custom/Form/AutoComplete.tpl"}
                {/if}
            </td>
        </tr>

        {if $element.help_post}

<td>&nbsp;</td>
<td class="description">{$element.help_post}<br />&nbsp;</td>
</tr>
        {/if}
    {/if}
{/if}
