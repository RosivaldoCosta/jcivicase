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

			{foreach from=$block.fields item=field key=key}{*$key*}
						<div>
						{if $field.html_type EQ 'Radio' or $field.html_type EQ 'CheckBox'}
							<div class='left'><span>{$field.label}:</span></div>
							<div class='right'>
							{foreach from=$field.element_value item=elem}
								 <img src='{$rootURL}images/{$elem.value}.png'> {$elem.text}
							{/foreach}
							</div><br />
						{else}
							<div class='left'><span>{$field.label}:</span></div><div class='right'> {$field.element_value} </div><br />
						{/if}
						</div>
			{/foreach}
			</div>
     {/if}

			</div>
			<div class='clr'></div>

		{/foreach}

