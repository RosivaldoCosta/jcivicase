{include file="CRM/Report/Form/Case/Table.tpl" title="Calls YTD Report" results=$results}

<!--
{if $results}
<TABLE FRAME=VOID CELLSPACING=0 COLS=27 RULES=NONE BORDER=0> 
	<COLGROUP><COL WIDTH=237><COL WIDTH=44><COL WIDTH=44><COL WIDTH=1><COL WIDTH=44><COL WIDTH=1><COL WIDTH=44><COL WIDTH=1><COL WIDTH=59><COL WIDTH=1><COL WIDTH=44><COL WIDTH=1><COL WIDTH=44><COL WIDTH=1><COL WIDTH=59><COL WIDTH=1><COL WIDTH=59><COL WIDTH=1><COL WIDTH=44><COL WIDTH=1><COL WIDTH=44><COL WIDTH=1><COL WIDTH=1><COL WIDTH=44><COL WIDTH=1><COL WIDTH=59><COL WIDTH=1></COLGROUP> 
	<TBODY> 
		<TR> 
			<TD COLSPAN=27 WIDTH=889 HEIGHT=28 ALIGN=CENTER VALIGN=TOP><B><FONT SIZE=4 COLOR="#800000">Calls Report</FONT></B></TD> 
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
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Avg</FONT></B></TD> 
			</TR> 
	{foreach from=$results key=view item=ds}
		<TR> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" HEIGHT=28 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$views.$view}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$results.$view.Jul}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$results.$view.Aug}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$results.$view.Sep}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$results.$view.Oct}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$results.$view.Nov}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$results.$view.Dec}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$results.$view.Jan}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$results.$view.Feb}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="427" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{$results.$view.Mar}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="388" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{$results.$view.Apr}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="437" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{$results.$view.May}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$results.$view.Jun}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="1252" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{$results.$view.TTL}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="1252" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{$results.$view.AVG}</FONT></TD> 
		</TR> 
	{/foreach}
</TABLE>

{/if}
-->
