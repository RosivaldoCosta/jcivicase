{if $embedObjectData}
<table class="form-layout-compressed">
        {foreach from=$embedObjectData item=data key=k}
    <tr>
        <td class="label">
		
	</td>
        <td class="view-value">
		<object id="pluginobj_{$data.type}" type="application/x-vnd-aplix-foo">Plugin FAILED to load</object>
		<br/>
		<input type="button" onclick='document.getElementById("pluginobj_{$data.type}").SetSig("{$data.signature_data}");' value="Set Sign" />
	</td>
    </tr>
        {/foreach}
    </table>
{literal}
  <script type="text/javascript">
	window.onload = setsigs();

	function setsigs() {

{/literal}
		{foreach from=$embedObjectData item=data key=k}
			document.getElementById("pluginobj_{$data.type}").SetSig("{$data.signature_data}");
		{/foreach}
{literal}
	}

 
</script>
{/literal}
{else}
		<object id="pluginobj" type="application/x-vnd-aplix-foo">Plugin FAILED to load</object>
{literal}
<script type="text/javascript">
		function Sign() {
			try {
				document.getElementById("pluginobj").Sign();
			}
			catch(e) { alert(e); }
		}

		function Clear() {
			try {
				alert(document.getElementById("pluginobj").Clear());
			}
			catch(e) { alert(e); }
		}

		function Done() {
			try {
				alert(document.getElementById("pluginobj").Done());
			}
			catch(e) { alert(e); }
		}

		function GetSig() {
			try {
				//alert(document.getElementById("pluginobj").GetSig());
				document.getElementById("result").value = document.getElementById("pluginobj").GetSig();
			}
			catch(e) { alert(e); }
		}

		function SetSig(str) {
			try {
				alert(document.getElementById("pluginobj").SetSig(str));
			}
			catch(e) { alert(e); }
		}
</script>
{/literal}


<!--<FORM action="createimg.php" id=sigForm method=post name=sigForm>-->

<p>
<input type="button" onclick='document.getElementById("pluginobj").Sign();' value="Sign" />
<input type="button" onclick='document.getElementById("pluginobj").Clear();' value="Clear" />
<input type="button" onclick='document.getElementById("pluginobj").Done();document.getElementById("SigField").value=document.getElementById("pluginobj").GetSig();' value="Done" />
<INPUT type=hidden id=SigField name=SigField>

</p>

<!--</FORM>-->
{/if}
