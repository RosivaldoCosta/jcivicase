<HTML>
<HEAD>
<TITLE>SigPlus Example</TITLE>



</HEAD>

<BODY>
<table border=1 cellpadding="0" height="150" width="306">
  <tr>
    <td height="1" width="368"> <OBJECT classid=clsid:69A40DA3-4D42-11D0-86B0-0000C025864A height=50
            id=SigPlus1 name=SigPlus1
            style="HEIGHT: 170px; LEFT: 0px; TOP: 0px; WIDTH: 283px" width=183
            VIEWASTEXT>
	<PARAM NAME="_Version" VALUE="131095">
	<PARAM NAME="_ExtentX" VALUE="4842">
	<PARAM NAME="_ExtentY" VALUE="1323">
	<PARAM NAME="_StockProps" VALUE="0">
            </OBJECT>
      </td>
  </tr></table>
<SCRIPT LANGUAGE=vbscript>
<!--

Sub OnClear
SigPlus1.ClearTablet
end Sub

Sub OnSign
document.Activity.SigPlus1.TabletState=1
end Sub

Sub OnSubm

Dim SigStr

SigPlus1.TabletState = 0
SigPlus1.SigCompressionMode = 1

SigStr = SigPlus1.SigString
document.sigForm.SigField.value = SigStr

document.sigForm.Submit

end Sub

//-->
</SCRIPT>



<FORM action="createimg.php" id=sigForm method=post name=sigForm>

<p>
<INPUT id=SignBtn name=SignBtn type=button value=Sign language ="VBScript" onclick=OnSign>&nbsp;&nbsp;&nbsp;&nbsp;

<INPUT id=button1 name=ClearBtn type=button value=Clear language ="VBScript" onclick=OnClear>&nbsp;&nbsp;&nbsp;&nbsp

<INPUT id=button2 name=DoneBtn type=button value=Done language ="VBScript" onclick=OnSubm>&nbsp;&nbsp;&nbsp;&nbsp

<INPUT type=hidden id=SigField name=SigField>

</p>

</FORM>

</BODY>

</HTML>
