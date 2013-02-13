		{foreach from=$grTree item=block}
				

			<div class='cdu_print_title4'>{$block.title}</div>
			<div class='cdu_print_innerTable'>	
			
			{if $block.help_pre}
				<div class='test11'  style='display:block; padding-bottom:0px;'>{$block.help_pre}</div>	<div class='clr'></div>
			{/if}
			

			<div class='test11'  style='display:block;'>
			
			{foreach from=$block.fields item=field}
				
				{if $field.html_type EQ 'Radio' or $field.html_type EQ 'CheckBox'}
				
					{if $field.overlenght}
						{assign var='label' value=$field.label}
						{assign var='sign' value=':'}
						{foreach from=$field.element_value item=elem}
						<div>
							<div class='left'><span>{$label}{$sign}</span></div>
							<div class='right'><img src='{$rootURL}images/{$elem.value}.png'> {$elem.text}</div>
						</div>
							{assign var="label" value=" "}
							{assign var='sign' value=''}
						{/foreach} 
					{else}
						<div>
							<div class='left'><span>{$field.label}</span></div>
							<div class='right'><div>{foreach from=$field.element_value item=elem}<img src='{$rootURL}images/{$elem.value}.png'> {$elem.text}&nbsp;{/foreach}</div></div>
						</div>
					{/if}

				{else}
					<div><div class='left'><span>{$field.label}:</span></div><div class='right'> {$field.element_value} </div><br /></div>
				{/if}
			
			{/foreach}
			</div>	

	
			</div>
			<div class='clr'></div>
			
		{/foreach}