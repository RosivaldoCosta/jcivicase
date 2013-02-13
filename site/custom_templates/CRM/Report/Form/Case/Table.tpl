<TABLE BORDER=0> 
	<TBODY> 
		<TR> 
			<TD COLSPAN=27 WIDTH=889 HEIGHT=28 ALIGN=CENTER VALIGN=TOP><B><FONT SIZE=4 COLOR="#800000">{$title}</FONT></B></TD> 
		</TR> 
		<TR> 
			<TD ALIGN=CENTER STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" HEIGHT=28 ALIGN=LEFT VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000"></FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Jul</FONT></B></TD> 
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

	{if $view|strpos:'separator'===0}
		<tr>
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" HEIGHT=28 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000" ><strong>{$views.$view}</strong></FONT></TD> 
			<TD colspan="26" STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" HEIGHT=28 ALIGN=CENTER VALIGN=TOP>&nbsp;</TD>

		</tr>
	{else} 
		<TR> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" HEIGHT=28 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$views.$view}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Jul}{$results.$view.Jul}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Aug}{$results.$view.Aug}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Sep}{$results.$view.Sep}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Oct}{$results.$view.Oct}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Nov}{$results.$view.Nov}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Dec}{$results.$view.Dec}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Jan}{$results.$view.Jan}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Feb}{$results.$view.Feb}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="427" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{if $results.$view.Mar}{$results.$view.Mar}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="388" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{if $results.$view.Apr}{$results.$view.Apr}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="437" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{if $results.$view.May}{$results.$view.May}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Jun}{$results.$view.Jun}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="1252" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{if $results.$view.TTL}{$results.$view.TTL}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="1252" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{if $results.$view.AVG}{$results.$view.AVG}{else}0{/if}</FONT></TD> 
		</TR> 
	{/if}
	{/foreach}
</TABLE>

