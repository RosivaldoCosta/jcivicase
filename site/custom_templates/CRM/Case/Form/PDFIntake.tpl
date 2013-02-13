


		<div class='cdu_print_title4'>{$details.label}:</div>	
		<div class='cdu_print_innerTable'>
			<div class='test11'>
				{$details.html}
			</div>			
		</div>
		<div class='clr'></div>

		{foreach from=$grTree item=block}
		
			{if $block.name EQ 'Referral_Source'}
			<div class='cdu_print_title4'>How did you hear about us?</div>
			{else}
			<div class='cdu_print_title4'>{$block.title}</div>
			{/if}
			<div class='cdu_print_innerTable'>		
				
			{if $block.name == 'Caller_Information'}

					<div class='test21'>
						<div  class='left3'><span>{$block.fields.51.label}:</span></div><div  class='right3'> {$block.fields.51.element_value} <br /></div>
						<div  class='left3'><span>{$block.fields.52.label}:</span></div><div  class='right3'> {$block.fields.52.element_value} <br /></div>
						<div  class='left3'><span>{$block.fields.53.label}:</span></div><div  class='right3'> {$block.fields.53.element_value} <br /></div>
						<div  class='left3'><span>{$block.fields.54.label}:</span></div><div  class='right3'> {$block.fields.54.element_value} <br /></div>
					</div>	
					<div class='test22'>
						<div  class='left3'><span>{$block.fields.55.label}:</span></div><div  class='right3'> {$block.fields.55.element_value} <br /></div>
						<div  class='left3'><span>{$block.fields.56.label}:</span></div><div  class='right3'> {$block.fields.56.element_value} <br /></div>
						<div  class='left3'><span>{$block.fields.1268.label}:</span></div><div  class='right3'> {$block.fields.1268.element_value} <br /></div>
						<div  class='left3'><span>{$block.fields.57.label}:</span></div><div  class='right3'> {$block.fields.57.element_value} <br /></div>
					</div>	
					<div class='clr'></div>
					<div class='test11_2'>
						<span>{$block.fields.58.label}:</span> 
							{foreach from=$block.fields.58.element_value item=elem}
								 <img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
							{/foreach} 
 						<br />
						<span>{$block.fields.1266.label}:</span>  
							{foreach from=$block.fields.1266.element_value item=elem}
								{if $elem.value}
									<img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
								{else}
									<img src='{$rootURL}images/cbem.png'> {$elem.text}
								{/if}
							{/foreach} 
						<br />
						<span>{$block.fields.1267.label}:</span> {$block.fields.1257.element_value} <br />
					</div>
			
			{elseif $block.name == 'Referral_Source'}
			
				<div class='test41'><div>
				{section name=num loop=$block.fields.122.element_value start=0 max=3}
				    <img src='{$rootURL}images/{$block.fields.122.element_value[num].value}.png'> {$block.fields.122.element_value[num].text}<br />
				{/section}
				</div></div>			
				<div class='test42'><div>
				{section name=num loop=$block.fields.122.element_value start=3 max=3}
				    <img src='{$rootURL}images/{$block.fields.122.element_value[num].value}.png'> {$block.fields.122.element_value[num].text}<br />
				{/section}
				</div></div>	
				<div class='test43'><div>
				{section name=num loop=$block.fields.122.element_value start=6 max=3}
				    <img src='{$rootURL}images/{$block.fields.122.element_value[num].value}.png'> {$block.fields.122.element_value[num].text}<br />
				{/section}
				</div></div>
				<div class='test44'><div>
				{section name=num loop=$block.fields.122.element_value start=9 max=4}
				    <img src='{$rootURL}images/{$block.fields.122.element_value[num].value}.png'> {$block.fields.122.element_value[num].text}<br />
				{/section}
				</div></div>
				<div class='clr'></div>
				<div class='test11_2' style='width:100%'>
					<span>{$block.fields.125.label}:</span> <br />
					{if $block.fields.125.element_value}
					<div class='blockBorder'><div>{$block.fields.125.element_value}</div></div>
					{/if}
				</div>
			{elseif $block.name == 'Insurance'}
				<div class='insurance_block'>
					<div class='test41'>
					{section name=num loop=$block.fields.121.element_value start=0 max=2}
					    <img src='{$rootURL}images/{$block.fields.121.element_value[num].value}.png'> {$block.fields.121.element_value[num].text}<br />
					{/section}
					</div>			
					<div class='test42'>
					{section name=num loop=$block.fields.121.element_value start=2 max=2}
					    <img src='{$rootURL}images/{$block.fields.121.element_value[num].value}.png'> {$block.fields.121.element_value[num].text}<br />
					{/section}
					</div>			
					<div class='test43'>
					{section name=num loop=$block.fields.121.element_value start=4 max=2}
					    <img src='{$rootURL}images/{$block.fields.121.element_value[num].value}.png'> {$block.fields.121.element_value[num].text}<br />
					{/section}
					</div>
					<div class='test44'>
					{section name=num loop=$block.fields.121.element_value start=6 max=3}
					    <img src='{$rootURL}images/{$block.fields.121.element_value[num].value}.png'> {$block.fields.121.element_value[num].text}<br />
					{/section}
					</div>
				</div>
				<div class='test11' style='display:block;'>
					<div class='test31'>
						<span>{$block.fields.117.label}:</span> {$block.fields.117.element_value} <br />
					</div>	
					<div class='test32'>
						<span>{$block.fields.119.label}:</span> {$block.fields.119.element_value} <br />
					</div>
					<div class='test33'>
						<span>{$block.fields.120.label}:</span> {$block.fields.120.element_value} <br />
					</div>
				</div>

			{elseif $block.name == 'Case_Events'}
				<div class='test21'>
					<div  class='left2'  style='width:200px;'><span>{$block.fields.24.label}:</span></div><div  class='right2'> 
						 {foreach from=$block.fields.24.element_value item=elem}
							<img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
						{/foreach} 
						<br /></div>
					<div  class='left2'  style='width:200px;'><span>{$block.fields.25.label}:</span></div><div  class='right2'> 
						 {foreach from=$block.fields.25.element_value item=elem}
							<img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
						{/foreach} 
						<br /></div>
				</div>	
				<div class='test22'>
					<div  class='left2' style='width:200px;'><span>{$block.fields.26.label}:</span></div><div  class='right2'> 
						 {foreach from=$block.fields.26.element_value item=elem}
							<img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
						{/foreach} 
						<br /></div>
					<div  class='left2'  style='width:200px;'><span>{$block.fields.27.label}:</span></div><div  class='right2'> 
						 {foreach from=$block.fields.27.element_value item=elem}
							<img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
						{/foreach} 
						<br /></div>
				</div>
			{elseif $block.name == 'CRS_Action'}
				<div class='test21'>
					<div  class='left2'  style='width:200px;'><span>{$block.fields.16.label}:</span></div><div  class='right2'> 
						 {foreach from=$block.fields.16.element_value item=elem}
							<img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
						{/foreach} 
						<br /></div>
					<div  class='left2'  style='width:200px;'><span>{$block.fields.17.label}:</span></div><div  class='right2'> 
						 {foreach from=$block.fields.17.element_value item=elem}
							<img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
						{/foreach} 
						<br /></div>
					<div  class='left2'  style='width:200px;'><span>{$block.fields.18.label}:</span></div><div  class='right2'> 
						 {foreach from=$block.fields.18.element_value item=elem}
							<img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
						{/foreach} 
						<br /></div>
					<div  class='left2'  style='width:200px;'><span>{$block.fields.20.label}:</span></div><div  class='right2'> 
						 {foreach from=$block.fields.20.element_value item=elem}
							<img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
						{/foreach} 
						<br /></div>
					<div  class='left2'  style='width:200px;'><span>{$block.fields.21.label}:</span></div><div  class='right2'> 
						 {foreach from=$block.fields.21.element_value item=elem}
							<img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
						{/foreach} 
						<br /></div>
				</div>	
				<div class='test22'>
					<div  class='left2'  style='width:200px;'><span>{$block.fields.22.label}:</span></div><div  class='right2'> 
						 {foreach from=$block.fields.22.element_value item=elem}
							<img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
						{/foreach} 
						<br /></div>
					<div  class='left2'  style='width:200px;'><span>{$block.fields.23.label}:</span></div><div  class='right2'> 
						 {foreach from=$block.fields.23.element_value item=elem}
							<img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
						{/foreach} 
						<br /></div>
					<div  class='left2'  style='width:200px;'><span>{$block.fields.19.label}:</span></div><div  class='right2'> 
						 {foreach from=$block.fields.19.element_value item=elem}
							<img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
						{/foreach} 
						<br /></div>
					<div  class='left2'  style='width:200px;'><span>{$block.fields.753.label}:</span></div><div  class='right2'> 
						{$block.fields.753.element_value}
						<br /></div>
					<div  class='left2'  style='width:200px;'><span>{$block.fields.48.label}:</span></div><div  class='right2'> 
						{$block.fields.753.element_value}
						<br /></div>
				</div>
				
			{elseif $block.name == 'Additional_Info'}
				<div class='test11'>
				{foreach from=$block.fields item=field}
					<div>
						 {$field.element_value}
					</div>
				{/foreach}
				</div>	
			{elseif $block.name == 'Provider_PCP'}
				<div class='test11'>
					<div class='test31'>
						<div  class='left3'><span>{$block.fields.260.label}:</span></div><div  class='right3'> {$block.fields.260.element_value} <br /></div>
						<div  class='left3'><span>{$block.fields.258.label}:</span></div><div  class='right3'> {$block.fields.258.element_value} <br /></div>
						<div  class='left3'><span>{$block.fields.128.label}:</span></div><div  class='right3'> {$block.fields.128.element_value} <br /></div>
					</div>	
					<div class='test32'>
						<div  class='left3'><span>{$block.fields.284.label}:</span></div><div  class='right3'> {$block.fields.284.element_value} <br /></div>
						<div  class='left3'><span>{$block.fields.285.label}:</span></div><div  class='right3'> {$block.fields.285.element_value} <br /></div>
						<div  class='left3'><span>{$block.fields.286.label}:</span></div><div  class='right3'> {$block.fields.286.element_value} <br /></div>
					</div>
					<div class='test33'>
						<div  class='left3'><span>{$block.fields.1258.label}:</span></div><div  class='right3'> {$block.fields.1258.element_value} <br /></div>
						<div  class='left3'><span>{$block.fields.1259.label}:</span></div><div  class='right3'> {$block.fields.1259.element_value} <br /></div>
						<div  class='left3'><span>{$block.fields.1260.label}:</span></div><div  class='right3'> {$block.fields.1260.element_value} <br /></div>
					</div>
				</div>	
			{elseif $block.name == 'Linkage_and_Referrals'}
					<div class='test11'>
					{foreach from=$block.fields item=field}
						<div>
							<span>{$field.label}:</span> {$field.element_value} <br />
						</div>
					{/foreach}
					</div>	
			{elseif $block.name == 'Financial'}
						<div class='test21'>
						{if $block.fields.59.html_type EQ 'Radio' or $block.fields.59.html_type EQ 'CheckBox'}
							<span>{$block.fields.59.label}:</span>
							{foreach from=$block.fields.59.element_value item=elem}
								 <img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
							{/foreach} 
						{else}
							<div class='left'><span>{$block.fields.59.label}:</span></div><div class='right'> {$block.fields.59.element_value} </div><br />
						{/if}
						</div>
						<div class='test22'>
						{if $block.fields.202.html_type EQ 'Radio' or $block.fields.202.html_type EQ 'CheckBox'}
							<span>{$block.fields.202.label}:</span>
							{foreach from=$block.fields.202.element_value item=elem}
								 <img src='{$rootURL}images/{$elem.value}.png'> {$elem.text} 
							{/foreach} 
							
						{else}
							<div class='left'><span>{$block.fields.202.label}:</span></div><div class='right'> {$block.fields.202.element_value} </div><br />
						{/if}
						</div>
			{elseif $block.name == 'Emergency_Contact_If_Client_Is_Under_18'}
				<div class='test11'>
					<div class='test31' style='display:block;'>
						<span>{$block.fields.38.label}:</span> {$block.fields.38.element_value} <br />
					</div>	
					<div class='test32'>
						<span>{$block.fields.40.label}:</span> {$block.fields.40.element_value} <br />
					</div>
					<div class='test33'>
						<span>{$block.fields.39.label}:</span> {$block.fields.39.element_value} <br />
					</div>
				</div>
				{if $block.fields.1770.label}
				<div class='test11' style='display:block;'>
					<div class='test31'>
						<span>{$block.fields.1770.label}:</span> {$block.fields.1770.element_value} <br />
					</div>	
					<div class='test32'>
						<span>{$block.fields.1771.label}:</span> {$block.fields.1771.element_value} <br />
					</div>
					<div class='test33'>
						<span>{$block.fields.1772.label}:</span> {$block.fields.1772.element_value} <br />
					</div>
				</div>
				{/if}
			{elseif $block.name eq 'Client_Information_Field' OR $block.name eq 'Client_Information'}

					{foreach from=$block.collumns item=ColKey key=keyN}
						<div class='test2{$keyN}'><div>
						{foreach from=$ColKey item=fieldKey}
							
							{if $block.fields.$fieldKey.html_type EQ 'Radio' or $block.fields.$fieldKey.html_type EQ 'CheckBox'}
								<div class='left2'><span>{$block.fields.$fieldKey.label}:</span></div>
								<div class='right2'>
								{foreach from=$block.fields.$fieldKey.element_value item=elem}
									 <img src='{$rootURL}/images/{$elem.value}.png'> {$elem.text} 
								{/foreach} 
								</div><br />
							{else}
								<div class='left2'><span>{$block.fields.$fieldKey.label}:</span></div><div class='right2'> {$block.fields.$fieldKey.element_value} </div><br />
							{/if}
							
						{/foreach}
						</div></div>
					{/foreach}
				
			{else}
					{if $block.name == 'Release_of_Information'}
					<div class='test11'  style='display:block; padding-bottom:0px;'>{$block.help_pre}</div>	<div class='clr'></div>
					{/if}
					<div class='test11'  style='display:block;'>
					{foreach from=$block.fields item=field}
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

<!-- <div class='page_break'></div>	 -->
