<!--
{include file="CRM/Report/Form/Case/Table.tpl" title="Out Referrals YTD Report"}
-->

{if $results}
<TABLE FRAME=VOID CELLSPACING=0 COLS=27 RULES=NONE BORDER=0> 
	<COLGROUP><COL WIDTH=237><COL WIDTH=44><COL WIDTH=44><COL WIDTH=1><COL WIDTH=44><COL WIDTH=1><COL WIDTH=44><COL WIDTH=1><COL WIDTH=59><COL WIDTH=1><COL WIDTH=44><COL WIDTH=1><COL WIDTH=44><COL WIDTH=1><COL WIDTH=59><COL WIDTH=1><COL WIDTH=59><COL WIDTH=1><COL WIDTH=44><COL WIDTH=1><COL WIDTH=44><COL WIDTH=1><COL WIDTH=1><COL WIDTH=44><COL WIDTH=1><COL WIDTH=59><COL WIDTH=1></COLGROUP> 
	<TBODY> 
		<TR> 
			<TD COLSPAN=27 WIDTH=889 HEIGHT=28 ALIGN=CENTER VALIGN=TOP><B><FONT SIZE=4 COLOR="#800000">Out Referrals Report</FONT></B></TD> 
		</TR> 
		<TR> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" HEIGHT=28 ALIGN=LEFT VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000"></FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=LEFT VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Jul</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT SIZE=1 COLOR="#000000">Aug</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Sep</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Oct</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Nov</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Dec</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Jan</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Feb</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Mar</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Apr</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT SIZE=1 COLOR="#000000">May</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Jun</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Total</FONT></B></TD> 
			</TR> 
	{foreach from=$results key=view item=ds}
		{foreach from=$ds key=referral item=i}
		<TR> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" HEIGHT=28 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$referral}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$i.Jul}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$i.Aug}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$i.Sep}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$i.Oct}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$i.Nov}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$i.Dec}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$i.Jan}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$i.Feb}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="427" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{$i.Mar}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="388" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{$i.Apr}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="437" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{$i.May}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$i.Jun}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="1252" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{$i.TTL}</FONT></TD> 
		</TR> 
	{/foreach}
	{/foreach}
</TABLE>

{/if}
