{if $embedObjectData}
<table class="form-layout-compressed">
        {foreach from=$embedObjectData item=data key=k}
    <tr>
        <td class="label">
		<!--{ts}{if $data.type eq 'client'} Client{/if}
		    {if $data.type eq 'witness'}Witness{/if}
		    {if $data.type eq 'staff'} Staff {/if}
		    {if $data.type eq 'physician'} Physician{/if}
		   Signature
		 {/ts}-->
		
	</td>
        <td class="view-value">
               	<OBJECT classid=clsid:69A40DA3-4D42-11D0-86B0-0000C025864A height=50 id={$data.type}SigPlus1 name=SigPlus1 style="HEIGHT: 170px; LEFT: 0px; TOP: 0px; WIDTH: 283px" width=183 VIEWASTEXT>
        	<PARAM NAME="_Version" VALUE="131095">
        	<PARAM NAME="_ExtentX" VALUE="4842">
        	<PARAM NAME="_ExtentY" VALUE="1323">
        	<PARAM NAME="_StockProps" VALUE="0">
            	</OBJECT> 
		<br/>
    </tr>
        {/foreach}
    </table>
  <script language="vbscript">
 
	{foreach from=$embedObjectData item=data key=k}
      		document.Activity.{$data.type}SigPlus1.AutoKeyStart
      		document.Activity.{$data.type}SigPlus1.AutoKeyData="sante"
      		document.Activity.{$data.type}SigPlus1.AutoKeyFinish
      		document.Activity.{$data.type}SigPlus1.EncryptionMode=1
      		document.Activity.{$data.type}SigPlus1.SigCompressionMode=2
      		document.Activity.{$data.type}SigPlus1.JustifyMode=5 
      		document.Activity.{$data.type}SigPlus1.SigString="{$data.signature_data}"
	{/foreach}
</script>
{else}
    <OBJECT classid=clsid:69A40DA3-4D42-11D0-86B0-0000C025864A height=50
            id=SigPlus1 name=SigPlus1
            style="HEIGHT: 170px; LEFT: 0px; TOP: 0px; WIDTH: 283px" width=183
            VIEWASTEXT>
	<PARAM NAME="_Version" VALUE="131095">
	<PARAM NAME="_ExtentX" VALUE="4842">
	<PARAM NAME="_ExtentY" VALUE="1323">
	<PARAM NAME="_StockProps" VALUE="0">
            </OBJECT>
{literal}
<SCRIPT LANGUAGE=vbscript>
<!--

Sub OnClear
document.Activity.SigPlus1.ClearTablet
end Sub

Sub OnSign
document.Activity.SigPlus1.TabletState=1
end Sub

Sub OnSubm

Dim SigStr

document.Activity.SigPlus1.TabletState = 0
document.Activity.SigPlus1.SigCompressionMode = 2
document.Activity.SigPlus1.AutoKeyStart
document.Activity.SigPlus1.AutoKeyData = "sante"
document.Activity.SigPlus1.AutoKeyFinish
document.Activity.SigPlus1.EncryptionMode = 1

SigStr = document.Activity.SigPlus1.SigString
document.Activity.SigField.value = SigStr

'document.sigForm.Submit

end Sub

//-->
</SCRIPT>
{/literal}


<!--<FORM action="createimg.php" id=sigForm method=post name=sigForm>-->

<p>
<INPUT id=SignBtn name=SignBtn type=button value=Sign language ="VBScript" onclick=OnSign>&nbsp;&nbsp;&nbsp;&nbsp;

<INPUT id=button1 name=ClearBtn type=button value=Clear language ="VBScript" onclick=OnClear>&nbsp;&nbsp;&nbsp;&nbsp

<INPUT id=button2 name=DoneBtn type=button value=Done language ="VBScript" onclick=OnSubm>&nbsp;&nbsp;&nbsp;&nbsp

<INPUT type=hidden id=SigField name=SigField>

</p>

<!--</FORM>-->
{/if}
