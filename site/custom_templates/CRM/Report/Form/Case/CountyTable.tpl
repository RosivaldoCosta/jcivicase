<TABLE BORDER=0> 
	<TBODY> 
		<TR> 
			<TD COLSPAN=27 WIDTH=889 HEIGHT=28 ALIGN=CENTER VALIGN=TOP><B><FONT SIZE=4 COLOR="#800000">{$title}</FONT></B></TD> 
		</TR> 
		<TR> 
			<TD ALIGN=CENTER STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" HEIGHT=28 ALIGN=LEFT VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">County of Residence</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Caroline</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT SIZE=1 COLOR="#000000">Cecil</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Dorchester</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Kent</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">QA</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Somerset</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Talbot</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Wicomico</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Total</FONT></B></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR="#FFFFCC"><B><FONT COLOR="#000000">Avg</FONT></B></TD> 
			</TR> 
	{foreach from=$results key=view item=ds}

	{if $view|strpos:'separator'===0}
		<tr>
			<TD BGCOLOR="#FFFFCC" STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" HEIGHT=28 ALIGN=LEFT VALIGN=TOP><FONT COLOR="#000000" ><strong>{$views.$view}</strong></FONT></TD> 
			<TD BGCOLOR="#FFFFCC" colspan="26" STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" HEIGHT=28 ALIGN=CENTER VALIGN=TOP>&nbsp;</TD>

		</tr>
	{else} 
		<TR> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" HEIGHT=28 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{$views.$view}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Caroline}{$results.$view.Caroline}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Cecil}{$results.$view.Cecil}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Dorchester}{$results.$view.Dorchester}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Kent}{$results.$view.Kent}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.QA}{$results.$view.QA}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Somerset}{$results.$view.Somerset}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Talbot}{$results.$view.Talbot}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP><FONT COLOR="#000000">{if $results.$view.Wicomico}{$results.$view.Wicomico}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="1252" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{if $results.$view.TTL}{$results.$view.TTL}{else}0{/if}</FONT></TD> 
			<TD STYLE="border-top: 3px solid #000000; border-bottom: 3px solid #000000; border-left: 3px solid #000000; border-right: 3px solid #000000" COLSPAN=2 ALIGN=CENTER VALIGN=TOP SDVAL="1252" SDNUM="1033;0;[$-409]General"><FONT COLOR="#000000">{if $results.$view.AVG}{$results.$view.AVG}{else}0{/if}</FONT></TD> 
		</TR> 
	{/if}
	{/foreach}
</TABLE>

