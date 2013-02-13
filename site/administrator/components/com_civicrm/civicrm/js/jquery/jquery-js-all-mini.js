(function($){$.addFlex=function(t,p)
{if(t.grid)return false;p=$.extend({height:200,width:'auto',striped:true,novstripe:false,minwidth:30,minheight:80,resizable:true,url:false,method:'POST',dataType:'xml',errormsg:'Connection Error',usepager:false,nowrap:true,page:1,total:1,useRp:true,rp:15,rpOptions:[10,15,20,25,40],title:false,pagestat:'Displaying {from} to {to} of {total} items',procmsg:'Processing, please wait ...',query:'',qtype:'',nomsg:'No items',minColToggle:1,showToggleBtn:true,hideOnSubmit:true,autoload:true,blockOpacity:0.5,onToggleCol:false,onChangeSort:false,onSuccess:false,onSubmit:false},p);$(t).show().attr({cellPadding:0,cellSpacing:0,border:0}).removeAttr('width');var g={hset:{},rePosDrag:function(){var cdleft=0-this.hDiv.scrollLeft;if(this.hDiv.scrollLeft>0)cdleft-=Math.floor(p.cgwidth/2);$(g.cDrag).css({top:g.hDiv.offsetTop+1});var cdpad=this.cdpad;$('div',g.cDrag).hide();$('thead tr:first th:visible',this.hDiv).each
(function()
{var n=$('thead tr:first th:visible',g.hDiv).index(this);var cdpos=parseInt($('div',this).width());var ppos=cdpos;if(cdleft==0)
cdleft-=Math.floor(p.cgwidth/2);cdpos=cdpos+cdleft+cdpad;$('div:eq('+n+')',g.cDrag).css({'left':cdpos+'px'}).show();cdleft=cdpos;});},fixHeight:function(newH){newH=false;if(!newH)newH=$(g.bDiv).height();var hdHeight=$(this.hDiv).height();$('div',this.cDrag).each(function()
{$(this).height(newH+hdHeight);});var nd=parseInt($(g.nDiv).height());if(nd>newH)
$(g.nDiv).height(newH).width(200);else
$(g.nDiv).height('auto').width('auto');$(g.block).css({height:newH,marginBottom:(newH*-1)});var hrH=g.bDiv.offsetTop+newH;if(p.height!='auto'&&p.resizable)hrH=g.vDiv.offsetTop;$(g.rDiv).css({height:hrH});},dragStart:function(dragtype,e,obj){if(dragtype=='colresize')
{$(g.nDiv).hide();$(g.nBtn).hide();var n=$('div',this.cDrag).index(obj);var ow=$('th:visible div:eq('+n+')',this.hDiv).width();$(obj).addClass('dragging').siblings().hide();$(obj).prev().addClass('dragging').show();this.colresize={startX:e.pageX,ol:parseInt(obj.style.left),ow:ow,n:n};$('body').css('cursor','col-resize');}
else if(dragtype=='vresize')
{var hgo=false;$('body').css('cursor','row-resize');if(obj)
{hgo=true;$('body').css('cursor','col-resize');}
this.vresize={h:p.height,sy:e.pageY,w:p.width,sx:e.pageX,hgo:hgo};}
else if(dragtype=='colMove')
{$(g.nDiv).hide();$(g.nBtn).hide();this.hset=$(this.hDiv).offset();this.hset.right=this.hset.left+$('table',this.hDiv).width();this.hset.bottom=this.hset.top+$('table',this.hDiv).height();this.dcol=obj;this.dcoln=$('th',this.hDiv).index(obj);this.colCopy=document.createElement("div");this.colCopy.className="colCopy";this.colCopy.innerHTML=obj.innerHTML;if($.browser.msie)
{this.colCopy.className="colCopy ie";}
$(this.colCopy).css({position:'absolute',float:'left',display:'none',textAlign:obj.align});$('body').append(this.colCopy);$(this.cDrag).hide();}
$('body').noSelect();},dragMove:function(e){if(this.colresize)
{var n=this.colresize.n;var diff=e.pageX-this.colresize.startX;var nleft=this.colresize.ol+diff;var nw=this.colresize.ow+diff;if(nw>p.minwidth)
{$('div:eq('+n+')',this.cDrag).css('left',nleft);this.colresize.nw=nw;}}
else if(this.vresize)
{var v=this.vresize;var y=e.pageY;var diff=y-v.sy;if(!p.defwidth)p.defwidth=p.width;if(p.width!='auto'&&!p.nohresize&&v.hgo)
{var x=e.pageX;var xdiff=x-v.sx;var newW=v.w+xdiff;if(newW>p.defwidth)
{this.gDiv.style.width=newW+'px';p.width=newW;}}
var newH=v.h+diff;if((newH>p.minheight||p.height<p.minheight)&&!v.hgo)
{this.bDiv.style.height=newH+'px';p.height=newH;this.fixHeight(newH);}
v=null;}
else if(this.colCopy){$(this.dcol).addClass('thMove').removeClass('thOver');if(e.pageX>this.hset.right||e.pageX<this.hset.left||e.pageY>this.hset.bottom||e.pageY<this.hset.top)
{$('body').css('cursor','move');}
else
$('body').css('cursor','pointer');$(this.colCopy).css({top:e.pageY+10,left:e.pageX+20,display:'block'});}},dragEnd:function(){if(this.colresize)
{var n=this.colresize.n;var nw=this.colresize.nw;$('th:visible div:eq('+n+')',this.hDiv).css('width',nw);$('tr',this.bDiv).each(function()
{$('td:visible div:eq('+n+')',this).css('width',nw);});this.hDiv.scrollLeft=this.bDiv.scrollLeft;$('div:eq('+n+')',this.cDrag).siblings().show();$('.dragging',this.cDrag).removeClass('dragging');this.rePosDrag();this.fixHeight();this.colresize=false;}
else if(this.vresize)
{this.vresize=false;}
else if(this.colCopy)
{$(this.colCopy).remove();if(this.dcolt!=null)
{if(this.dcoln>this.dcolt)
$('th:eq('+this.dcolt+')',this.hDiv).before(this.dcol);else
$('th:eq('+this.dcolt+')',this.hDiv).after(this.dcol);this.switchCol(this.dcoln,this.dcolt);$(this.cdropleft).remove();$(this.cdropright).remove();this.rePosDrag();}
this.dcol=null;this.hset=null;this.dcoln=null;this.dcolt=null;this.colCopy=null;$('.thMove',this.hDiv).removeClass('thMove');$(this.cDrag).show();}
$('body').css('cursor','default');$('body').noSelect(false);},toggleCol:function(cid,visible){var ncol=$("th[axis='col"+cid+"']",this.hDiv)[0];var n=$('thead th',g.hDiv).index(ncol);var cb=$('input[value='+cid+']',g.nDiv)[0];if(visible==null)
{visible=ncol.hide;}
if($('input:checked',g.nDiv).length<p.minColToggle&&!visible)return false;if(visible)
{ncol.hide=false;$(ncol).show();cb.checked=true;}
else
{ncol.hide=true;$(ncol).hide();cb.checked=false;}
$('tbody tr',t).each
(function()
{if(visible)
$('td:eq('+n+')',this).show();else
$('td:eq('+n+')',this).hide();});this.rePosDrag();if(p.onToggleCol)p.onToggleCol(cid,visible);return visible;},switchCol:function(cdrag,cdrop){$('tbody tr',t).each
(function()
{if(cdrag>cdrop)
$('td:eq('+cdrop+')',this).before($('td:eq('+cdrag+')',this));else
$('td:eq('+cdrop+')',this).after($('td:eq('+cdrag+')',this));});if(cdrag>cdrop)
$('tr:eq('+cdrop+')',this.nDiv).before($('tr:eq('+cdrag+')',this.nDiv));else
$('tr:eq('+cdrop+')',this.nDiv).after($('tr:eq('+cdrag+')',this.nDiv));if($.browser.msie&&$.browser.version<7.0)$('tr:eq('+cdrop+') input',this.nDiv)[0].checked=true;this.hDiv.scrollLeft=this.bDiv.scrollLeft;},scroll:function(){this.hDiv.scrollLeft=this.bDiv.scrollLeft;this.rePosDrag();},addData:function(data){if(p.preProcess)
data=p.preProcess(data);$('.pReload',this.pDiv).removeClass('loading');this.loading=false;if(!data)
{$('.pPageStat',this.pDiv).html(p.errormsg);return false;}
if(p.dataType=='xml')
p.total=+$('rows total',data).text();else
p.total=data.total;if(p.total==0)
{$('tr, a, td, div',t).unbind();$(t).empty();p.pages=1;p.page=1;this.buildpager();$('.pPageStat',this.pDiv).html(p.nomsg);return false;}
p.pages=Math.ceil(p.total/p.rp);if(p.dataType=='xml')
p.page=+$('rows page',data).text();else
p.page=data.page;this.buildpager();var tbody=document.createElement('tbody');if(p.dataType=='json')
{$.each
(data.rows,function(i,row)
{var tr=document.createElement('tr');if(i%2&&p.striped)tr.className='erow';if(row.id)tr.id='row'+row.id;$('thead tr:first th',g.hDiv).each
(function()
{var td=document.createElement('td');var idx=$(this).attr('axis').substr(3);td.align=this.align;td.innerHTML=row.cell[idx];$(tr).append(td);td=null;});if($('thead',this.gDiv).length<1)
{for(idx=0;idx<cell.length;idx++)
{var td=document.createElement('td');td.innerHTML=row.cell[idx];$(tr).append(td);td=null;}}
$(tbody).append(tr);tr=null;});}else if(p.dataType=='xml'){i=1;$("rows row",data).each
(function()
{i++;var tr=document.createElement('tr');if(i%2&&p.striped)tr.className='erow';var nid=$(this).attr('id');if(nid)tr.id='row'+nid;nid=null;var robj=this;$('thead tr:first th',g.hDiv).each
(function()
{var td=document.createElement('td');var idx=$(this).attr('axis').substr(3);td.align=this.align;td.innerHTML=$("cell:eq("+idx+")",robj).text();$(tr).append(td);td=null;});if($('thead',this.gDiv).length<1)
{$('cell',this).each
(function()
{var td=document.createElement('td');td.innerHTML=$(this).text();$(tr).append(td);td=null;});}
$(tbody).append(tr);tr=null;robj=null;});}
$('tr',t).unbind();$(t).empty();$(t).append(tbody);this.addCellProp();this.addRowProp();this.rePosDrag();tbody=null;data=null;i=null;if(p.onSuccess)p.onSuccess();if(p.hideOnSubmit)$(g.block).remove();this.hDiv.scrollLeft=this.bDiv.scrollLeft;if($.browser.opera)$(t).css('visibility','visible');},changeSort:function(th){if(this.loading)return true;$(g.nDiv).hide();$(g.nBtn).hide();if(p.sortname==$(th).attr('abbr'))
{if(p.sortorder=='asc')p.sortorder='desc';else p.sortorder='asc';}
$(th).addClass('sorted').siblings().removeClass('sorted');$('.sdesc',this.hDiv).removeClass('sdesc');$('.sasc',this.hDiv).removeClass('sasc');$('div',th).addClass('s'+p.sortorder);p.sortname=$(th).attr('abbr');if(p.onChangeSort)
p.onChangeSort(p.sortname,p.sortorder);else
this.populate();},buildpager:function(){$('.pcontrol input',this.pDiv).val(p.page);$('.pcontrol span',this.pDiv).html(p.pages);var r1=(p.page-1)*p.rp+1;var r2=r1+p.rp-1;if(p.total<r2)r2=p.total;var stat=p.pagestat;stat=stat.replace(/{from}/,r1);stat=stat.replace(/{to}/,r2);stat=stat.replace(/{total}/,p.total);$('.pPageStat',this.pDiv).html(stat);},populate:function(){if(this.loading)return true;if(p.onSubmit)
{var gh=p.onSubmit();if(!gh)return false;}
this.loading=true;if(!p.url)return false;$('.pPageStat',this.pDiv).html(p.procmsg);$('.pReload',this.pDiv).addClass('loading');$(g.block).css({top:g.bDiv.offsetTop});if(p.hideOnSubmit)$(this.gDiv).prepend(g.block);if($.browser.opera)$(t).css('visibility','hidden');if(!p.newp)p.newp=1;if(p.page>p.pages)p.page=p.pages;var param=[{name:'page',value:p.newp},{name:'rp',value:p.rp},{name:'sortname',value:p.sortname},{name:'sortorder',value:p.sortorder},{name:'query',value:p.query},{name:'qtype',value:p.qtype}];if(p.params)
{for(var pi=0;pi<p.params.length;pi++)param[param.length]=p.params[pi];}
$.ajax({type:p.method,url:p.url,data:param,dataType:p.dataType,success:function(data){g.addData(data);},error:function(data){try{if(p.onError)p.onError(data);}catch(e){}}});},doSearch:function(){p.query=$('input[name=q]',g.sDiv).val();p.qtype=$('select[name=qtype]',g.sDiv).val();p.newp=1;this.populate();},changePage:function(ctype){if(this.loading)return true;switch(ctype)
{case'first':p.newp=1;break;case'prev':if(p.page>1)p.newp=parseInt(p.page)-1;break;case'next':if(p.page<p.pages)p.newp=parseInt(p.page)+1;break;case'last':p.newp=p.pages;break;case'input':var nv=parseInt($('.pcontrol input',this.pDiv).val());if(isNaN(nv))nv=1;if(nv<1)nv=1;else if(nv>p.pages)nv=p.pages;$('.pcontrol input',this.pDiv).val(nv);p.newp=nv;break;}
if(p.newp==p.page)return false;if(p.onChangePage)
p.onChangePage(p.newp);else
this.populate();},addCellProp:function()
{$('tbody tr td',g.bDiv).each
(function()
{var tdDiv=document.createElement('div');var n=$('td',$(this).parent()).index(this);var pth=$('th:eq('+n+')',g.hDiv).get(0);if(pth!=null)
{if(p.sortname==$(pth).attr('abbr')&&p.sortname)
{this.className='sorted';}
$(tdDiv).css({textAlign:pth.align,width:$('div:first',pth)[0].style.width});if(pth.hide)$(this).css('display','none');}
if(p.nowrap==false)$(tdDiv).css('white-space','normal');if(this.innerHTML=='')this.innerHTML=' ';tdDiv.innerHTML=this.innerHTML;var prnt=$(this).parent()[0];var pid=false;if(prnt.id)pid=prnt.id.substr(3);if(pth!=null)
{if(pth.process)pth.process(tdDiv,pid);}
$(this).empty().append(tdDiv).removeAttr('width');});},getCellDim:function(obj)
{var ht=parseInt($(obj).height());var pht=parseInt($(obj).parent().height());var wt=parseInt(obj.style.width);var pwt=parseInt($(obj).parent().width());var top=obj.offsetParent.offsetTop;var left=obj.offsetParent.offsetLeft;var pdl=parseInt($(obj).css('paddingLeft'));var pdt=parseInt($(obj).css('paddingTop'));return{ht:ht,wt:wt,top:top,left:left,pdl:pdl,pdt:pdt,pht:pht,pwt:pwt};},addRowProp:function()
{$('tbody tr',g.bDiv).each
(function()
{$(this).click(function(e)
{var obj=(e.target||e.srcElement);if(obj.href||obj.type)return true;if(p.singleSelect)$(this).siblings().removeClass('trSelected');}).mousedown(function(e)
{if(e.shiftKey)
{$(this).toggleClass('trSelected');g.multisel=true;this.focus();$(g.gDiv).noSelect();}}).mouseup(function()
{if(g.multisel)
{g.multisel=false;$(g.gDiv).noSelect(false);}}).hover(function(e)
{if(g.multisel)
{$(this).toggleClass('trSelected');}},function(){});if($.browser.msie&&$.browser.version<7.0)
{$(this).hover(function(){$(this).addClass('trOver');},function(){$(this).removeClass('trOver');});}});},pager:0};if(p.colModel)
{thead=document.createElement('thead');tr=document.createElement('tr');for(i=0;i<p.colModel.length;i++)
{var cm=p.colModel[i];var th=document.createElement('th');th.innerHTML=cm.display;if(cm.name&&cm.sortable)
$(th).attr('abbr',cm.name);$(th).attr('axis','col'+i);if(cm.align)
th.align=cm.align;if(cm.width)
$(th).attr('width',cm.width);if(cm.hide)
{th.hide=true;}
if(cm.process)
{th.process=cm.process;}
$(tr).append(th);}
$(thead).append(tr);$(t).prepend(thead);}
g.gDiv=document.createElement('div');g.mDiv=document.createElement('div');g.hDiv=document.createElement('div');g.bDiv=document.createElement('div');g.vDiv=document.createElement('div');g.rDiv=document.createElement('div');g.cDrag=document.createElement('div');g.block=document.createElement('div');g.nDiv=document.createElement('div');g.nBtn=document.createElement('div');g.iDiv=document.createElement('div');g.tDiv=document.createElement('div');g.sDiv=document.createElement('div');if(p.usepager)g.pDiv=document.createElement('div');g.hTable=document.createElement('table');g.gDiv.className='flexigrid';if(p.width!='auto')g.gDiv.style.width=p.width+'px';if($.browser.msie)
$(g.gDiv).addClass('ie');if(p.novstripe)
$(g.gDiv).addClass('novstripe');$(t).before(g.gDiv);$(g.gDiv).append(t);if(p.buttons)
{g.tDiv.className='tDiv';var tDiv2=document.createElement('div');tDiv2.className='tDiv2';for(i=0;i<p.buttons.length;i++)
{var btn=p.buttons[i];if(!btn.separator)
{var btnDiv=document.createElement('div');btnDiv.className='fbutton';btnDiv.innerHTML="<div><span>"+btn.name+"</span></div>";if(btn.bclass)
$('span',btnDiv).addClass(btn.bclass).css({paddingLeft:20});btnDiv.onpress=btn.onpress;btnDiv.name=btn.name;if(btn.onpress)
{$(btnDiv).click
(function()
{this.onpress(this.name,g.gDiv);});}
$(tDiv2).append(btnDiv);if($.browser.msie&&$.browser.version<7.0)
{$(btnDiv).hover(function(){$(this).addClass('fbOver');},function(){$(this).removeClass('fbOver');});}}else{$(tDiv2).append("<div class='btnseparator'></div>");}}
$(g.tDiv).append(tDiv2);$(g.tDiv).append("<div style='clear:both'></div>");$(g.gDiv).prepend(g.tDiv);}
g.hDiv.className='hDiv';$(t).before(g.hDiv);g.hTable.cellPadding=0;g.hTable.cellSpacing=0;$(g.hDiv).append('<div class="hDivBox"></div>');$('div',g.hDiv).append(g.hTable);var thead=$("thead:first",t).get(0);if(thead)$(g.hTable).append(thead);thead=null;if(!p.colmodel)var ci=0;$('thead tr:first th',g.hDiv).each
(function()
{var thdiv=document.createElement('div');if($(this).attr('abbr'))
{$(this).click(function(e)
{if(!$(this).hasClass('thOver'))return false;var obj=(e.target||e.srcElement);if(obj.href||obj.type)return true;g.changeSort(this);});if($(this).attr('abbr')==p.sortname)
{this.className='sorted';thdiv.className='s'+p.sortorder;}}
if(this.hide)$(this).hide();if(!p.colmodel)
{$(this).attr('axis','col'+ci++);}
$(thdiv).css({textAlign:this.align,width:this.width+'px'});thdiv.innerHTML=this.innerHTML;$(this).empty().append(thdiv).removeAttr('width').mousedown(function(e)
{g.dragStart('colMove',e,this);}).hover(function(){if(!g.colresize&&!$(this).hasClass('thMove')&&!g.colCopy)$(this).addClass('thOver');if($(this).attr('abbr')!=p.sortname&&!g.colCopy&&!g.colresize&&$(this).attr('abbr'))$('div',this).addClass('s'+p.sortorder);else if($(this).attr('abbr')==p.sortname&&!g.colCopy&&!g.colresize&&$(this).attr('abbr'))
{var no='';if(p.sortorder=='asc')no='desc';else no='asc';$('div',this).removeClass('s'+p.sortorder).addClass('s'+no);}
if(g.colCopy)
{var n=$('th',g.hDiv).index(this);if(n==g.dcoln)return false;if(n<g.dcoln)$(this).append(g.cdropleft);else $(this).append(g.cdropright);g.dcolt=n;}else if(!g.colresize){var nv=$('th:visible',g.hDiv).index(this);var onl=parseInt($('div:eq('+nv+')',g.cDrag).css('left'));var nw=parseInt($(g.nBtn).width());var nw9=parseInt($(g.nBtn).css('borderLeftWidth'));nw+=isNaN(nw9)?0:nw9;nl=onl-nw+Math.floor(p.cgwidth/2);$(g.nDiv).hide();$(g.nBtn).hide();$(g.nBtn).css({'left':nl,top:g.hDiv.offsetTop}).show();var ndw=parseInt($(g.nDiv).width());$(g.nDiv).css({top:g.bDiv.offsetTop});if((nl+ndw)>$(g.gDiv).width())
$(g.nDiv).css('left',onl-ndw+1);else
$(g.nDiv).css('left',nl);if($(this).hasClass('sorted'))
$(g.nBtn).addClass('srtd');else
$(g.nBtn).removeClass('srtd');}},function(){$(this).removeClass('thOver');if($(this).attr('abbr')!=p.sortname)$('div',this).removeClass('s'+p.sortorder);else if($(this).attr('abbr')==p.sortname)
{var no='';if(p.sortorder=='asc')no='desc';else no='asc';$('div',this).addClass('s'+p.sortorder).removeClass('s'+no);}
if(g.colCopy)
{$(g.cdropleft).remove();$(g.cdropright).remove();g.dcolt=null;}});});g.bDiv.className='bDiv';$(t).before(g.bDiv);$(g.bDiv).css({height:(p.height=='auto')?'auto':p.height+"px"}).scroll(function(e){g.scroll()}).append(t);if(p.height=='auto')
{$('table',g.bDiv).addClass('autoht');}
g.addCellProp();g.addRowProp();var cdcol=$('thead tr:first th:first',g.hDiv).get(0);if(cdcol!=null)
{g.cDrag.className='cDrag';g.cdpad=0;g.cdpad+=(isNaN(parseInt($('div',cdcol).css('borderLeftWidth')))?0:parseInt($('div',cdcol).css('borderLeftWidth')));g.cdpad+=(isNaN(parseInt($('div',cdcol).css('borderRightWidth')))?0:parseInt($('div',cdcol).css('borderRightWidth')));g.cdpad+=(isNaN(parseInt($('div',cdcol).css('paddingLeft')))?0:parseInt($('div',cdcol).css('paddingLeft')));g.cdpad+=(isNaN(parseInt($('div',cdcol).css('paddingRight')))?0:parseInt($('div',cdcol).css('paddingRight')));g.cdpad+=(isNaN(parseInt($(cdcol).css('borderLeftWidth')))?0:parseInt($(cdcol).css('borderLeftWidth')));g.cdpad+=(isNaN(parseInt($(cdcol).css('borderRightWidth')))?0:parseInt($(cdcol).css('borderRightWidth')));g.cdpad+=(isNaN(parseInt($(cdcol).css('paddingLeft')))?0:parseInt($(cdcol).css('paddingLeft')));g.cdpad+=(isNaN(parseInt($(cdcol).css('paddingRight')))?0:parseInt($(cdcol).css('paddingRight')));$(g.bDiv).before(g.cDrag);var cdheight=$(g.bDiv).height();var hdheight=$(g.hDiv).height();$(g.cDrag).css({top:-hdheight+'px'});$('thead tr:first th',g.hDiv).each
(function()
{var cgDiv=document.createElement('div');$(g.cDrag).append(cgDiv);if(!p.cgwidth)p.cgwidth=$(cgDiv).width();$(cgDiv).css({height:cdheight+hdheight}).mousedown(function(e){g.dragStart('colresize',e,this);});if($.browser.msie&&$.browser.version<7.0)
{g.fixHeight($(g.gDiv).height());$(cgDiv).hover(function()
{g.fixHeight();$(this).addClass('dragging')},function(){if(!g.colresize)$(this).removeClass('dragging')});}});}
if(p.striped)
$('tbody tr:odd',g.bDiv).addClass('erow');if(p.resizable&&p.height!='auto')
{g.vDiv.className='vGrip';$(g.vDiv).mousedown(function(e){g.dragStart('vresize',e)}).html('<span></span>');$(g.bDiv).after(g.vDiv);}
if(p.resizable&&p.width!='auto'&&!p.nohresize)
{g.rDiv.className='hGrip';$(g.rDiv).mousedown(function(e){g.dragStart('vresize',e,true);}).html('<span></span>').css('height',$(g.gDiv).height());if($.browser.msie&&$.browser.version<7.0)
{$(g.rDiv).hover(function(){$(this).addClass('hgOver');},function(){$(this).removeClass('hgOver');});}
$(g.gDiv).append(g.rDiv);}
if(p.usepager)
{g.pDiv.className='pDiv';g.pDiv.innerHTML='<div class="pDiv2"></div>';$(g.bDiv).after(g.pDiv);var html=' <div class="pGroup"> <div class="pFirst pButton"><span></span></div><div class="pPrev pButton"><span></span></div> </div> <div class="btnseparator"></div> <div class="pGroup"><span class="pcontrol">Page <input type="text" size="4" value="1" /> of <span> 1 </span></span></div> <div class="btnseparator"></div> <div class="pGroup"> <div class="pNext pButton"><span></span></div><div class="pLast pButton"><span></span></div> </div> <div class="btnseparator"></div> <div class="pGroup"> <div class="pReload pButton"><span></span></div> </div> <div class="btnseparator"></div> <div class="pGroup"><span class="pPageStat"></span></div>';$('div',g.pDiv).html(html);$('.pReload',g.pDiv).click(function(){g.populate()});$('.pFirst',g.pDiv).click(function(){g.changePage('first')});$('.pPrev',g.pDiv).click(function(){g.changePage('prev')});$('.pNext',g.pDiv).click(function(){g.changePage('next')});$('.pLast',g.pDiv).click(function(){g.changePage('last')});$('.pcontrol input',g.pDiv).keydown(function(e){if(e.keyCode==13)g.changePage('input')});if($.browser.msie&&$.browser.version<7)$('.pButton',g.pDiv).hover(function(){$(this).addClass('pBtnOver');},function(){$(this).removeClass('pBtnOver');});if(p.useRp)
{var opt="";for(var nx=0;nx<p.rpOptions.length;nx++)
{if(p.rp==p.rpOptions[nx])sel='selected="selected"';else sel='';opt+="<option value='"+p.rpOptions[nx]+"' "+sel+" >"+p.rpOptions[nx]+"  </option>";};$('.pDiv2',g.pDiv).prepend("<div class='pGroup'><select name='rp'>"+opt+"</select></div> <div class='btnseparator'></div>");$('select',g.pDiv).change(function()
{if(p.onRpChange)
p.onRpChange(+this.value);else
{p.newp=1;p.rp=+this.value;g.populate();}});}
if(p.searchitems)
{$('.pDiv2',g.pDiv).prepend("<div class='pGroup'> <div class='pSearch pButton'><span></span></div> </div>  <div class='btnseparator'></div>");$('.pSearch',g.pDiv).click(function(){$(g.sDiv).slideToggle('fast',function(){$('.sDiv:visible input:first',g.gDiv).trigger('focus');});});g.sDiv.className='sDiv';sitems=p.searchitems;var sopt="";for(var s=0;s<sitems.length;s++)
{if(p.qtype==''&&sitems[s].isdefault==true)
{p.qtype=sitems[s].name;sel='selected="selected"';}else sel='';sopt+="<option value='"+sitems[s].name+"' "+sel+" >"+sitems[s].display+"  </option>";}
if(p.qtype=='')p.qtype=sitems[0].name;$(g.sDiv).append("<div class='sDiv2'>Quick Search <input type='text' size='30' name='q' class='qsbox' /> <select name='qtype'>"+sopt+"</select> <input type='button' value='Clear' /></div>");$('input[name=q],select[name=qtype]',g.sDiv).keydown(function(e){if(e.keyCode==13)g.doSearch()});$('input[value=Clear]',g.sDiv).click(function(){$('input[name=q]',g.sDiv).val('');p.query='';g.doSearch();});$(g.bDiv).after(g.sDiv);}}
$(g.pDiv,g.sDiv).append("<div style='clear:both'></div>");if(p.title)
{g.mDiv.className='mDiv';g.mDiv.innerHTML='<div class="ftitle">'+p.title+'</div>';$(g.gDiv).prepend(g.mDiv);if(p.showTableToggleBtn)
{$(g.mDiv).append('<div class="ptogtitle" title="Minimize/Maximize Table"><span></span></div>');$('div.ptogtitle',g.mDiv).click
(function()
{$(g.gDiv).toggleClass('hideBody');$(this).toggleClass('vsble');});}}
g.cdropleft=document.createElement('span');g.cdropleft.className='cdropleft';g.cdropright=document.createElement('span');g.cdropright.className='cdropright';g.block.className='gBlock';var gh=$(g.bDiv).height();var gtop=g.bDiv.offsetTop;$(g.block).css({width:g.bDiv.style.width,height:gh,background:'white',position:'relative',marginBottom:(gh*-1),zIndex:1,top:gtop,left:'0px'});$(g.block).fadeTo(0,p.blockOpacity);if($('th',g.hDiv).length)
{g.nDiv.className='nDiv';g.nDiv.innerHTML="<table cellpadding='0' cellspacing='0'><tbody></tbody></table>";$(g.nDiv).css({marginBottom:(gh*-1),display:'none',top:gtop}).noSelect();var cn=0;$('th div',g.hDiv).each
(function()
{var kcol=$("th[axis='col"+cn+"']",g.hDiv)[0];var chk='checked="checked"';if(kcol.style.display=='none')chk='';$('tbody',g.nDiv).append('<tr><td class="ndcol1"><input type="checkbox" '+chk+' class="togCol" value="'+cn+'" /></td><td class="ndcol2">'+this.innerHTML+'</td></tr>');cn++;});if($.browser.msie&&$.browser.version<7.0)
$('tr',g.nDiv).hover
(function(){$(this).addClass('ndcolover');},function(){$(this).removeClass('ndcolover');});$('td.ndcol2',g.nDiv).click
(function()
{if($('input:checked',g.nDiv).length<=p.minColToggle&&$(this).prev().find('input')[0].checked)return false;return g.toggleCol($(this).prev().find('input').val());});$('input.togCol',g.nDiv).click
(function()
{if($('input:checked',g.nDiv).length<p.minColToggle&&this.checked==false)return false;$(this).parent().next().trigger('click');});$(g.gDiv).prepend(g.nDiv);$(g.nBtn).addClass('nBtn').html('<div></div>').attr('title','Hide/Show Columns').click
(function()
{$(g.nDiv).toggle();return true;});if(p.showToggleBtn)$(g.gDiv).prepend(g.nBtn);}
$(g.iDiv).addClass('iDiv').css({display:'none'});$(g.bDiv).append(g.iDiv);$(g.bDiv).hover(function(){$(g.nDiv).hide();$(g.nBtn).hide();},function(){if(g.multisel)g.multisel=false;});$(g.gDiv).hover(function(){},function(){$(g.nDiv).hide();$(g.nBtn).hide();});$(document).mousemove(function(e){g.dragMove(e)}).mouseup(function(e){g.dragEnd()}).hover(function(){},function(){g.dragEnd()});if($.browser.msie&&$.browser.version<7.0)
{$('.hDiv,.bDiv,.mDiv,.pDiv,.vGrip,.tDiv, .sDiv',g.gDiv).css({width:'100%'});$(g.gDiv).addClass('ie6');if(p.width!='auto')$(g.gDiv).addClass('ie6fullwidthbug');}
g.rePosDrag();g.fixHeight();t.p=p;t.grid=g;if(p.url&&p.autoload)
{g.populate();}
return t;};var docloaded=false;$(document).ready(function(){docloaded=true});$.fn.flexigrid=function(p){return this.each(function(){if(!docloaded)
{$(this).hide();var t=this;$(document).ready
(function()
{$.addFlex(t,p);});}else{$.addFlex(this,p);}});};$.fn.flexReload=function(p){return this.each(function(){if(this.grid&&this.p.url)this.grid.populate();});};$.fn.flexOptions=function(p){return this.each(function(){if(this.grid)$.extend(this.p,p);});};$.fn.flexToggleCol=function(cid,visible){return this.each(function(){if(this.grid)this.grid.toggleCol(cid,visible);});};$.fn.flexAddData=function(data){return this.each(function(){if(this.grid)this.grid.addData(data);});};$.fn.noSelect=function(p){if(p==null)
prevent=true;else
prevent=p;if(prevent){return this.each(function()
{if($.browser.msie||$.browser.safari)$(this).bind('selectstart',function(){return false;});else if($.browser.mozilla)
{$(this).css('MozUserSelect','none');$('body').trigger('focus');}
else if($.browser.opera)$(this).bind('mousedown',function(){return false;});else $(this).attr('unselectable','on');});}else{return this.each(function()
{if($.browser.msie||$.browser.safari)$(this).unbind('selectstart');else if($.browser.mozilla)$(this).css('MozUserSelect','inherit');else if($.browser.opera)$(this).unbind('mousedown');else $(this).removeAttr('unselectable','on');});}};})(jQuery);;(function($){$.fn.extend({autocomplete:function(urlOrData,options){var isUrl=typeof urlOrData=="string";options=$.extend({},$.Autocompleter.defaults,{url:isUrl?urlOrData:null,data:isUrl?null:urlOrData,delay:isUrl?$.Autocompleter.defaults.delay:10,max:options&&!options.scroll?10:150},options);options.highlight=options.highlight||function(value){return value;};options.formatMatch=options.formatMatch||options.formatItem;return this.each(function(){new $.Autocompleter(this,options);});},result:function(handler){return this.bind("result",handler);},search:function(handler){return this.trigger("search",[handler]);},flushCache:function(){return this.trigger("flushCache");},setOptions:function(options){return this.trigger("setOptions",[options]);},unautocomplete:function(){return this.trigger("unautocomplete");}});$.Autocompleter=function(input,options){var KEY={UP:38,DOWN:40,DEL:46,TAB:9,RETURN:13,ESC:27,COMMA:188,PAGEUP:33,PAGEDOWN:34,BACKSPACE:8};var $input=$(input).attr("autocomplete","off").addClass(options.inputClass);var timeout;var previousValue="";var cache=$.Autocompleter.Cache(options);var hasFocus=0;var lastKeyPressCode;var config={mouseDownOnSelect:false};var select=$.Autocompleter.Select(options,input,selectCurrent,config);var blockSubmit;$.browser.opera&&$(input.form).bind("submit.autocomplete",function(){if(blockSubmit){blockSubmit=false;return false;}});$input.bind(($.browser.opera?"keypress":"keydown")+".autocomplete",function(event){lastKeyPressCode=event.keyCode;switch(event.keyCode){case KEY.UP:event.preventDefault();if(select.visible()){select.prev();}else{onChange(0,true);}
break;case KEY.DOWN:event.preventDefault();if(select.visible()){select.next();}else{onChange(0,true);}
break;case KEY.PAGEUP:event.preventDefault();if(select.visible()){select.pageUp();}else{onChange(0,true);}
break;case KEY.PAGEDOWN:event.preventDefault();if(select.visible()){select.pageDown();}else{onChange(0,true);}
break;case options.multiple&&$.trim(options.multipleSeparator)==","&&KEY.COMMA:case KEY.TAB:case KEY.RETURN:if(selectCurrent()){event.preventDefault();blockSubmit=true;return false;}
break;case KEY.ESC:select.hide();break;default:clearTimeout(timeout);timeout=setTimeout(onChange,options.delay);break;}}).focus(function(){hasFocus++;}).blur(function(){hasFocus=0;if(!config.mouseDownOnSelect){hideResults();}}).click(function(){if(hasFocus++>1&&!select.visible()){onChange(0,true);}}).bind("search",function(){var fn=(arguments.length>1)?arguments[1]:null;function findValueCallback(q,data){var result;if(data&&data.length){for(var i=0;i<data.length;i++){if(data[i].result.toLowerCase()==q.toLowerCase()){result=data[i];break;}}}
if(typeof fn=="function")fn(result);else $input.trigger("result",result&&[result.data,result.value]);}
$.each(trimWords($input.val()),function(i,value){request(value,findValueCallback,findValueCallback);});}).bind("flushCache",function(){cache.flush();}).bind("setOptions",function(){$.extend(options,arguments[1]);if("data"in arguments[1])
cache.populate();}).bind("unautocomplete",function(){select.unbind();$input.unbind();$(input.form).unbind(".autocomplete");});function selectCurrent(){var selected=select.selected();if(!selected)
return false;var v=selected.result;previousValue=v;if(options.multiple){var words=trimWords($input.val());if(words.length>1){v=words.slice(0,words.length-1).join(options.multipleSeparator)+options.multipleSeparator+v;}
v+=options.multipleSeparator;}
$input.val(v);hideResultsNow();$input.trigger("result",[selected.data,selected.value]);return true;}
function onChange(crap,skipPrevCheck){if(lastKeyPressCode==KEY.DEL){select.hide();return;}
var currentValue=$input.val();if(!skipPrevCheck&&currentValue==previousValue)
return;previousValue=currentValue;currentValue=lastWord(currentValue);if(currentValue.length>=options.minChars){$input.addClass(options.loadingClass);if(!options.matchCase)
currentValue=currentValue.toLowerCase();request(currentValue,receiveData,hideResultsNow);}else{stopLoading();select.hide();}};function trimWords(value){if(!value){return[""];}
var words=value.split(options.multipleSeparator);var result=[];$.each(words,function(i,value){if($.trim(value))
result[i]=$.trim(value);});return result;}
function lastWord(value){if(!options.multiple)
return value;var words=trimWords(value);return words[words.length-1];}
function autoFill(q,sValue){if(options.autoFill&&(lastWord($input.val()).toLowerCase()==q.toLowerCase())&&lastKeyPressCode!=KEY.BACKSPACE){$input.val($input.val()+sValue.substring(lastWord(previousValue).length));$.Autocompleter.Selection(input,previousValue.length,previousValue.length+sValue.length);}};function hideResults(){clearTimeout(timeout);timeout=setTimeout(hideResultsNow,200);};function hideResultsNow(){var wasVisible=select.visible();select.hide();clearTimeout(timeout);stopLoading();if(options.mustMatch){$input.search(function(result){if(!result){if(options.multiple){var words=trimWords($input.val()).slice(0,-1);$input.val(words.join(options.multipleSeparator)+(words.length?options.multipleSeparator:""));}
else
$input.val("");}});}
if(wasVisible)
$.Autocompleter.Selection(input,input.value.length,input.value.length);};function receiveData(q,data){if(data&&data.length&&hasFocus){stopLoading();select.display(data,q);autoFill(q,data[0].value);select.show();}else{hideResultsNow();}};function request(term,success,failure){if(!options.matchCase)
term=term.toLowerCase();var data=cache.load(term);if(data&&data.length){success(term,data);}else if((typeof options.url=="string")&&(options.url.length>0)){var extraParams={timestamp:+new Date()};$.each(options.extraParams,function(key,param){extraParams[key]=typeof param=="function"?param():param;});$.ajax({mode:"abort",port:"autocomplete"+input.name,dataType:options.dataType,url:options.url,data:$.extend({s:lastWord(term),limit:options.max},extraParams),success:function(data){var parsed=options.parse&&options.parse(data)||parse(data);cache.add(term,parsed);success(term,parsed);}});}else{select.emptyList();failure(term);}};function parse(data){var parsed=[];var rows=data.split("\n");for(var i=0;i<rows.length;i++){var row=$.trim(rows[i]);if(row){row=row.split("|");parsed[parsed.length]={data:row,value:row[0],result:options.formatResult&&options.formatResult(row,row[0])||row[0]};}}
return parsed;};function stopLoading(){$input.removeClass(options.loadingClass);};};$.Autocompleter.defaults={inputClass:"ac_input",resultsClass:"ac_results",loadingClass:"ac_loading",minChars:0,delay:400,matchCase:false,matchSubset:true,matchContains:false,cacheLength:10,max:100,mustMatch:false,extraParams:{},selectFirst:true,formatItem:function(row){return row[0];},formatMatch:null,autoFill:false,width:0,multiple:false,multipleSeparator:", ",highlight:function(value,term){return value.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)("+term.replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi,"\\$1")+")(?![^<>]*>)(?![^&;]+;)","gi"),"<strong>$1</strong>");},scroll:true,scrollHeight:180};$.Autocompleter.Cache=function(options){var data={};var length=0;function matchSubset(s,sub){if(!options.matchCase)
s=s.toLowerCase();var i=s.indexOf(sub);if(i==-1)return false;return i==0||options.matchContains;};function add(q,value){if(length>options.cacheLength){flush();}
if(!data[q]){length++;}
data[q]=value;}
function populate(){if(!options.data)return false;var stMatchSets={},nullData=0;if(!options.url)options.cacheLength=1;stMatchSets[""]=[];for(var i=0,ol=options.data.length;i<ol;i++){var rawValue=options.data[i];rawValue=(typeof rawValue=="string")?[rawValue]:rawValue;var value=options.formatMatch(rawValue,i+1,options.data.length);if(value===false)
continue;var firstChar=value.charAt(0).toLowerCase();if(!stMatchSets[firstChar])
stMatchSets[firstChar]=[];var row={value:value,data:rawValue,result:options.formatResult&&options.formatResult(rawValue)||value};stMatchSets[firstChar].push(row);if(nullData++<options.max){stMatchSets[""].push(row);}};$.each(stMatchSets,function(i,value){options.cacheLength++;add(i,value);});}
setTimeout(populate,25);function flush(){data={};length=0;}
return{flush:flush,add:add,populate:populate,load:function(q){if(!options.cacheLength||!length)
return null;if(!options.url&&options.matchContains){var csub=[];for(var k in data){if(k.length>0){var c=data[k];$.each(c,function(i,x){if(matchSubset(x.value,q)){csub.push(x);}});}}
return csub;}else
if(data[q]){return data[q];}else
if(options.matchSubset){for(var i=q.length-1;i>=options.minChars;i--){var c=data[q.substr(0,i)];if(c){var csub=[];$.each(c,function(i,x){if(matchSubset(x.value,q)){csub[csub.length]=x;}});return csub;}}}
return null;}};};$.Autocompleter.Select=function(options,input,select,config){var CLASSES={ACTIVE:"ac_over"};var listItems,active=-1,data,term="",needsInit=true,element,list;function init(){if(!needsInit)
return;element=$("<div/>").hide().addClass(options.resultsClass).css("position","absolute").appendTo(document.body);list=$("<ul/>").appendTo(element).mouseover(function(event){if(target(event).nodeName&&target(event).nodeName.toUpperCase()=='LI'){active=$("li",list).removeClass(CLASSES.ACTIVE).index(target(event));$(target(event)).addClass(CLASSES.ACTIVE);}}).click(function(event){$(target(event)).addClass(CLASSES.ACTIVE);select();input.focus();return false;}).mousedown(function(){config.mouseDownOnSelect=true;}).mouseup(function(){config.mouseDownOnSelect=false;});if(options.width>0)
element.css("width",options.width);needsInit=false;}
function target(event){var element=event.target;while(element&&element.tagName!="LI")
element=element.parentNode;if(!element)
return[];return element;}
function moveSelect(step){listItems.slice(active,active+1).removeClass(CLASSES.ACTIVE);movePosition(step);var activeItem=listItems.slice(active,active+1).addClass(CLASSES.ACTIVE);if(options.scroll){var offset=0;listItems.slice(0,active).each(function(){offset+=this.offsetHeight;});if((offset+activeItem[0].offsetHeight-list.scrollTop())>list[0].clientHeight){list.scrollTop(offset+activeItem[0].offsetHeight-list.innerHeight());}else if(offset<list.scrollTop()){list.scrollTop(offset);}}};function movePosition(step){active+=step;if(active<0){active=listItems.size()-1;}else if(active>=listItems.size()){active=0;}}
function limitNumberOfItems(available){return options.max&&options.max<available?options.max:available;}
function fillList(){list.empty();var max=limitNumberOfItems(data.length);for(var i=0;i<max;i++){if(!data[i])
continue;var formatted=options.formatItem(data[i].data,i+1,max,data[i].value,term);if(formatted===false)
continue;var li=$("<li/>").html(options.highlight(formatted,term)).addClass(i%2==0?"ac_even":"ac_odd").appendTo(list)[0];$.data(li,"ac_data",data[i]);}
listItems=list.find("li");if(options.selectFirst){listItems.slice(0,1).addClass(CLASSES.ACTIVE);active=0;}
if($.fn.bgiframe)
list.bgiframe();}
return{display:function(d,q){init();data=d;term=q;fillList();},next:function(){moveSelect(1);},prev:function(){moveSelect(-1);},pageUp:function(){if(active!=0&&active-8<0){moveSelect(-active);}else{moveSelect(-8);}},pageDown:function(){if(active!=listItems.size()-1&&active+8>listItems.size()){moveSelect(listItems.size()-1-active);}else{moveSelect(8);}},hide:function(){element&&element.hide();listItems&&listItems.removeClass(CLASSES.ACTIVE);active=-1;},visible:function(){return element&&element.is(":visible");},current:function(){return this.visible()&&(listItems.filter("."+CLASSES.ACTIVE)[0]||options.selectFirst&&listItems[0]);},show:function(){var offset=$(input).offset();element.css({width:typeof options.width=="string"||options.width>0?options.width:$(input).width(),top:offset.top+input.offsetHeight,left:offset.left}).show();if(options.scroll){list.scrollTop(0);list.css({maxHeight:options.scrollHeight,overflow:'auto'});if($.browser.msie&&typeof document.body.style.maxHeight==="undefined"){var listHeight=0;listItems.each(function(){listHeight+=this.offsetHeight;});var scrollbarsVisible=listHeight>options.scrollHeight;list.css('height',scrollbarsVisible?options.scrollHeight:listHeight);if(!scrollbarsVisible){listItems.width(list.width()-parseInt(listItems.css("padding-left"))-parseInt(listItems.css("padding-right")));}}}},selected:function(){var selected=listItems&&listItems.filter("."+CLASSES.ACTIVE).removeClass(CLASSES.ACTIVE);return selected&&selected.length&&$.data(selected[0],"ac_data");},emptyList:function(){list&&list.empty();},unbind:function(){element&&element.remove();}};};$.Autocompleter.Selection=function(field,start,end){if(field.createTextRange){var selRange=field.createTextRange();selRange.collapse(true);selRange.moveStart("character",start);selRange.moveEnd("character",end);selRange.select();}else if(field.setSelectionRange){field.setSelectionRange(start,end);}else{if(field.selectionStart){field.selectionStart=start;field.selectionEnd=end;}}
field.focus();};})(jQuery);(function($){$.fn.tree=function(opts){return this.each(function(){var conf=$.extend({},opts);if(tree_component.inst&&tree_component.inst[$(this).attr('id')])tree_component.inst[$(this).attr('id')].destroy();if(conf!==false)new tree_component().init(this,conf);});};$.tree_create=function(){return new tree_component();};$.tree_focused=function(){return tree_component.inst[tree_component.focused];};$.tree_reference=function(id){return tree_component.inst[id]||null;};$.tree_rollback=function(data){for(var i in data){if(typeof data[i]=="function")continue;var tmp=tree_component.inst[i];var lock=!tmp.locked;if(lock)tmp.lock(true);if(tmp.inp)tmp.inp.val("").blur();tmp.context.append=false;tmp.container.html(data[i].html).find(".dragged").removeClass("dragged").end().find("div.context").remove();if(data[i].selected){tmp.selected=$("#"+data[i].selected);tmp.selected_arr=[];tmp.container.find("a.clicked").each(function(){tmp.selected_arr.push(tmp.get_node(this));});}
if(lock)tmp.lock(false);delete lock;delete tmp;}};function tree_component(){if(typeof tree_component.inst=="undefined"){tree_component.cntr=0;tree_component.inst={};tree_component.drag_drop={isdown:false,drag_node:false,drag_help:false,init_x:false,init_y:false,moving:false,origin_tree:false,marker:false,move_type:false,ref_node:false,appended:false,foreign:false,droppable:[],open_time:false,scroll_time:false};tree_component.mousedown=function(event){var tmp=$(event.target);if(tree_component.drag_drop.droppable.length&&tmp.is("."+tree_component.drag_drop.droppable.join(", ."))){tree_component.drag_drop.drag_help=$("<div id='jstree-dragged' class='tree tree-default'><ul><li class='last dragged foreign "+event.target.className+"'><a href='#'>"+tmp.text()+"</a></li></ul></div>");tree_component.drag_drop.drag_node=tree_component.drag_drop.drag_help.find("li:eq(0)");tree_component.drag_drop.isdown=true;tree_component.drag_drop.foreign=tmp;tmp.blur();event.preventDefault();event.stopPropagation();return false;}
event.stopPropagation();return true;};tree_component.mouseup=function(event){var tmp=tree_component.drag_drop;if(tmp.open_time)clearTimeout(tmp.open_time);if(tmp.scroll_time)clearTimeout(tmp.scroll_time);if(tmp.foreign===false&&tmp.drag_node&&tmp.drag_node.size()){tmp.drag_help.remove();if(tmp.move_type){var tree1=tree_component.inst[tmp.ref_node.parents(".tree:eq(0)").attr("id")];if(tree1)tree1.moved(tmp.origin_tree.container.find("li.dragged"),tmp.ref_node,tmp.move_type,false,(tmp.origin_tree.settings.rules.drag_copy=="on"||(tmp.origin_tree.settings.rules.drag_copy=="ctrl"&&event.ctrlKey)));}
tmp.move_type=false;tmp.ref_node=false;}
if(tmp.drag_node&&tmp.foreign!==false){tmp.drag_help.remove();if(tmp.move_type){var tree1=tree_component.inst[tmp.ref_node.parents(".tree:eq(0)").attr("id")];if(tree1)tree1.settings.callback.ondrop.call(null,tmp.foreign.get(0),tree1.get_node(tmp.ref_node).get(0),tmp.move_type,tree1);}
tmp.foreign=false;tmp.move_type=false;tmp.ref_node=false;}
tree_component.drag_drop.marker.hide();tmp.drag_help=false;tmp.drag_node=false;tmp.isdown=false;tmp.init_x=false;tmp.init_y=false;tmp.moving=false;tmp.appended=false;$("li.dragged").removeClass("dragged");tmp.origin_tree=false;event.preventDefault();event.stopPropagation();return false;};tree_component.mousemove=function(event){var tmp=tree_component.drag_drop;if(tmp.isdown){if(!tmp.moving&&Math.abs(tmp.init_x-event.pageX)<5&&Math.abs(tmp.init_y-event.pageY)<5){event.preventDefault();event.stopPropagation();return false;}
else tree_component.drag_drop.moving=true;if(tmp.open_time)clearTimeout(tmp.open_time);if(!tmp.appended){if(tmp.foreign!==false)tmp.origin_tree=$.tree_focused();$("body").append(tmp.drag_help);tmp.w=tmp.drag_help.width();tmp.appended=true;}
tmp.drag_help.css({"left":(event.pageX-(tmp.origin_tree.settings.ui.rtl?tmp.w:-5)),"top":(event.pageY+15)});if(event.target.tagName=="IMG"&&event.target.id=="marker")return false;var et=$(event.target);var cnt=et.is(".tree")?et:et.parents(".tree:eq(0)");if(cnt.size()==0||!tree_component.inst[cnt.attr("id")]){if(tmp.scroll_time)clearTimeout(tmp.scroll_time);if(tmp.drag_help.find("IMG").size()==0){tmp.drag_help.find("li:eq(0)").append("<img style='position:absolute; "+(tmp.origin_tree.settings.ui.rtl?"right":"left")+":4px; top:0px; background:white; padding:2px;' src='"+tmp.origin_tree.settings.ui.theme_path+"remove.png' />");}
tmp.move_type=false;tmp.ref_node=false;tree_component.drag_drop.marker.hide();return false;}
var tree2=tree_component.inst[cnt.attr("id")];tree2.off_height();if(tmp.foreign===false&&tmp.origin_tree.container.get(0)!=tree2.container.get(0)&&(!tmp.origin_tree.settings.rules.multitree||!tree2.settings.rules.multitree)){if(tmp.drag_help.find("IMG").size()==0){tmp.drag_help.find("li:eq(0)").append("<img style='position:absolute; "+(tmp.origin_tree.settings.ui.rtl?"right":"left")+":4px; top:0px; background:white; padding:2px;' src='"+tmp.origin_tree.settings.ui.theme_path+"remove.png' />");}
tmp.move_type=false;tmp.ref_node=false;tree_component.drag_drop.marker.hide();return false;}
if(tmp.scroll_time)clearTimeout(tmp.scroll_time);tmp.scroll_time=setTimeout(function(){tree2.scrollCheck(event.pageX,event.pageY);},50);var mov=false;var st=cnt.scrollTop();if(event.target.tagName=="A"){if(et.is("#jstree-dragged"))return false;if(tree2.get_node(event.target).hasClass("closed")){tmp.open_time=setTimeout(function(){tree2.open_branch(et);},500);}
var et_off=et.offset();var goTo={x:(et_off.left-1),y:(event.pageY-et_off.top)};if(cnt.children("ul:eq(0)").hasClass("rtl"))goTo.x+=et.width()-8;var arr=[];if(goTo.y<tree2.li_height/3+1)arr=["before","inside","after"];else if(goTo.y>tree2.li_height*2/3-1)arr=["after","inside","before"];else{if(goTo.y<tree2.li_height/2)arr=["inside","before","after"];else arr=["inside","after","before"];}
var ok=false;$.each(arr,function(i,val){if(tree2.checkMove(tmp.origin_tree.container.find("li.dragged"),et,val)){mov=val;ok=true;return false;}});if(ok){switch(mov){case"before":goTo.y=et_off.top-2;if(cnt.children("ul:eq(0)").hasClass("rtl")){tree_component.drag_drop.marker.attr("src",tree2.settings.ui.theme_path+"marker_rtl.gif").width(40);}
else{tree_component.drag_drop.marker.attr("src",tree2.settings.ui.theme_path+"marker.gif").width(40);}
break;case"after":goTo.y=et_off.top-2+tree2.li_height;if(cnt.children("ul:eq(0)").hasClass("rtl")){tree_component.drag_drop.marker.attr("src",tree2.settings.ui.theme_path+"marker_rtl.gif").width(40);}
else{tree_component.drag_drop.marker.attr("src",tree2.settings.ui.theme_path+"marker.gif").width(40);}
break;case"inside":goTo.x-=2;if(cnt.children("ul:eq(0)").hasClass("rtl")){goTo.x+=36;}
goTo.y=et_off.top-2+tree2.li_height/2;tree_component.drag_drop.marker.attr("src",tree2.settings.ui.theme_path+"plus.gif").width(11);break;}
tmp.move_type=mov;tmp.ref_node=$(event.target);tmp.drag_help.find("IMG").remove();tree_component.drag_drop.marker.css({"left":goTo.x,"top":goTo.y}).show();}}
if((et.is(".tree")||et.is("ul"))&&et.find("li:eq(0)").size()==0){var et_off=et.offset();tmp.move_type="inside";tmp.ref_node=cnt.children("ul:eq(0)");tmp.drag_help.find("IMG").remove();tree_component.drag_drop.marker.attr("src",tree2.settings.ui.theme_path+"plus.gif").width(11);tree_component.drag_drop.marker.css({"left":et_off.left+(cnt.children("ul:eq(0)").hasClass("rtl")?(cnt.width()-10):10),"top":et_off.top+15}).show();}
else if(event.target.tagName!="A"||!ok){if(tmp.drag_help.find("IMG").size()==0){tmp.drag_help.find("li:eq(0)").append("<img style='position:absolute; "+(tmp.origin_tree.settings.ui.rtl?"right":"left")+":4px; top:0px; background:white; padding:2px;' src='"+tmp.origin_tree.settings.ui.theme_path+"remove.png' />");}
tmp.move_type=false;tmp.ref_node=false;tree_component.drag_drop.marker.hide();}
event.preventDefault();event.stopPropagation();return false;}
return true;};};return{cntr:++tree_component.cntr,settings:{data:{type:"predefined",method:"GET",async:false,async_data:function(NODE,TREE_OBJ){return{id:$(NODE).attr("id")||0}},url:false,json:false,xml:false},selected:false,opened:[],languages:[],path:false,cookies:false,ui:{dots:true,rtl:false,animation:0,hover_mode:true,scroll_spd:4,theme_path:false,theme_name:"default",context:[{id:"create",label:"Create",icon:"create.png",visible:function(NODE,TREE_OBJ){if(NODE.length!=1)return false;return TREE_OBJ.check("creatable",NODE);},action:function(NODE,TREE_OBJ){TREE_OBJ.create(false,TREE_OBJ.get_node(NODE[0]));}},"separator",{id:"rename",label:"Rename",icon:"rename.png",visible:function(NODE,TREE_OBJ){if(NODE.length!=1)return false;return TREE_OBJ.check("renameable",NODE);},action:function(NODE,TREE_OBJ){TREE_OBJ.rename(NODE);}},{id:"delete",label:"Delete",icon:"remove.png",visible:function(NODE,TREE_OBJ){var ok=true;$.each(NODE,function(){if(TREE_OBJ.check("deletable",this)==false)ok=false;return false;});return ok;},action:function(NODE,TREE_OBJ){$.each(NODE,function(){TREE_OBJ.remove(this);});}}]},rules:{multiple:false,metadata:false,type_attr:"rel",multitree:false,createat:"bottom",use_inline:false,clickable:"all",renameable:"all",deletable:"all",creatable:"all",draggable:"none",dragrules:"all",drag_copy:false,droppable:[],drag_button:"left"},lang:{new_node:"New folder",loading:"Loading ..."},callback:{beforechange:function(NODE,TREE_OBJ){return true},beforeopen:function(NODE,TREE_OBJ){return true},beforeclose:function(NODE,TREE_OBJ){return true},beforemove:function(NODE,REF_NODE,TYPE,TREE_OBJ){return true},beforecreate:function(NODE,REF_NODE,TYPE,TREE_OBJ){return true},beforerename:function(NODE,LANG,TREE_OBJ){return true},beforedelete:function(NODE,TREE_OBJ){return true},onJSONdata:function(DATA,TREE_OBJ){return DATA;},onselect:function(NODE,TREE_OBJ){},ondeselect:function(NODE,TREE_OBJ){},onchange:function(NODE,TREE_OBJ){},onrename:function(NODE,LANG,TREE_OBJ,RB){},onmove:function(NODE,REF_NODE,TYPE,TREE_OBJ,RB){},oncopy:function(NODE,REF_NODE,TYPE,TREE_OBJ,RB){},oncreate:function(NODE,REF_NODE,TYPE,TREE_OBJ,RB){},ondelete:function(NODE,TREE_OBJ,RB){},onopen:function(NODE,TREE_OBJ){},onopen_all:function(TREE_OBJ){},onclose:function(NODE,TREE_OBJ){},error:function(TEXT,TREE_OBJ){},ondblclk:function(NODE,TREE_OBJ){TREE_OBJ.toggle_branch.call(TREE_OBJ,NODE);TREE_OBJ.select_branch.call(TREE_OBJ,NODE);},onrgtclk:function(NODE,TREE_OBJ,EV){},onload:function(TREE_OBJ){},onfocus:function(TREE_OBJ){},ondrop:function(NODE,REF_NODE,TYPE,TREE_OBJ){}}},init:function(elem,conf){var _this=this;this.container=$(elem);if(this.container.size==0){alert("Invalid container node!");return}
tree_component.inst[this.cntr]=this;if(!this.container.attr("id"))this.container.attr("id","jstree_"+this.cntr);tree_component.inst[this.container.attr("id")]=tree_component.inst[this.cntr];tree_component.focused=this.cntr;var opts=$.extend({},conf);if(opts&&opts.cookies){this.settings.cookies=$.extend({},this.settings.cookies,opts.cookies);delete opts.cookies;if(!this.settings.cookies.opts)this.settings.cookies.opts={};}
if(opts&&opts.callback){this.settings.callback=$.extend({},this.settings.callback,opts.callback);delete opts.callback;}
if(opts&&opts.data){this.settings.data=$.extend({},this.settings.data,opts.data);delete opts.data;}
if(opts&&opts.ui){this.settings.ui=$.extend({},this.settings.ui,opts.ui);delete opts.ui;}
if(opts&&opts.rules){this.settings.rules=$.extend({},this.settings.rules,opts.rules);delete opts.rules;}
if(opts&&opts.lang){this.settings.lang=$.extend({},this.settings.lang,opts.lang);delete opts.lang;}
this.settings=$.extend({},this.settings,opts);if(this.settings.path==false){this.path="";$("script").each(function(){if(this.src.toString().match(/tree_component.*?js$/)){_this.path=this.src.toString().replace(/tree_component.*?js$/,"");}});}
else this.path=this.settings.path;this.current_lang=this.settings.languages&&this.settings.languages.length?this.settings.languages[0]:false;if(this.settings.languages&&this.settings.languages.length){this.sn=get_sheet_num("tree_component.css");if(this.sn===false&&document.styleSheets.length)this.sn=document.styleSheets.length;var st=false;var id=this.container.attr("id")?"#"+this.container.attr("id"):".tree";for(var ln=0;ln<this.settings.languages.length;ln++){st=add_css(id+" ."+this.settings.languages[ln],this.sn);if(st!==false){if(this.settings.languages[ln]==this.current_lang)st.style.display="";else st.style.display="none";}}}
if(this.settings.rules.droppable.length){for(var i in this.settings.rules.droppable){if(typeof this.settings.rules.droppable[i]=="function")continue;tree_component.drag_drop.droppable.push(this.settings.rules.droppable[i]);}
tree_component.drag_drop.droppable=$.unique(tree_component.drag_drop.droppable);}
if(this.settings.ui.theme_path===false)this.settings.ui.theme_path=this.path+"themes/";this.theme=this.settings.ui.theme_path;if(_this.settings.ui.theme_name){this.theme+=_this.settings.ui.theme_name+"/";if(_this.settings.ui.theme_name!="themeroller"&&!tree_component.def_style){add_sheet(_this.settings.ui.theme_path+"default/style.css");tree_component.def_style=true;}
add_sheet(_this.theme+"style.css");}
this.container.addClass("tree");if(_this.settings.ui.theme_name!="themeroller")this.container.addClass("tree-default");if(this.settings.ui.theme_name&&this.settings.ui.theme_name!="default")this.container.addClass("tree-"+_this.settings.ui.theme_name);if(this.settings.ui.theme_name=="themeroller")this.container.addClass("ui-widget ui-widget-content");if(this.settings.rules.multiple)this.selected_arr=[];this.offset=false;this.context_menu();this.hovered=false;this.locked=false;if(this.settings.rules.draggable!="none"&&tree_component.drag_drop.marker===false){var _this=this;tree_component.drag_drop.marker=$("<img>").attr({id:"marker",src:_this.settings.ui.theme_path+"marker.gif"}).css({height:"5px",width:"40px",display:"block",position:"absolute",left:"30px",top:"30px",zIndex:"1000"}).hide().appendTo("body");}
this.refresh();this.attachEvents();this.focus();},off_height:function(){if(this.offset===false){this.container.css({position:"relative"});this.offset=this.container.offset();var tmp=0;tmp=parseInt($.curCSS(this.container.get(0),"paddingTop",true),10);if(tmp)this.offset.top+=tmp;tmp=parseInt($.curCSS(this.container.get(0),"borderTopWidth",true),10);if(tmp)this.offset.top+=tmp;this.container.css({position:""});}
if(!this.li_height){var tmp=this.container.find("ul li.closed, ul li.leaf").eq(0);this.li_height=tmp.height();if(tmp.children("ul:eq(0)").size())this.li_height-=tmp.children("ul:eq(0)").height();if(!this.li_height)this.li_height=18;}},context_menu:function(){this.context=false;if(this.settings.ui.context!=false){var str='<div class="tree-context tree-default-context tree-'+this.settings.ui.theme_name+'-context">';for(var i in this.settings.ui.context){if(typeof this.settings.ui.context[i]=="function")continue;if(this.settings.ui.context[i]=="separator"){str+="<span class='separator'> </span>";continue;}
var icn="";if(this.settings.ui.context[i].icon)icn='background-image:url(\''+(this.settings.ui.context[i].icon.indexOf("/")==-1?this.theme+this.settings.ui.context[i].icon:this.settings.ui.context[i].icon)+'\');';str+='<a rel="'+this.settings.ui.context[i].id+'" href="#" style="'+icn+'">'+this.settings.ui.context[i].label+'</a>';}
str+='</div>';this.context=$(str);this.context.hide();this.context.append=false;}},refresh:function(obj){if(this.locked)return this.error("LOCKED");var _this=this;this.is_partial_refresh=obj?true:false;this.opened=Array();if(this.settings.cookies&&$.cookie(this.settings.cookies.prefix+'_open')){var str=$.cookie(this.settings.cookies.prefix+'_open');var tmp=str.split(",");$.each(tmp,function(){if(this.replace(/^#/,"").length>0){_this.opened.push("#"+this.replace(/^#/,""));}});this.settings.opened=false;}
else if(this.settings.opened!=false){$.each(this.settings.opened,function(i,item){if(this.replace(/^#/,"").length>0){_this.opened.push("#"+this.replace(/^#/,""));}});this.settings.opened=false;}
else{this.container.find("li.open").each(function(i){if(this.id){_this.opened.push("#"+this.id);}});}
if(this.selected){this.settings.selected=Array();if(obj){$(obj).find("li:has(a.clicked)").each(function(){$this=$(this);if($this.attr("id"))_this.settings.selected.push("#"+$this.attr("id"));});}
else{if(this.selected_arr){$.each(this.selected_arr,function(){if(this.attr("id"))_this.settings.selected.push("#"+this.attr("id"));});}
else{if(this.selected.attr("id"))this.settings.selected.push("#"+this.selected.attr("id"));}}}
else if(this.settings.cookies&&$.cookie(this.settings.cookies.prefix+'_selected')){this.settings.selected=Array();var str=$.cookie(this.settings.cookies.prefix+'_selected');var tmp=str.split(",");$.each(tmp,function(){if(this.replace(/^#/,"").length>0){_this.settings.selected.push("#"+this.replace(/^#/,""));}});}
else if(this.settings.selected!==false){var tmp=Array();if((typeof this.settings.selected).toLowerCase()=="object"){$.each(this.settings.selected,function(){if(this.replace(/^#/,"").length>0)tmp.push("#"+this.replace(/^#/,""));});}
else{if(this.settings.selected.replace(/^#/,"").length>0)tmp.push("#"+this.settings.selected.replace(/^#/,""));}
this.settings.selected=tmp;}
if(obj&&this.settings.data.async){this.opened=Array();obj=this.get_node(obj);obj.find("li.open").each(function(i){_this.opened.push("#"+this.id);});if(obj.hasClass("open"))obj.removeClass("open").addClass("closed");if(obj.hasClass("leaf"))obj.removeClass("leaf");obj.children("ul:eq(0)").html("");return this.open_branch(obj,true,function(){_this.reselect.apply(_this);});}
if(this.settings.data.type=="xml_flat"||this.settings.data.type=="xml_nested"){this.scrtop=this.container.get(0).scrollTop;var xsl=(this.settings.data.type=="xml_flat")?"flat.xsl":"nested.xsl";if(this.settings.data.xml)this.container.getTransform(this.path+xsl,this.settings.data.xml,{params:{theme_name:_this.settings.ui.theme_name,theme_path:_this.theme},meth:_this.settings.data.method,dat:_this.settings.data.async_data.apply(_this,[obj,_this]),callback:function(){_this.context_menu.apply(_this);_this.reselect.apply(_this);}});else this.container.getTransform(this.path+xsl,this.settings.data.url,{params:{theme_name:_this.settings.ui.theme_name,theme_path:_this.theme},meth:_this.settings.data.method,dat:_this.settings.data.async_data.apply(_this,[obj,_this]),callback:function(){_this.context_menu.apply(_this);_this.reselect.apply(_this);}});return;}
else if(this.settings.data.type=="json"){if(this.settings.data.json){var str="";if(this.settings.data.json.length){for(var i=0;i<this.settings.data.json.length;i++){str+=this.parseJSON(this.settings.data.json[i]);}}else str=this.parseJSON(this.settings.data.json);this.container.html("<ul>"+str+"</ul>");this.container.find("li:last-child").addClass("last").end().find("li:has(ul)").not(".open").addClass("closed");this.container.find("li").not(".open").not(".closed").addClass("leaf");this.context_menu();this.reselect();}
else{var _this=this;$.ajax({type:this.settings.data.method,url:this.settings.data.url,data:this.settings.data.async_data(false,this),dataType:"json",success:function(data){data=_this.settings.callback.onJSONdata.call(null,data,_this);var str="";if(data.length){for(var i=0;i<data.length;i++){str+=_this.parseJSON(data[i]);}}else str=_this.parseJSON(data);_this.container.html("<ul>"+str+"</ul>");_this.container.find("li:last-child").addClass("last").end().find("li:has(ul)").not(".open").addClass("closed");_this.container.find("li").not(".open").not(".closed").addClass("leaf");_this.context_menu.apply(_this);_this.reselect.apply(_this);},error:function(xhttp,textStatus,errorThrown){_this.error(errorThrown+" "+textStatus);}});}}
else{this.container.children("ul:eq(0)");this.container.find("li:last-child").addClass("last").end().find("li:has(ul)").not(".open").addClass("closed");this.container.find("li").not(".open").not(".closed").addClass("leaf");this.reselect();}},parseJSON:function(data){if(!data||!data.data)return"";var str="";str+="<li ";var cls=false;if(data.attributes){for(var i in data.attributes){if(typeof data.attributes[i]=="function")continue;if(i=="class"){str+=" class='"+data.attributes[i]+" ";if(data.state=="closed"||data.state=="open")str+=" "+data.state+" ";str+="' ";cls=true;}
else str+=" "+i+"='"+data.attributes[i]+"' ";}}
if(!cls&&(data.state=="closed"||data.state=="open"))str+=" class='"+data.state+"' ";str+=">";if(this.settings.languages.length){for(var i=0;i<this.settings.languages.length;i++){var attr={};attr["href"]="#";attr["style"]="";attr["class"]=this.settings.languages[i];if(data.data[this.settings.languages[i]]&&(typeof data.data[this.settings.languages[i]].attributes).toLowerCase()!="undefined"){for(var j in data.data[this.settings.languages[i]].attributes){if(typeof data.data[this.settings.languages[i]].attributes[j]=="function")continue;if(j=="style"||j=="class")attr[j]+=" "+data.data[this.settings.languages[i]].attributes[j];else attr[j]=data.data[this.settings.languages[i]].attributes[j];}}
if(data.data[this.settings.languages[i]]&&data.data[this.settings.languages[i]].icon&&this.settings.theme_name!="themeroller"){var icn=data.data[this.settings.languages[i]].icon.indexOf("/")==-1?this.theme+data.data[this.settings.languages[i]].icon:data.data[this.settings.languages[i]].icon;attr["style"]+=" ; background-image:url('"+icn+"'); ";}
str+="<a";for(var j in attr){if(typeof attr[j]=="function")continue;str+=' '+j+'="'+attr[j]+'" ';}
str+=">";if(data.data[this.settings.languages[i]]&&data.data[this.settings.languages[i]].icon&&this.settings.theme_name=="themeroller"){str+="<ins class='ui-icon "+data.data[this.settings.languages[i]].icon+"'> </ins>";}
str+=((typeof data.data[this.settings.languages[i]].title).toLowerCase()!="undefined"?data.data[this.settings.languages[i]].title:data.data[this.settings.languages[i]])+"</a>";}}
else{var attr={};attr["href"]="#";attr["style"]="";attr["class"]="";if((typeof data.data.attributes).toLowerCase()!="undefined"){for(var i in data.data.attributes){if(typeof data.data.attributes[i]=="function")continue;if(i=="style"||i=="class")attr[i]+=" "+data.data.attributes[i];else attr[i]=data.data.attributes[i];}}
if(data.data.icon&&this.settings.ui.theme_name!="themeroller"){var icn=data.data.icon.indexOf("/")==-1?this.theme+data.data.icon:data.data.icon;attr["style"]+=" ; background-image:url('"+icn+"');";}
str+="<a";for(var i in attr){if(typeof attr[j]=="function")continue;str+=' '+i+'="'+attr[i]+'" ';}
str+=">";if(data.data.icon&&this.settings.ui.theme_name=="themeroller"){str+="<ins class='ui-icon "+data.data.icon+"'> </ins>";}
str+=((typeof data.data.title).toLowerCase()!="undefined"?data.data.title:data.data)+"</a>";}
if(data.children&&data.children.length){str+='<ul>';for(var i=0;i<data.children.length;i++){str+=this.parseJSON(data.children[i]);}
str+='</ul>';}
str+="</li>";return str;},getJSON:function(nod,outer_attrib,inner_attrib,force){var _this=this;if(!nod||$(nod).size()==0){nod=this.container.children("ul").children("li");}
else nod=$(nod);if(nod.size()>1){var arr=[];nod.each(function(){arr.push(_this.getJSON(this,outer_attrib,inner_attrib,force));});return arr;}
if(!outer_attrib)outer_attrib=["id","rel","class"];if(!inner_attrib)inner_attrib=[];var obj={attributes:{},data:false};for(var i in outer_attrib){if(typeof outer_attrib[i]=="function")continue;var val=(outer_attrib[i]=="class")?nod.attr(outer_attrib[i]).replace("last","").replace("leaf","").replace("closed","").replace("open",""):nod.attr(outer_attrib[i]);if(typeof val!="undefined"&&val.replace(" ","").length>0)obj.attributes[outer_attrib[i]]=val;delete val;}
if(this.settings.languages.length){obj.data={};for(var i in this.settings.languages){if(typeof this.settings.languages[i]=="function")continue;var a=nod.children("a."+this.settings.languages[i]);if(force||inner_attrib.length||a.get(0).style.backgroundImage.toString().length){obj.data[this.settings.languages[i]]={};obj.data[this.settings.languages[i]].title=a.text();if(a.get(0).style.backgroundImage.length){obj.data[this.settings.languages[i]].icon=a.get(0).style.backgroundImage.replace("url(","").replace(")","");}
if(this.settings.ui.theme_name=="themeroller"&&a.children("ins").size()){var tmp=a.children("ins").attr("class");var cls=false;$.each(tmp.split(" "),function(i,val){if(val.indexOf("ui-icon-")==0){cls=val;return false;}});if(cls)obj.data[this.settings.languages[i]].icon=cls;}
if(inner_attrib.length){obj.data[this.settings.languages[i]].attributes={};for(var j in inner_attrib){if(typeof inner_attrib[j]=="function")continue;var val=a.attr(inner_attrib[j]);if(typeof val!="undefined"&&val.replace(" ","").length>0)obj.data[this.settings.languages[i]].attributes[inner_attrib[j]]=val;delete val;}}}
else{obj.data[this.settings.languages[i]]=a.text();}}}
else{var a=nod.children("a");if(force||inner_attrib.length||a.get(0).style.backgroundImage.toString().length){obj.data={};obj.data.title=a.text();if(a.get(0).style.backgroundImage.length){obj.data.icon=a.get(0).style.backgroundImage.replace("url(","").replace(")","");}
if(this.settings.ui.theme_name=="themeroller"&&a.children("ins").size()){var tmp=a.children("ins").attr("class");var cls=false;$.each(tmp.split(" "),function(i,val){if(val.indexOf("ui-icon-")==0){cls=val;return false;}});if(cls)obj.data[this.settings.languages[i]].icon=cls;}
if(inner_attrib.length){obj.data.attributes={};for(var j in inner_attrib){if(typeof inner_attrib[j]=="function")continue;var val=a.attr(inner_attrib[j]);if(typeof val!="undefined"&&val.replace(" ","").length>0)obj.data.attributes[inner_attrib[j]]=val;delete val;}}}
else{obj.data=a.text();}}
if(nod.children("ul").size()>0){obj.children=[];nod.children("ul").children("li").each(function(){obj.children.push(_this.getJSON(this,outer_attrib,inner_attrib,force));});}
return obj;},getXML:function(tp,nod,outer_attrib,inner_attrib,cb){var _this=this;if(tp!="flat")tp="nested";if(!nod||$(nod).size()==0){nod=this.container.children("ul").children("li");}
else nod=$(nod);if(nod.size()>1){var obj='<root>';nod.each(function(){obj+=_this.getXML(tp,this,outer_attrib,inner_attrib,true);});obj+='</root>';return obj;}
if(!outer_attrib)outer_attrib=["id","rel","class"];if(!inner_attrib)inner_attrib=[];var obj='';if(!cb)obj='<root>';obj+='<item ';if(tp=="flat"){var tmp_id=nod.parents("li:eq(0)").size()?nod.parents("li:eq(0)").attr("id"):0;obj+=' parent_id="'+tmp_id+'" ';delete tmp_id;}
for(var i in outer_attrib){if(typeof outer_attrib[i]=="function")continue;var val=(outer_attrib[i]=="class")?nod.attr(outer_attrib[i]).replace("last","").replace("leaf","").replace("closed","").replace("open",""):nod.attr(outer_attrib[i]);if(typeof val!="undefined"&&val.replace(" ","").length>0)obj+=' '+outer_attrib[i]+'="'+val+'" ';delete val;}
obj+='>';obj+='<content>';if(this.settings.languages.length){for(var i in this.settings.languages){if(typeof this.settings.languages[i]=="function")continue;var a=nod.children("a."+this.settings.languages[i]);obj+='<name ';if(inner_attrib.length||a.get(0).style.backgroundImage.toString().length||this.settings.ui.theme_name=="themeroller"){if(a.get(0).style.backgroundImage.length){obj+=' icon="'+a.get(0).style.backgroundImage.replace("url(","").replace(")","")+'" ';}
if(this.settings.ui.theme_name=="themeroller"&&a.children("ins").size()){var tmp=a.children("ins").attr("class");var cls=false;$.each(tmp.split(" "),function(i,val){if(val.indexOf("ui-icon-")==0){cls=val;return false;}});if(cls)obj+=' icon="'+cls+'" ';}
if(inner_attrib.length){for(var j in inner_attrib){if(typeof inner_attrib[j]=="function")continue;var val=a.attr(inner_attrib[j]);if(typeof val!="undefined"&&val.replace(" ","").length>0)obj+=' '+inner_attrib[j]+'="'+val+'" ';delete val;}}}
obj+='><![CDATA['+a.text()+']]></name>';}}
else{var a=nod.children("a");obj+='<name ';if(inner_attrib.length||a.get(0).style.backgroundImage.toString().length||this.settings.ui.theme_name=="themeroller"){if(a.get(0).style.backgroundImage.length){obj+=' icon="'+a.get(0).style.backgroundImage.replace("url(","").replace(")","")+'" ';}
if(this.settings.ui.theme_name=="themeroller"&&a.children("ins").size()){var tmp=a.children("ins").attr("class");var cls=false;$.each(tmp.split(" "),function(i,val){if(val.indexOf("ui-icon-")==0){cls=val;return false;}});if(cls)obj+=' icon="'+cls+'" ';}
if(inner_attrib.length){for(var j in inner_attrib){if(typeof inner_attrib[j]=="function")continue;var val=a.attr(inner_attrib[j]);if(typeof val!="undefined"&&val.replace(" ","").length>0)obj+=' '+inner_attrib[j]+'="'+val+'" ';delete val;}}}
obj+='><![CDATA['+a.text()+']]></name>';}
obj+='</content>';if(tp=="flat")obj+='</item>';if(nod.children("ul").size()>0){nod.children("ul").children("li").each(function(){obj+=_this.getXML(tp,this,outer_attrib,inner_attrib,true);});}
if(tp=="nested")obj+='</item>';if(!cb)obj+='</root>';return obj;},focus:function(){if(this.locked)return false;if(tree_component.focused!=this.cntr){tree_component.focused=this.cntr;this.settings.callback.onfocus.call(null,this);}},show_context:function(obj){this.context.show();var tmp=$(obj).children("a:visible").offset();this.context.css({"left":(tmp.left),"top":(tmp.top+parseInt(obj.children("a:visible").height())+2)});},hide_context:function(){if(this.context.to_remove&&this.context.apply_to)this.context.apply_to.children("a").removeClass("clicked");this.context.apply_to=false;this.context.hide();},attachEvents:function(){var _this=this;this.container.bind("mousedown.jstree",function(event){if(tree_component.drag_drop.isdown){tree_component.drag_drop.move_type=false;event.preventDefault();event.stopPropagation();event.stopImmediatePropagation();return false;}}).bind("mouseup.jstree",function(event){setTimeout(function(){_this.focus.apply(_this);},5);}).bind("click.jstree",function(event){return true;});$("#"+this.container.attr("id")+" li").live("click",function(event){if(event.target.tagName!="LI")return true;_this.off_height();if(event.pageY-$(event.target).offset().top>_this.li_height)return true;_this.toggle_branch.apply(_this,[event.target]);event.stopPropagation();return false;});$("#"+this.container.attr("id")+" li a").live("click.jstree",function(event){if(event.which&&event.which==3)return true;if(_this.locked){event.preventDefault();event.target.blur();return _this.error("LOCKED");}
_this.select_branch.apply(_this,[event.target,event.ctrlKey||_this.settings.rules.multiple=="on"]);if(_this.inp){_this.inp.blur();}
event.preventDefault();event.target.blur();return false;}).live("dblclick.jstree",function(event){if(_this.locked){event.preventDefault();event.stopPropagation();event.target.blur();return _this.error("LOCKED");}
_this.settings.callback.ondblclk.call(null,_this.get_node(event.target).get(0),_this);event.preventDefault();event.stopPropagation();event.target.blur();}).live("contextmenu.jstree",function(event){if(_this.locked){event.target.blur();return _this.error("LOCKED");}
var val=_this.settings.callback.onrgtclk.call(null,_this.get_node(event.target).get(0),_this,event);if(_this.context){if(_this.context.append==false){$("body").append(_this.context);_this.context.append=true;for(var i in _this.settings.ui.context){if(typeof _this.settings.ui.context[i]=="function")continue;if(_this.settings.ui.context[i]=="separator")continue;(function(){var func=_this.settings.ui.context[i].action;_this.context.children("[rel="+_this.settings.ui.context[i].id+"]").bind("click",function(event){if(!$(this).hasClass("disabled")){func.call(null,_this.context.apply_to||null,_this);_this.hide_context();}
event.stopPropagation();event.preventDefault();return false;}).bind("mouseup",function(event){this.blur();if($(this).hasClass("disabled")){event.stopPropagation();event.preventDefault();return false;}}).bind("mousedown",function(event){event.stopPropagation();event.preventDefault();});})();}}
var obj=_this.get_node(event.target);if(_this.inp){_this.inp.blur();}
if(obj){if(!obj.children("a:eq(0)").hasClass("clicked")){_this.context.apply_to=obj;_this.context.to_remove=true;_this.context.apply_to.children("a").addClass("clicked");event.target.blur();}
else{_this.context.to_remove=false;_this.context.apply_to=(_this.selected_arr&&_this.selected_arr.length>1)?_this.selected_arr:_this.selected;}
_this.context.children("a").removeClass("disabled").show();var go=false;for(var i in _this.settings.ui.context){if(typeof _this.settings.ui.context[i]=="function")continue;if(_this.settings.ui.context[i]=="separator")continue;var state=_this.settings.ui.context[i].visible.call(null,_this.context.apply_to,_this);if(state===false)_this.context.children("[rel="+_this.settings.ui.context[i].id+"]").addClass("disabled");if(state===-1)_this.context.children("[rel="+_this.settings.ui.context[i].id+"]").hide();else go=true;}
if(go==true)_this.show_context(obj);event.preventDefault();event.stopPropagation();return false;}}
return val;}).live("mouseover.jstree",function(event){if(_this.locked){event.preventDefault();event.stopPropagation();return _this.error("LOCKED");}
if((_this.settings.ui.hover_mode||_this.settings.ui.theme_name=="themeroller")&&_this.hovered!==false&&event.target.tagName=="A"){_this.hovered.children("a").removeClass("hover ui-state-hover");_this.hovered=false;}
if(_this.settings.ui.theme_name=="themeroller"){_this.hover_branch.apply(_this,[event.target]);}});if(_this.settings.ui.theme_name=="themeroller"){$("#"+this.container.attr("id")+" li a").live("mouseout",function(event){if(_this.hovered)_this.hovered.children("a").removeClass("hover ui-state-hover");});}
if(this.settings.rules.draggable!="none"){$("#"+this.container.attr("id")+" li a").live("mousedown.jstree",function(event){if(_this.settings.rules.drag_button=="left"&&event.which&&event.which!=1)return true;if(_this.settings.rules.drag_button=="right"&&event.which&&event.which!=3)return true;_this.focus.apply(_this);if(_this.locked)return _this.error("LOCKED");var obj=_this.get_node(event.target);if(_this.settings.rules.multiple!=false&&_this.selected_arr.length>1&&obj.children("a:eq(0)").hasClass("clicked")){var counter=0;for(var i in _this.selected_arr){if(typeof _this.selected_arr[i]=="function")continue;if(_this.check("draggable",_this.selected_arr[i])){_this.selected_arr[i].addClass("dragged");tree_component.drag_drop.origin_tree=_this;counter++;}}
if(counter>0){if(_this.check("draggable",obj))tree_component.drag_drop.drag_node=obj;else tree_component.drag_drop.drag_node=_this.container.find("li.dragged:eq(0)");tree_component.drag_drop.isdown=true;tree_component.drag_drop.drag_help=$("<div id='jstree-dragged' class='tree "+(_this.container.hasClass("tree-default")?" tree-default":"")+(_this.settings.ui.theme_name&&_this.settings.ui.theme_name!="default"?" tree-"+_this.settings.ui.theme_name:"")+"' />").append("<ul class='"+_this.container.children("ul:eq(0)").get(0).className+"' />");var tmp=$(tree_component.drag_drop.drag_node.get(0).cloneNode(true));if(_this.settings.languages.length>0)tmp.find("a").not("."+_this.current_lang).hide();tree_component.drag_drop.drag_help.children("ul:eq(0)").append(tmp);tree_component.drag_drop.drag_help.find("li:eq(0)").removeClass("last").addClass("last").children("a").html("Multiple selection").end().children("ul").remove();}}
else{if(_this.check("draggable",obj)){tree_component.drag_drop.drag_node=obj;tree_component.drag_drop.drag_help=$("<div id='jstree-dragged' class='tree "+(_this.container.hasClass("tree-default")?" tree-default":"")+(_this.settings.ui.theme_name&&_this.settings.ui.theme_name!="default"?" tree-"+_this.settings.ui.theme_name:"")+"' />").append("<ul class='"+_this.container.children("ul:eq(0)").get(0).className+"' />");var tmp=$(obj.get(0).cloneNode(true));if(_this.settings.languages.length>0)tmp.find("a").not("."+_this.current_lang).hide();tree_component.drag_drop.drag_help.children("ul:eq(0)").append(tmp);tree_component.drag_drop.drag_help.find("li:eq(0)").removeClass("last").addClass("last");tree_component.drag_drop.isdown=true;tree_component.drag_drop.foreign=false;tree_component.drag_drop.origin_tree=_this;obj.addClass("dragged");}}
tree_component.drag_drop.init_x=event.pageX;tree_component.drag_drop.init_y=event.pageY;obj.blur();event.preventDefault();event.stopPropagation();return false;});$(document).bind("mousedown.jstree",tree_component.mousedown).bind("mouseup.jstree",tree_component.mouseup).bind("mousemove.jstree",tree_component.mousemove);}
if(_this.context)$(document).bind("mousedown",function(){_this.hide_context();});},checkMove:function(NODES,REF_NODE,TYPE){if(this.locked)return this.error("LOCKED");var _this=this;if(REF_NODE.parents("li.dragged").size()>0||REF_NODE.is(".dragged"))return this.error("MOVE: NODE OVER SELF");if(NODES.size()==1){var NODE=NODES.eq(0);if(tree_component.drag_drop.foreign){if(this.settings.rules.droppable.length==0)return false;if(!NODE.is("."+this.settings.rules.droppable.join(", .")))return false;var ok=false;for(var i in this.settings.rules.droppable){if(typeof this.settings.rules.droppable[i]=="function")continue;if(NODE.is("."+this.settings.rules.droppable[i])){if(this.settings.rules.metadata){$.metadata.setType("attr",this.settings.rules.metadata);NODE.attr(this.settings.rules.metadata,"type: '"+this.settings.rules.droppable[i]+"'");}
else{NODE.attr(this.settings.rules.type_attr,this.settings.rules.droppable[i]);}
ok=true;break;}}
if(!ok)return false;}
if(!this.check("dragrules",[NODE,TYPE,REF_NODE.parents("li:eq(0)")]))return this.error("MOVE: AGAINST DRAG RULES");}
else{var ok=true;NODES.each(function(i){if(ok==false)return false;var ref=REF_NODE;var mv=TYPE;if(!_this.check.apply(_this,["dragrules",[$(this),mv,ref]]))ok=false;});if(ok==false)return this.error("MOVE: AGAINST DRAG RULES");}
if(this.settings.rules.use_inline&&this.settings.rules.metadata){var nd=false;if(TYPE=="inside")nd=REF_NODE.parents("li:eq(0)");else nd=REF_NODE.parents("li:eq(1)");if(nd.size()){if(typeof nd.metadata()["valid_children"]!="undefined"){var tmp=nd.metadata()["valid_children"];var ok=true;NODES.each(function(i){if(ok==false)return false;if($.inArray(_this.get_type(this),tmp)==-1)ok=false;});if(ok==false)return this.error("MOVE: NOT A VALID CHILD");}
if(typeof nd.metadata()["max_children"]!="undefined"){if((nd.children("ul:eq(0)").children("li").not(".dragged").size()+NODES.size())>nd.metadata().max_children)return this.error("MOVE: MAX CHILDREN REACHED");}
var incr=0;NODES.each(function(j){var i=1;var t=$(this);while(i<100){t=t.children("ul").children("li");if(t.size()==0)break;i++}
incr=Math.max(i,incr);});var ok=true;if((typeof $(nd).metadata().max_depth).toLowerCase()!="undefined"&&$(nd).metadata().max_depth<incr)ok=false;else{nd.parents("li").each(function(i){if(ok==false)return false;if((typeof $(this).metadata().max_depth).toLowerCase()!="undefined"){if((i+incr)>=$(this).metadata().max_depth)ok=false;}});}
if(ok==false)return this.error("MOVE: MAX_DEPTH REACHED");}}
return true;},reselect:function(is_callback){var _this=this;if(!is_callback)this.cl_count=0;else this.cl_count--;if(this.opened&&this.opened.length){var opn=false;for(var j=0;this.opened&&j<this.opened.length;j++){if(this.settings.data.async){if(this.get_node(this.opened[j]).size()>0){opn=true;var tmp=this.opened[j];delete this.opened[j];this.open_branch(tmp,true,function(){_this.reselect.apply(_this,[true]);});this.cl_count++;}}
else this.open_branch(this.opened[j],true);}
if(this.settings.data.async&&opn)return;delete this.opened;}
if(this.cl_count>0)return;if(this.settings.ui.rtl)this.container.css("direction","rtl").children("ul:eq(0)").addClass("rtl");else this.container.css("direction","ltr").children("ul:eq(0)").addClass("ltr");if(this.settings.ui.dots==false)this.container.children("ul:eq(0)").addClass("no_dots");if(this.scrtop){this.container.scrollTop(_this.scrtop);delete this.scrtop;}
if(this.settings.selected!==false){$.each(this.settings.selected,function(i){if(_this.is_partial_refresh)_this.select_branch($(_this.settings.selected[i],_this.container),(_this.settings.rules.multiple!==false));else _this.select_branch($(_this.settings.selected[i],_this.container),(_this.settings.rules.multiple!==false&&i>0));});this.settings.selected=false;}
if(this.settings.ui.theme_name=="themeroller")this.container.find("a").addClass("ui-state-default");this.settings.callback.onload.call(null,_this);},get_node:function(obj){var obj=$(obj);return obj.is("li")?obj:obj.parents("li:eq(0)");},get_type:function(obj){obj=!obj?this.selected:this.get_node(obj);if(!obj)return;if(this.settings.rules.metadata){$.metadata.setType("attr",this.settings.rules.metadata);var tmp=obj.metadata().type;if(tmp)return tmp;}
return obj.attr(this.settings.rules.type_attr);},scrollCheck:function(x,y){var _this=this;var cnt=_this.container;var off=_this.container.offset();var st=cnt.scrollTop();var sl=cnt.scrollLeft();var h_cor=(cnt.get(0).scrollWidth>cnt.width())?40:20;if(y-off.top<20)cnt.scrollTop(Math.max((st-_this.settings.ui.scroll_spd),0));if(cnt.height()-(y-off.top)<h_cor)cnt.scrollTop(st+_this.settings.ui.scroll_spd);if(x-off.left<20)cnt.scrollLeft(Math.max((sl-_this.settings.ui.scroll_spd),0));if(cnt.width()-(x-off.left)<40)cnt.scrollLeft(sl+_this.settings.ui.scroll_spd);if(cnt.scrollLeft()!=sl||cnt.scrollTop()!=st){_this.moveType=false;_this.moveRef=false;tree_component.drag_drop.marker.hide();}
tree_component.drag_drop.scroll_time=setTimeout(function(){_this.scrollCheck(x,y);},50);},check:function(rule,nodes){if(this.locked)return this.error("LOCKED");if(rule!="dragrules"&&this.settings.rules.use_inline&&this.settings.rules.metadata){$.metadata.setType("attr",this.settings.rules.metadata);if(typeof this.get_node(nodes).metadata()[rule]!="undefined")return this.get_node(nodes).metadata()[rule];}
if(!this.settings.rules[rule])return false;if(this.settings.rules[rule]=="none")return false;if(this.settings.rules[rule]=="all")return true;if(rule=="dragrules"){var nds=new Array();nds[0]=this.get_type(nodes[0]);nds[1]=nodes[1];nds[2]=this.get_type(nodes[2]);for(var i=0;i<this.settings.rules.dragrules.length;i++){var r=this.settings.rules.dragrules[i];var n=(r.indexOf("!")===0)?false:true;if(!n)r=r.replace("!","");var tmp=r.split(" ");for(var j=0;j<3;j++){if(tmp[j]==nds[j]||tmp[j]=="*")tmp[j]=true;}
if(tmp[0]===true&&tmp[1]===true&&tmp[2]===true)return n;}
return false;}
else
return($.inArray(this.get_type(nodes),this.settings.rules[rule])!=-1)?true:false;},hover_branch:function(obj){if(this.locked)return this.error("LOCKED");if(this.settings.ui.hover_mode==false&&this.settings.ui.theme_name!="themeroller")return this.select_branch(obj);var _this=this;var obj=_this.get_node(obj);if(!obj.size())return this.error("HOVER: NOT A VALID NODE");if(!_this.check("clickable",obj))return this.error("SELECT: NODE NOT SELECTABLE");if(this.hovered)this.hovered.children("A").removeClass("hover ui-state-hover");this.hovered=obj;this.hovered.children("a").removeClass("hover ui-state-hover").addClass(this.settings.ui.theme_name=="themeroller"?"hover ui-state-hover":"hover");var off_t=this.hovered.offset().top;var beg_t=this.container.offset().top;var end_t=beg_t+this.container.height();var h_cor=(this.container.get(0).scrollWidth>this.container.width())?40:20;if(off_t+5<beg_t)this.container.scrollTop(this.container.scrollTop()-(beg_t-off_t+5));if(off_t+h_cor>end_t)this.container.scrollTop(this.container.scrollTop()+(off_t+h_cor-end_t));},select_branch:function(obj,multiple){if(this.locked)return this.error("LOCKED");if(!obj&&this.hovered!==false)obj=this.hovered;var _this=this;obj=_this.get_node(obj);if(!obj.size())return this.error("SELECT: NOT A VALID NODE");obj.children("a").removeClass("hover ui-state-hover");if(!_this.check("clickable",obj))return this.error("SELECT: NODE NOT SELECTABLE");if(_this.settings.callback.beforechange.call(null,obj.get(0),_this)===false)return this.error("SELECT: STOPPED BY USER");if(this.settings.rules.multiple!=false&&multiple&&obj.children("a.clicked").size()>0){return this.deselect_branch(obj);}
if(this.settings.rules.multiple!=false&&multiple){this.selected_arr.push(obj);}
if(this.settings.rules.multiple!=false&&!multiple){for(var i in this.selected_arr){if(typeof this.selected_arr[i]=="function")continue;this.selected_arr[i].children("A").removeClass("clicked ui-state-active");this.settings.callback.ondeselect.call(null,this.selected_arr[i].get(0),_this);}
this.selected_arr=[];this.selected_arr.push(obj);if(this.selected&&this.selected.children("A").hasClass("clicked")){this.selected.children("A").removeClass("clicked ui-state-active");this.settings.callback.ondeselect.call(null,this.selected.get(0),_this);}}
if(!this.settings.rules.multiple){if(this.selected){this.selected.children("A").removeClass("clicked ui-state-active");this.settings.callback.ondeselect.call(null,this.selected.get(0),_this);}}
this.selected=obj;if((this.settings.ui.hover_mode||this.settings.ui.theme_name=="themeroller")&&this.hovered!==false){this.hovered.children("A").removeClass("hover ui-state-hover");this.hovered=obj;}
this.selected.children("a").removeClass("clicked ui-state-active").addClass(this.settings.ui.theme_name=="themeroller"?"clicked ui-state-active":"clicked").end().parents("li.closed").each(function(){_this.open_branch(this,true);});var off_t=this.selected.offset().top;var beg_t=this.container.offset().top;var end_t=beg_t+this.container.height();var h_cor=(this.container.get(0).scrollWidth>this.container.width())?40:20;if(off_t+5<beg_t)this.container.scrollTop(this.container.scrollTop()-(beg_t-off_t+5));if(off_t+h_cor>end_t)this.container.scrollTop(this.container.scrollTop()+(off_t+h_cor-end_t));this.set_cookie("selected");this.settings.callback.onselect.call(null,this.selected.get(0),_this);this.settings.callback.onchange.call(null,this.selected.get(0),_this);},deselect_branch:function(obj){if(this.locked)return this.error("LOCKED");var _this=this;var obj=this.get_node(obj);obj.children("a").removeClass("clicked ui-state-active");this.settings.callback.ondeselect.call(null,obj.get(0),_this);if(this.settings.rules.multiple!=false&&this.selected_arr.length>1){this.selected_arr=[];this.container.find("a.clicked").filter(":first-child").parent().each(function(){_this.selected_arr.push($(this));});if(obj.get(0)==this.selected.get(0)){this.selected=this.selected_arr[0];this.set_cookie("selected");}}
else{if(this.settings.rules.multiple!=false)this.selected_arr=[];this.selected=false;this.set_cookie("selected");}
if(this.selected)this.settings.callback.onchange.call(null,this.selected.get(0),_this);else this.settings.callback.onchange.call(null,false,_this);},toggle_branch:function(obj){if(this.locked)return this.error("LOCKED");var obj=this.get_node(obj);if(obj.hasClass("closed"))return this.open_branch(obj);if(obj.hasClass("open"))return this.close_branch(obj);},open_branch:function(obj,disable_animation,callback){if(this.locked)return this.error("LOCKED");var obj=this.get_node(obj);if(!obj.size())return this.error("OPEN: NO SUCH NODE");if(obj.hasClass("leaf"))return this.error("OPEN: OPENING LEAF NODE");if(this.settings.data.async&&obj.find("li").size()==0){if(this.settings.callback.beforeopen.call(null,obj.get(0),this)===false)return this.error("OPEN: STOPPED BY USER");var _this=this;obj.children("ul:eq(0)").remove().end().append("<ul><li class='last'><a class='loading' href='#'>"+(_this.settings.lang.loading||"Loading ...")+"</a></li></ul>");obj.removeClass("closed").addClass("open");if(this.settings.data.type=="xml_flat"||this.settings.data.type=="xml_nested"){var xsl=(this.settings.data.type=="xml_flat")?"flat.xsl":"nested.xsl";obj.children("ul:eq(0)").getTransform(this.path+xsl,this.settings.data.url,{params:{theme_path:_this.theme},meth:this.settings.data.method,dat:this.settings.data.async_data(obj,this),repl:true,callback:function(str,json){if(str.length<15){obj.removeClass("closed").removeClass("open").addClass("leaf").children("ul").remove();if(callback)callback.call();return;}
_this.open_branch.apply(_this,[obj]);if(callback)callback.call();},error:function(){obj.removeClass("open").addClass("closed").children("ul:eq(0)").remove();}});}
else{$.ajax({type:this.settings.data.method,url:this.settings.data.url,data:this.settings.data.async_data(obj,this),dataType:"json",success:function(data,textStatus){data=_this.settings.callback.onJSONdata.call(null,data,_this);if(!data||data.length==0){obj.removeClass("closed").removeClass("open").addClass("leaf").children("ul").remove();if(callback)callback.call();return;}
var str="";if(data.length){for(var i=0;i<data.length;i++){str+=_this.parseJSON(data[i]);}}
else str=_this.parseJSON(data);if(str.length>0){obj.children("ul:eq(0)").replaceWith("<ul>"+str+"</ul>");obj.find("li:last-child").addClass("last").end().find("li:has(ul)").not(".open").addClass("closed");obj.find("li").not(".open").not(".closed").addClass("leaf");_this.open_branch.apply(_this,[obj]);}
else obj.removeClass("closed").removeClass("open").addClass("leaf").children("ul").remove();if(callback)callback.call();},error:function(xhttp,textStatus,errorThrown){obj.removeClass("open").addClass("closed").children("ul:eq(0)").remove();_this.error(errorThrown+" "+textStatus);}});}
return true;}
else{if(!this.settings.data.async){if(this.settings.callback.beforeopen.call(null,obj.get(0),this)===false)return this.error("OPEN: STOPPED BY USER");}
if(this.settings.ui.theme_name=="themeroller")obj.find("a").not(".ui-state-default").addClass("ui-state-default");if(parseInt(this.settings.ui.animation)>0&&!disable_animation){obj.children("ul:eq(0)").css("display","none");obj.removeClass("closed").addClass("open");obj.children("ul:eq(0)").slideDown(parseInt(this.settings.ui.animation),function(){$(this).css("display","");if(callback)callback.call();});}else{obj.removeClass("closed").addClass("open");if(callback)callback.call();}
this.set_cookie("open");this.settings.callback.onopen.call(null,obj.get(0),this);return true;}},close_branch:function(obj,disable_animation){if(this.locked)return this.error("LOCKED");var _this=this;var obj=this.get_node(obj);if(!obj.size())return this.error("CLOSE: NO SUCH NODE");if(_this.settings.callback.beforeclose.call(null,obj.get(0),_this)===false)return this.error("CLOSE: STOPPED BY USER");if(parseInt(this.settings.ui.animation)>0&&!disable_animation&&obj.children("ul:eq(0)").size()==1){obj.children("ul:eq(0)").slideUp(parseInt(this.settings.ui.animation),function(){if(obj.hasClass("open"))obj.removeClass("open").addClass("closed");_this.set_cookie("open");$(this).css("display","");});}
else{if(obj.hasClass("open"))obj.removeClass("open").addClass("closed");this.set_cookie("open");}
if(this.selected&&obj.children("ul:eq(0)").find("a.clicked").size()>0){obj.find("li:has(a.clicked)").each(function(){_this.deselect_branch(this);});if(obj.children("a.clicked").size()==0)this.select_branch(obj,(this.settings.rules.multiple!=false&&this.selected_arr.length>0));}
this.settings.callback.onclose.call(null,obj.get(0),this);},open_all:function(obj,callback){if(this.locked)return this.error("LOCKED");var _this=this;obj=obj?this.get_node(obj).parent():this.container;var s=obj.find("li.closed").size();if(!callback)this.cl_count=0;else this.cl_count--;if(s>0){this.cl_count+=s;obj.find("li.closed").each(function(){var __this=this;_this.open_branch.apply(_this,[this,true,function(){_this.open_all.apply(_this,[__this,true]);}]);});}
else if(this.cl_count==0)this.settings.callback.onopen_all.call(null,this);},close_all:function(){if(this.locked)return this.error("LOCKED");var _this=this;this.container.find("li.open").each(function(){_this.close_branch(this,true);});},show_lang:function(i){if(this.locked)return this.error("LOCKED");if(this.settings.languages[i]==this.current_lang)return true;var st=false;var id=this.container.attr("id")?"#"+this.container.attr("id"):".tree";st=get_css(id+" ."+this.current_lang,this.sn);if(st!==false)st.style.display="none";st=get_css(id+" ."+this.settings.languages[i],this.sn);if(st!==false)st.style.display="";this.current_lang=this.settings.languages[i];return true;},cycle_lang:function(){if(this.locked)return this.error("LOCKED");var i=$.inArray(this.current_lang,this.settings.languages);i++;if(i>this.settings.languages.length-1)i=0;this.show_lang(i);},create:function(obj,ref_node,position){if(this.locked)return this.error("LOCKED");var root=false;if(ref_node==-1){root=true;ref_node=this.container;}
else ref_node=ref_node?this.get_node(ref_node):this.selected;if(!root&&(!ref_node||!ref_node.size()))return this.error("CREATE: NO NODE SELECTED");var pos=position;var tmp=ref_node;if(position=="before"){position=ref_node.parent().children().index(ref_node);ref_node=ref_node.parents("li:eq(0)");}
if(position=="after"){position=ref_node.parent().children().index(ref_node)+1;ref_node=ref_node.parents("li:eq(0)");}
if(!root&&ref_node.size()==0){root=true;ref_node=this.container;}
if(!root){if(!this.check("creatable",ref_node))return this.error("CREATE: CANNOT CREATE IN NODE");if(ref_node.hasClass("closed")){if(this.settings.data.async&&ref_node.children("ul").size()==0){var _this=this;return this.open_branch(ref_node,true,function(){_this.create.apply(_this,[obj,ref_node,position]);});}
else this.open_branch(ref_node,true);}}
var torename=false;if(!obj)obj={};else obj=$.extend(true,{},obj);if(!obj.attributes)obj.attributes={};if(this.settings.rules.metadata){if(!obj.attributes[this.settings.rules.metadata])obj.attributes[this.settings.rules.metadata]='{ "type" : "'+(this.get_type(tmp)||"")+'" }';}
else{if(!obj.attributes[this.settings.rules.type_attr])obj.attributes[this.settings.rules.type_attr]=this.get_type(tmp)||"";}
if(this.settings.languages.length){if(!obj.data){obj.data={};torename=true;}
for(var i=0;i<this.settings.languages.length;i++){if(!obj.data[this.settings.languages[i]])obj.data[this.settings.languages[i]]=((typeof this.settings.lang.new_node).toLowerCase()!="string"&&this.settings.lang.new_node[i])?this.settings.lang.new_node[i]:this.settings.lang.new_node;}}
else{if(!obj.data){obj.data=this.settings.lang.new_node;torename=true;}}
var $li=$(this.parseJSON(obj));if($li.children("ul").size()){if(!$li.is(".open"))$li.addClass("closed");}
else $li.addClass("leaf");$li.find("li:last-child").addClass("last").end().find("li:has(ul)").not(".open").addClass("closed");$li.find("li").not(".open").not(".closed").addClass("leaf");if(!root&&this.settings.rules.use_inline&&this.settings.rules.metadata){var t=this.get_type($li)||"";$.metadata.setType("attr",this.settings.rules.metadata);if(typeof ref_node.metadata()["valid_children"]!="undefined"){if($.inArray(t,ref_node.metadata()["valid_children"])==-1)return this.error("CREATE: NODE NOT A VALID CHILD");}
if(typeof ref_node.metadata()["max_children"]!="undefined"){if((ref_node.children("ul:eq(0)").children("li").size()+1)>ref_node.metadata().max_children)return this.error("CREATE: MAX_CHILDREN REACHED");}
var ok=true;if((typeof $(ref_node).metadata().max_depth).toLowerCase()!="undefined"&&$(ref_node).metadata().max_depth===0)ok=false;else{ref_node.parents("li").each(function(i){if($(this).metadata().max_depth){if((i+1)>=$(this).metadata().max_depth){ok=false;return false;}}});}
if(!ok)return this.error("CREATE: MAX_DEPTH REACHED");}
if((typeof position).toLowerCase()=="undefined"||position=="inside")
position=(this.settings.rules.createat=="top")?0:ref_node.children("ul:eq(0)").children("li").size();if(ref_node.children("ul").size()==0||(root==true&&ref_node.children("ul").children("li").size()==0)){if(!root)var a=this.moved($li,ref_node.children("a:eq(0)"),"inside",true);else var a=this.moved($li,this.container.children("ul:eq(0)"),"inside",true);}
else if(pos=="before"&&ref_node.children("ul:eq(0)").children("li:nth-child("+(position+1)+")").size())
var a=this.moved($li,ref_node.children("ul:eq(0)").children("li:nth-child("+(position+1)+")").children("a:eq(0)"),"before",true);else if(pos=="after"&&ref_node.children("ul:eq(0)").children("li:nth-child("+(position)+")").size())
var a=this.moved($li,ref_node.children("ul:eq(0)").children("li:nth-child("+(position)+")").children("a:eq(0)"),"after",true);else if(ref_node.children("ul:eq(0)").children("li:nth-child("+(position+1)+")").size())
var a=this.moved($li,ref_node.children("ul:eq(0)").children("li:nth-child("+(position+1)+")").children("a:eq(0)"),"before",true);else
var a=this.moved($li,ref_node.children("ul:eq(0)").children("li:last").children("a:eq(0)"),"after",true);if(a===false)return this.error("CREATE: ABORTED");if(torename){this.select_branch($li.children("a:eq(0)"));this.rename();}
return $li;},rename:function(obj){if(this.locked)return this.error("LOCKED");obj=obj?this.get_node(obj):this.selected;var _this=this;if(!obj||!obj.size())return this.error("RENAME: NO NODE SELECTED");if(!this.check("renameable",obj))return this.error("RENAME: NODE NOT RENAMABLE");if(!this.settings.callback.beforerename.call(null,obj.get(0),_this.current_lang,_this))return this.error("RENAME: STOPPED BY USER");obj.parents("li.closed").each(function(){_this.open_branch(this)});if(this.current_lang)obj=obj.find("a."+this.current_lang).get(0);else obj=obj.find("a:first").get(0);last_value=obj.innerHTML;_this.inp=$("<input type='text' autocomplete='off' />");_this.inp.val(last_value.replace(/&/g,"&").replace(/>/g,">").replace(/</g,"<")).bind("mousedown",function(event){event.stopPropagation();}).bind("mouseup",function(event){event.stopPropagation();}).bind("click",function(event){event.stopPropagation();}).bind("keyup",function(event){var key=event.keyCode||event.which;if(key==27){this.value=last_value;this.blur();return}
if(key==13){this.blur();return}});var rb={};rb[this.container.attr("id")]=this.get_rollback();_this.inp.blur(function(event){if(this.value=="")this.value=last_value;$(obj).text($(obj).parent().find("input").eq(0).attr("value")).get(0).style.display="";$(obj).prevAll("span").remove();_this.settings.callback.onrename.call(null,_this.get_node(obj).get(0),_this.current_lang,_this,rb);_this.inp=false;});var spn=$("<span />").addClass(obj.className).append(_this.inp);spn.attr("style",$(obj).attr("style"));obj.style.display="none";$(obj).parent().prepend(spn);_this.inp.get(0).focus();_this.inp.get(0).select();},remove:function(obj){if(this.locked)return this.error("LOCKED");var rb={};rb[this.container.attr("id")]=this.get_rollback();if(obj&&(!this.selected||this.get_node(obj).get(0)!=this.selected.get(0))){obj=this.get_node(obj);if(obj.size()){if(!this.check("deletable",obj))return this.error("DELETE: NODE NOT DELETABLE");if(!this.settings.callback.beforedelete.call(null,obj.get(0),_this))return this.error("DELETE: STOPPED BY USER");$parent=obj.parent();obj=obj.remove();$parent.children("li:last").addClass("last");if($parent.children("li").size()==0){$li=$parent.parents("li:eq(0)");$li.removeClass("open").removeClass("closed").addClass("leaf").children("ul").remove();this.set_cookie("open");}
this.settings.callback.ondelete.call(null,obj.get(0),this,rb);}}
else if(this.selected){if(!this.check("deletable",this.selected))return this.error("DELETE: NODE NOT DELETABLE");if(!this.settings.callback.beforedelete.call(null,this.selected.get(0),_this))return this.error("DELETE: STOPPED BY USER");$parent=this.selected.parent();var obj=this.selected;if(this.settings.rules.multiple==false||this.selected_arr.length==1){var stop=true;var tmp=(this.selected.prev("li:eq(0)").size())?this.selected.prev("li:eq(0)"):this.selected.parents("li:eq(0)");}
obj=obj.remove();$parent.children("li:last").addClass("last");if($parent.children("li").size()==0){$li=$parent.parents("li:eq(0)");$li.removeClass("open").removeClass("closed").addClass("leaf").children("ul").remove();this.set_cookie("open");}
this.settings.callback.ondelete.call(null,obj.get(0),this,rb);if(stop&&tmp)this.select_branch(tmp);if(this.settings.rules.multiple!=false&&!stop){var _this=this;this.selected_arr=[];this.container.find("a.clicked").filter(":first-child").parent().each(function(){_this.selected_arr.push($(this));});if(this.selected_arr.length>0){this.selected=this.selected_arr[0];this.remove();}}}
else return this.error("DELETE: NO NODE SELECTED");},next:function(obj,strict){obj=this.get_node(obj);if(!obj.size())return false;if(strict)return(obj.nextAll("li").size()>0)?obj.nextAll("li:eq(0)"):false;if(obj.hasClass("open"))return obj.find("li:eq(0)");else if(obj.nextAll("li").size()>0)return obj.nextAll("li:eq(0)");else return obj.parents("li").next("li").eq(0);},prev:function(obj,strict){obj=this.get_node(obj);if(!obj.size())return false;if(strict)return(obj.prevAll("li").size()>0)?obj.prevAll("li:eq(0)"):false;if(obj.prev("li").size()){var obj=obj.prev("li").eq(0);while(obj.hasClass("open"))obj=obj.children("ul:eq(0)").children("li:last");return obj;}
else return obj.parents("li:eq(0)").size()?obj.parents("li:eq(0)"):false;},parent:function(obj){obj=this.get_node(obj);if(!obj.size())return false;return obj.parents("li:eq(0)").size()?obj.parents("li:eq(0)"):false;},children:function(obj){obj=this.get_node(obj);if(!obj.size())return false;return obj.children("ul:eq(0)").children("li");},get_next:function(force){var obj=this.hovered||this.selected;return force?this.select_branch(this.next(obj)):this.hover_branch(this.next(obj));},get_prev:function(force){var obj=this.hovered||this.selected;return force?this.select_branch(this.prev(obj)):this.hover_branch(this.prev(obj));},get_left:function(force,rtl){if(this.settings.ui.rtl&&!rtl)return this.get_right(force,true);var obj=this.hovered||this.selected;if(obj){if(obj.hasClass("open"))this.close_branch(obj);else{return force?this.select_branch(this.parent(obj)):this.hover_branch(this.parent(obj));}}},get_right:function(force,rtl){if(this.settings.ui.rtl&&!rtl)return this.get_left(force,true);var obj=this.hovered||this.selected;if(obj){if(obj.hasClass("closed"))this.open_branch(obj);else{return force?this.select_branch(obj.find("li:eq(0)")):this.hover_branch(obj.find("li:eq(0)"));}}},toggleDots:function(){if(this.settings.ui.dots){this.settings.ui.dots=false;this.container.children("ul:eq(0)").addClass("no_dots");}
else{this.settings.ui.dots=true;this.container.children("ul:eq(0)").removeClass("no_dots");}},toggleRTL:function(){if(this.settings.ui.rtl){this.settings.ui.rtl=false;this.container.css("direction","ltr").children("ul:eq(0)").removeClass("rtl").addClass("ltr");}
else{this.settings.ui.rtl=true;this.container.css("direction","rtl").children("ul:eq(0)").removeClass("ltr").addClass("rtl");}},set_cookie:function(type){if(this.settings.cookies===false)return false;if(this.settings.cookies[type]===false)return false;switch(type){case"selected":if(this.settings.rules.multiple!=false&&this.selected_arr.length>1){var val=Array();$.each(this.selected_arr,function(){if(this.attr("id")){val.push(this.attr("id"));}});val=val.join(",");}
else var val=this.selected?this.selected.attr("id"):false;$.cookie(this.settings.cookies.prefix+'_selected',val,this.settings.cookies.opts);break;case"open":var str="";this.container.find("li.open").each(function(i){if(this.id){str+=this.id+",";}});$.cookie(this.settings.cookies.prefix+'_open',str.replace(/,$/ig,""),this.settings.cookies.opts);break;}},get_rollback:function(){var rb={};if(this.context.to_remove&&this.context.apply_to)this.context.apply_to.children("a").removeClass("clicked");rb.html=this.container.html();if(this.context.to_remove&&this.context.apply_to)this.context.apply_to.children("a").addClass("clicked");rb.selected=this.selected?this.selected.attr("id"):false;return rb;},moved:function(what,where,how,is_new,is_copy,rb){var what=$(what);var $parent=$(what).parents("ul:eq(0)");var $where=$(where);if(!rb){var rb={};rb[this.container.attr("id")]=this.get_rollback();if(!is_new){var tmp=what.size()>1?what.eq(0).parents(".tree:eq(0)"):what.parents(".tree:eq(0)");if(tmp.get(0)!=this.container.get(0)){tmp=tree_component.inst[tmp.attr("id")];rb[tmp.container.attr("id")]=tmp.get_rollback();}
delete tmp;}}
if(how=="inside"&&this.settings.data.async&&this.get_node($where).hasClass("closed")){var _this=this;return this.open_branch(this.get_node($where),true,function(){_this.moved.apply(_this,[what,where,how,is_new,is_copy,rb]);});}
if(what.size()>1){var _this=this;var tmp=this.moved(what.eq(0),where,how,false,is_copy,rb);what.each(function(i){if(i==0)return;if(tmp){tmp=_this.moved(this,tmp.children("a:eq(0)"),"after",false,is_copy,rb);}});return;}
if(is_copy){_what=what.clone();_what.each(function(i){this.id=this.id+"_copy";$(this).find("li").each(function(){this.id=this.id+"_copy";});$(this).removeClass("dragged").find("a.clicked").removeClass("clicked ui-state-active").end().find("li.dragged").removeClass("dragged");});}
else _what=what;if(is_new){if(!this.settings.callback.beforecreate.call(null,this.get_node(what).get(0),this.get_node(where).get(0),how,this))return false;}
else{if(!this.settings.callback.beforemove.call(null,this.get_node(what).get(0),this.get_node(where).get(0),how,this))return false;}
if(!is_new){var tmp=what.parents(".tree:eq(0)");if(tmp.get(0)!=this.container.get(0)){tmp=tree_component.inst[tmp.attr("id")];if(tmp.settings.languages.length){var res=[];if(this.settings.languages.length==0)res.push("."+tmp.current_lang);else{for(var i in this.settings.languages){if(typeof this.settings.languages[i]=="function")continue;for(var j in tmp.settings.languages){if(typeof tmp.settings.languages[j]=="function")continue;if(this.settings.languages[i]==tmp.settings.languages[j])res.push("."+this.settings.languages[i]);}}}
if(res.length==0)return this.error("MOVE: NO COMMON LANGUAGES");what.find("a").not(res.join(",")).remove();}
what.find("a.clicked").removeClass("clicked ui-state-active");}}
what=_what;switch(how){case"before":$where.parents("ul:eq(0)").children("li.last").removeClass("last");$where.parent().before(what.removeClass("last"));$where.parents("ul:eq(0)").children("li:last").addClass("last");break;case"after":$where.parents("ul:eq(0)").children("li.last").removeClass("last");$where.parent().after(what.removeClass("last"));$where.parents("ul:eq(0)").children("li:last").addClass("last");break;case"inside":if($where.parent().children("ul:first").size()){if(this.settings.rules.createat=="top")$where.parent().children("ul:first").prepend(what.removeClass("last")).children("li:last").addClass("last");else $where.parent().children("ul:first").children(".last").removeClass("last").end().append(what.removeClass("last")).children("li:last").addClass("last");}
else{what.addClass("last");$where.parent().append("<ul/>").removeClass("leaf").addClass("closed");$where.parent().children("ul:first").prepend(what);}
if($where.parent().hasClass("closed")){this.open_branch($where);}
break;default:break;}
if($parent.find("li").size()==0){var $li=$parent.parent();$li.removeClass("open").removeClass("closed").addClass("leaf");if(!$li.is(".tree"))$li.children("ul").remove();$li.parents("ul:eq(0)").children("li.last").removeClass("last").end().children("li:last").addClass("last");this.set_cookie("open");}
else{$parent.children("li.last").removeClass("last");$parent.children("li:last").addClass("last");}
if(is_copy)this.settings.callback.oncopy.call(null,this.get_node(what).get(0),this.get_node(where).get(0),how,this,rb);else if(is_new)this.settings.callback.oncreate.call(null,this.get_node(what).get(0),($where.is("ul")?-1:this.get_node(where).get(0)),how,this,rb);else this.settings.callback.onmove.call(null,this.get_node(what).get(0),this.get_node(where).get(0),how,this,rb);return what;},error:function(code){this.settings.callback.error.call(null,code,this);return false;},lock:function(state){this.locked=state;if(this.locked)this.container.children("ul:eq(0)").addClass("locked");else this.container.children("ul:eq(0)").removeClass("locked");},cut:function(obj){if(this.locked)return this.error("LOCKED");obj=obj?this.get_node(obj):this.container.find("a.clicked").filter(":first-child").parent();if(!obj||!obj.size())return this.error("CUT: NO NODE SELECTED");this.copy_nodes=false;this.cut_nodes=obj;},copy:function(obj){if(this.locked)return this.error("LOCKED");obj=obj?this.get_node(obj):this.container.find("a.clicked").filter(":first-child").parent();if(!obj||!obj.size())return this.error("COPY: NO NODE SELECTED");this.copy_nodes=obj;this.cut_nodes=false;},paste:function(obj,position){if(this.locked)return this.error("LOCKED");var root=false;if(obj==-1){root=true;obj=this.container;}
else obj=obj?this.get_node(obj):this.selected;if(!root&&(!obj||!obj.size()))return this.error("PASTE: NO NODE SELECTED");if(!this.copy_nodes&&!this.cut_nodes)return this.error("PASTE: NOTHING TO DO");var _this=this;var pos=position;if(position=="before"){position=obj.parent().children().index(obj);obj=obj.parents("li:eq(0)");}
else if(position=="after"){position=obj.parent().children().index(obj)+1;obj=obj.parents("li:eq(0)");}
else if((typeof position).toLowerCase()=="undefined"||position=="inside"){position=(this.settings.rules.createat=="top")?0:obj.children("ul:eq(0)").children("li").size();}
if(!root&&obj.size()==0){root=true;obj=this.container;}
if(this.copy_nodes&&this.copy_nodes.size()){var ok=true;if(!ok)return this.error("Invalid paste");if(!root&&!this.checkMove(this.copy_nodes,obj.children("a:eq(0)"),"inside"))return false;if(obj.children("ul").size()==0||(root==true&&obj.children("ul").children("li").size()==0)){if(!root)var a=this.moved(this.copy_nodes,obj.children("a:eq(0)"),"inside",false,true);else var a=this.moved(this.copy_nodes,this.container.children("ul:eq(0)"),"inside",false,true);}
else if(pos=="before"&&obj.children("ul:eq(0)").children("li:nth-child("+(position+1)+")").size())
var a=this.moved(this.copy_nodes,obj.children("ul:eq(0)").children("li:nth-child("+(position+1)+")").children("a:eq(0)"),"before",false,true);else if(pos=="after"&&obj.children("ul:eq(0)").children("li:nth-child("+(position)+")").size())
var a=this.moved(this.copy_nodes,obj.children("ul:eq(0)").children("li:nth-child("+(position)+")").children("a:eq(0)"),"after",false,true);else if(obj.children("ul:eq(0)").children("li:nth-child("+(position+1)+")").size())
var a=this.moved(this.copy_nodes,obj.children("ul:eq(0)").children("li:nth-child("+(position+1)+")").children("a:eq(0)"),"before",false,true);else
var a=this.moved(this.copy_nodes,obj.children("ul:eq(0)").children("li:last").children("a:eq(0)"),"after",false,true);this.copy_nodes=false;}
if(this.cut_nodes&&this.cut_nodes.size()){var ok=true;obj.parents().andSelf().each(function(){if(_this.cut_nodes.index(this)!=-1){ok=false;return false;}});if(!ok)return this.error("Invalid paste");if(!root&&!this.checkMove(this.cut_nodes,obj.children("a:eq(0)"),"inside"))return false;if(obj.children("ul").size()==0||(root==true&&obj.children("ul").children("li").size()==0)){if(!root)var a=this.moved(this.cut_nodes,obj.children("a:eq(0)"),"inside");else var a=this.moved(this.cut_nodes,this.container.children("ul:eq(0)"),"inside");}
else if(pos=="before"&&obj.children("ul:eq(0)").children("li:nth-child("+(position+1)+")").size())
var a=this.moved(this.cut_nodes,obj.children("ul:eq(0)").children("li:nth-child("+(position+1)+")").children("a:eq(0)"),"before");else if(pos=="after"&&obj.children("ul:eq(0)").children("li:nth-child("+(position)+")").size())
var a=this.moved(this.cut_nodes,obj.children("ul:eq(0)").children("li:nth-child("+(position)+")").children("a:eq(0)"),"after");else if(obj.children("ul:eq(0)").children("li:nth-child("+(position+1)+")").size())
var a=this.moved(this.cut_nodes,obj.children("ul:eq(0)").children("li:nth-child("+(position+1)+")").children("a:eq(0)"),"before");else
var a=this.moved(this.cut_nodes,obj.children("ul:eq(0)").children("li:last").children("a:eq(0)"),"after");this.cut_nodes=false;}},search:function(str){var _this=this;if(!str||(this.srch&&str!=this.srch)){this.srch="";this.srch_opn=false;this.container.find("a.search").removeClass("search ui-state-highlight");}
this.srch=str;if(!str)return;if(this.settings.data.async){if(!this.srch_opn){var dd=$.extend({"search":str},this.settings.data.async_data(false,this));$.ajax({type:this.settings.data.method,url:this.settings.data.url,data:dd,dataType:"text",success:function(data){_this.srch_opn=$.unique(data.split(","));_this.search.apply(_this,[str]);}});}
else if(this.srch_opn.length){if(this.srch_opn&&this.srch_opn.length){var opn=false;for(var j=0;j<this.srch_opn.length;j++){if(this.get_node("#"+this.srch_opn[j]).size()>0){opn=true;var tmp="#"+this.srch_opn[j];delete this.srch_opn[j];this.open_branch(tmp,true,function(){_this.search.apply(_this,[str]);});}}
if(!opn){this.srch_opn=[];_this.search.apply(_this,[str]);}}}
else{var selector="a";if(this.settings.languages.length)selector+="."+this.current_lang;this.container.find(selector+":contains('"+str+"')").addClass(this.settings.ui.theme_name=="themeroller"?"search ui-state-highlight":"search");this.srch_opn=false;}}
else{var selector="a";if(this.settings.languages.length)selector+="."+this.current_lang;this.container.find(selector+":contains('"+str+"')").addClass(this.settings.ui.theme_name=="themeroller"?"search ui-state-highlight":"search").parents("li.closed").each(function(){_this.open_branch(this,true);});}},destroy:function(){this.hide_context();this.container.unbind(".jstree");$("#"+this.container.attr("id")).die("click.jstree").die("dblclick.jstree").die("contextmenu.jstree").die("mouseover.jstree").die("mouseout.jstree").die("mousedown.jstree");this.container.removeClass("tree ui-widget ui-widget-content tree-default tree-"+this.settings.ui.theme_name).children("ul").removeClass("no_dots rtl ltr locked").find("li").removeClass("leaf").removeClass("open").removeClass("closed").removeClass("last").children("a").removeClass("clicked hover search ui-state-active ui-state-hover ui-state-highlight ui-state-default");if(this.cntr==tree_component.focused){for(var i in tree_component.inst){if(i!=this.cntr&&i!=this.container.attr("id")){tree_component.inst[i].focus();break;}}}
tree_component.inst[this.cntr]=false;tree_component.inst[this.container.attr("id")]=false;delete tree_component.inst[this.cntr];delete tree_component.inst[this.container.attr("id")];tree_component.cntr--;}}};})(jQuery);function get_css(rule_name,stylesheet,delete_flag){if(!document.styleSheets)return false;rule_name=rule_name.toLowerCase();stylesheet=stylesheet||0;for(var i=stylesheet;i<document.styleSheets.length;i++){var styleSheet=document.styleSheets[i];css_rules=document.styleSheets[i].cssRules||document.styleSheets[i].rules;if(!css_rules)continue;var j=0;do{if(css_rules.length&&j>css_rules.length+5)return false;if(css_rules[j].selectorText&&css_rules[j].selectorText.toLowerCase()==rule_name){if(delete_flag==true){if(document.styleSheets[i].removeRule)document.styleSheets[i].removeRule(j);if(document.styleSheets[i].deleteRule)document.styleSheets[i].deleteRule(j);return true;}
else return css_rules[j];}}
while(css_rules[++j]);}
return false;}
function add_css(rule_name,stylesheet){rule_name=rule_name.toLowerCase();stylesheet=stylesheet||0;if(!document.styleSheets||get_css(rule_name,stylesheet))return false;(document.styleSheets[stylesheet].insertRule)?document.styleSheets[stylesheet].insertRule(rule_name+' { }',0):document.styleSheets[stylesheet].addRule(rule_name,null,0);return get_css(rule_name,stylesheet);}
function get_sheet_num(href_name){if(!document.styleSheets)return false;for(var i=0;i<document.styleSheets.length;i++){if(document.styleSheets[i].href&&document.styleSheets[i].href.toString().match(href_name))return i;}
return false;}
function remove_css(rule_name,stylesheet){return get_css(rule_name,stylesheet,true);}
function add_sheet(url,media){if(document.createStyleSheet){document.createStyleSheet(url);}
else{var newSS=document.createElement('link');newSS.rel='stylesheet';newSS.type='text/css';newSS.media=media||"all";newSS.href=url;document.getElementsByTagName("head")[0].appendChild(newSS);}}
(function($)
{var menus=[],visibleMenus=[],activeMenu=activeItem=null,menuDIVElement=$('<div class="menu-div outerbox" style="position:absolute;top:0;left:0;display:none;"><div class="shadowbox1"></div><div class="shadowbox2"></div><div class="shadowbox3"></div></div>')[0],menuULElement=$('<ul class="menu-ul innerbox"></ul>')[0],menuItemElement=$('<li style="position:relative;"><div class="menu-item"></div></li>')[0],arrowElement=$('<img class="menu-item-arrow" />')[0],$rootDiv=$('<div id="root-menu-div" style="position:absolute;top:0;left:0;"></div>'),defaults={showDelay:200,hideDelay:200,hoverOpenDelay:0,offsetTop:0,offsetLeft:0,minWidth:0,onOpen:null,onClose:null,onClick:null,arrowSrc:null,addExpando:false,copyClassAttr:false};$(function(){$rootDiv.appendTo('body');});$.extend({MenuCollection:function(items){this.menus=[];this.init(items);}});$.extend($.MenuCollection,{prototype:{init:function(items)
{if(items&&items.length)
{for(var i=0;i<items.length;i++)
{this.addMenu(items[i]);items[i].menuCollection=this;}}},addMenu:function(menu)
{if(menu instanceof $.Menu)
this.menus.push(menu);menu.menuCollection=this;var self=this;$(menu.target).hover(function(){if(menu.visible)
return;for(var i=0;i<self.menus.length;i++)
{if(self.menus[i].visible)
{self.menus[i].hide();menu.show();return;}}},function(){});}}});$.extend({Menu:function(target,items,options){this.menuItems=[];this.subMenus=[];this.visible=false;this.active=false;this.parentMenuItem=null;this.settings=$.extend({},defaults,options);this.target=target;this.$eDIV=null;this.$eUL=null;this.timer=null;this.menuCollection=null;this.openTimer=null;this.init();if(items&&items.constructor==Array)
this.addItems(items);}});$.extend($.Menu,{checkMouse:function(e)
{var t=e.target;if(visibleMenus.length&&t==visibleMenus[0].target)
return;while(t.parentNode&&t.parentNode!=$rootDiv[0])
t=t.parentNode;if(!$(visibleMenus).filter(function(){return this.$eDIV[0]==t}).length)
{$.Menu.closeAll();}},checkKey:function(e)
{switch(e.keyCode)
{case 13:if(activeItem)
activeItem.click(e,activeItem.$eLI[0]);break;case 27:$.Menu.closeAll();break;case 37:if(!activeMenu)
activeMenu=visibleMenus[0];var a=activeMenu;if(a&&a.parentMenuItem)
{var pmi=a.parentMenuItem;pmi.$eLI.unbind('mouseout').unbind('mouseover');a.hide();pmi.hoverIn(true);setTimeout(function(){pmi.bindHover();});}
else if(a&&a.menuCollection)
{var pos,mcm=a.menuCollection.menus;if((pos=$.inArray(a,mcm))>-1)
{if(--pos<0)
pos=mcm.length-1;$.Menu.closeAll();mcm[pos].show();mcm[pos].setActive();if(mcm[pos].menuItems.length)
mcm[pos].menuItems[0].hoverIn(true);}}
break;case 38:if(activeMenu)
activeMenu.selectNextItem(-1);break;case 39:if(!activeMenu)
activeMenu=visibleMenus[0];var m,a=activeMenu,asm=activeItem?activeItem.subMenu:null;if(a)
{if(asm&&asm.menuItems.length)
{asm.show();asm.menuItems[0].hoverIn();}
else if((a=a.inMenuCollection()))
{var pos,mcm=a.menuCollection.menus;if((pos=$.inArray(a,mcm))>-1)
{if(++pos>=mcm.length)
pos=0;$.Menu.closeAll();mcm[pos].show();mcm[pos].setActive();if(mcm[pos].menuItems.length)
mcm[pos].menuItems[0].hoverIn(true);}}}
break;case 40:if(!activeMenu)
{if(visibleMenus.length&&visibleMenus[0].menuItems.length)
visibleMenus[0].menuItems[0].hoverIn();}
else
activeMenu.selectNextItem();break;}
if(e.keyCode>36&&e.keyCode<41)
return false;},closeAll:function()
{while(visibleMenus.length)
visibleMenus[0].hide();},setDefaults:function(d)
{$.extend(defaults,d);},prototype:{init:function()
{var self=this;if(!this.target)
return;else if(this.target instanceof $.MenuItem)
{this.parentMenuItem=this.target;this.target.addSubMenu(this);this.target=this.target.$eLI;}
menus.push(this);this.$eDIV=$(menuDIVElement.cloneNode(1));this.$eUL=$(menuULElement.cloneNode(1));this.$eDIV[0].appendChild(this.$eUL[0]);$rootDiv[0].appendChild(this.$eDIV[0]);if(!this.parentMenuItem)
{$(this.target).click(function(e){self.onClick(e);}).hover(function(e){self.setActive();if(self.settings.hoverOpenDelay)
{self.openTimer=setTimeout(function(){if(!self.visible)
self.onClick(e);},self.settings.hoverOpenDelay);}},function(){if(!self.visible)
$(this).removeClass('activetarget');if(self.openTimer)
clearTimeout(self.openTimer);});}
else
{this.$eDIV.hover(function(){self.setActive();},function(){});}},setActive:function()
{if(!this.parentMenuItem)
$(this.target).addClass('activetarget');else
this.active=true;},addItem:function(item)
{if(item instanceof $.MenuItem)
{if($.inArray(item,this.menuItems)==-1)
{this.$eUL.append(item.$eLI);this.menuItems.push(item);item.parentMenu=this;if(item.subMenu)
this.subMenus.push(item.subMenu);}}
else
{this.addItem(new $.MenuItem(item,this.settings));}},addItems:function(items)
{for(var i=0;i<items.length;i++)
{this.addItem(items[i]);}},removeItem:function(item)
{var pos=$.inArray(item,this.menuItems);if(pos>-1)
this.menuItems.splice(pos,1);item.parentMenu=null;},hide:function()
{if(!this.visible)
return;var i,pos=$.inArray(this,visibleMenus);this.$eDIV.hide();if(pos>=0)
visibleMenus.splice(pos,1);this.visible=this.active=false;$(this.target).removeClass('activetarget');for(i=0;i<this.subMenus.length;i++)
{this.subMenus[i].hide();}
for(i=0;i<this.menuItems.length;i++)
{if(this.menuItems[i].active)
this.menuItems[i].setInactive();}
if(!visibleMenus.length)
$(document).unbind('mousedown',$.Menu.checkMouse).unbind('keydown',$.Menu.checkKey);if(activeMenu==this)
activeMenu=null;if(this.settings.onClose)
this.settings.onClose.call(this);},show:function(e)
{if(this.visible)
return;var zi,pmi=this.parentMenuItem;if(this.menuItems.length)
{if(pmi)
{zi=parseInt(pmi.parentMenu.$eDIV.css('z-index'));this.$eDIV.css('z-index',(isNaN(zi)?1:zi+1));}
this.$eDIV.css({visibility:'hidden',display:'block'});if(this.settings.minWidth)
{if(this.$eDIV.width()<this.settings.minWidth)
this.$eDIV.css('width',this.settings.minWidth);}
this.setPosition();this.$eDIV.css({display:'none',visibility:''}).show();if($.browser.msie)
this.$eUL.css('width',parseInt($.browser.version)==6?this.$eDIV.width()-7:this.$eUL.width());if(this.settings.onOpen)
this.settings.onOpen.call(this);}
if(visibleMenus.length==0)
$(document).bind('mousedown',$.Menu.checkMouse).bind('keydown',$.Menu.checkKey);this.visible=true;visibleMenus.push(this);},setPosition:function()
{var $t,o,posX,posY,pmo,wst,wsl,ww=$(window).width(),wh=$(window).height(),pmi=this.parentMenuItem,height=this.$eDIV[0].clientHeight,width=this.$eDIV[0].clientWidth,pheight;if(pmi)
{o=pmi.$eLI.offset();posX=o.left+pmi.$eLI.width();posY=o.top;}
else
{$t=$(this.target);o=$t.offset();posX=o.left+this.settings.offsetLeft;posY=o.top+$t.height()+this.settings.offsetTop;}
if($.fn.scrollTop)
{wst=$(window).scrollTop();if(wh<height)
{posY=wst;}
else if(wh+wst<posY+height)
{if(pmi)
{pmo=pmi.parentMenu.$eDIV.offset();pheight=pmi.parentMenu.$eDIV[0].clientHeight;if(height<=pheight)
{posY=pmo.top+pheight-height;}
else
{posY=pmo.top;}
if(wh+wst<posY+height)
{posY-=posY+height-(wh+wst);}}
else
{posY-=posY+height-(wh+wst);}}}
if($.fn.scrollLeft)
{wsl=$(window).scrollLeft();if(ww+wsl<posX+width)
{if(pmi)
{posX-=pmi.$eLI.width()+width;if(posX<wsl)
posX=wsl;}
else
{posX-=posX+width-(ww+wsl);}}}
this.$eDIV.css({left:posX,top:posY});},onClick:function(e)
{if(this.visible)
{this.hide();this.setActive();}
else
{$.Menu.closeAll();this.show(e);}},addTimer:function(callback,delay)
{var self=this;this.timer=setTimeout(function(){callback.call(self);self.timer=null;},delay);},removeTimer:function()
{if(this.timer)
{clearTimeout(this.timer);this.timer=null;}},selectNextItem:function(offset)
{var i,pos=0,mil=this.menuItems.length,o=offset||1;for(i=0;i<mil;i++)
{if(this.menuItems[i].active)
{pos=i;break;}}
this.menuItems[pos].hoverOut();do
{pos+=o;if(pos>=mil)
pos=0;else if(pos<0)
pos=mil-1;}while(this.menuItems[pos].separator);this.menuItems[pos].hoverIn(true);},inMenuCollection:function()
{var m=this;while(m.parentMenuItem)
m=m.parentMenuItem.parentMenu;return m.menuCollection?m:null;},destroy:function()
{var pos,item;this.hide();if(!this.parentMenuItem)
$(this.target).unbind('click').unbind('mouseover').unbind('mouseout');else
this.$eDIV.unbind('mouseover').unbind('mouseout');while(this.menuItems.length)
{item=this.menuItems[0];item.destroy();delete item;}
if((pos=$.inArray(this,menus))>-1)
menus.splice(pos,1);if(this.menuCollection)
{if((pos=$.inArray(this,this.menuCollection.menus))>-1)
this.menuCollection.menus.splice(pos,1);}
this.$eDIV.remove();}}});$.extend({MenuItem:function(obj,options)
{if(typeof obj=='string')
obj={src:obj};this.src=obj.src||'';this.url=obj.url||null;this.urlTarget=obj.target||null;this.addClass=obj.addClass||null;this.data=obj.data||null;this.$eLI=null;this.parentMenu=null;this.subMenu=null;this.settings=$.extend({},defaults,options);this.active=false;this.enabled=true;this.separator=false;this.init();if(obj.subMenu)
new $.Menu(this,obj.subMenu,options);}});$.extend($.MenuItem,{prototype:{init:function()
{var i,isStr,src=this.src,self=this;this.$eLI=$(menuItemElement.cloneNode(1));if(this.addClass)
this.$eLI[0].setAttribute('class',this.addClass);if(this.settings.addExpando&&this.data)
this.$eLI[0].menuData=this.data;if(src=='')
{this.$eLI.addClass('menu-separator');this.separator=true;}
else
{isStr=typeof src=='string';if(isStr&&this.url)
src=$('<a href="'+this.url+'"'+(this.urlTarget?'target="'+this.urlTarget+'"':'')+'>'+src+'</a>');else if(isStr||!src.length)
src=[src];for(i=0;i<src.length;i++)
{if(typeof src[i]=='string')
{elem=document.createElement('span');elem.innerHTML=src[i];this.$eLI[0].firstChild.appendChild(elem);}
else
this.$eLI[0].firstChild.appendChild(src[i].cloneNode(1));}}
this.$eLI.click(function(e){self.click(e,this);});this.bindHover();},click:function(e,scope)
{if(this.enabled&&this.settings.onClick)
this.settings.onClick.call(scope,e,this);},bindHover:function()
{var self=this;this.$eLI.hover(function(){self.hoverIn();},function(){self.hoverOut();});},hoverIn:function(noSubMenu)
{this.removeTimer();var i,pms=this.parentMenu.subMenus,pmi=this.parentMenu.menuItems,self=this;if(this.parentMenu.timer)
this.parentMenu.removeTimer();if(!this.enabled)
return;for(i=0;i<pmi.length;i++)
{if(pmi[i].active)
pmi[i].setInactive();}
this.setActive();activeMenu=this.parentMenu;for(i=0;i<pms.length;i++)
{if(pms[i].visible&&pms[i]!=this.subMenu&&!pms[i].timer)
pms[i].addTimer(function(){this.hide();},pms[i].settings.hideDelay);}
if(this.subMenu&&!noSubMenu)
{this.subMenu.addTimer(function(){this.show();},this.subMenu.settings.showDelay);}},hoverOut:function()
{this.removeTimer();if(!this.enabled)
return;if(!this.subMenu||!this.subMenu.visible)
this.setInactive();},removeTimer:function()
{if(this.subMenu)
{this.subMenu.removeTimer();}},setActive:function()
{this.active=true;this.$eLI.addClass('active');var pmi=this.parentMenu.parentMenuItem;if(pmi&&!pmi.active)
pmi.setActive();activeItem=this;},setInactive:function()
{this.active=false;this.$eLI.removeClass('active');if(this==activeItem)
activeItem=null;},enable:function()
{this.$eLI.removeClass('disabled');this.enabled=true;},disable:function()
{this.$eLI.addClass('disabled');this.enabled=false;},destroy:function()
{this.removeTimer();this.$eLI.remove();this.$eLI.unbind('mouseover').unbind('mouseout').unbind('click');if(this.subMenu)
{this.subMenu.destroy();delete this.subMenu;}
this.parentMenu.removeItem(this);},addSubMenu:function(menu)
{if(this.subMenu)
return;this.subMenu=menu;if(this.parentMenu&&$.inArray(menu,this.parentMenu.subMenus)==-1)
this.parentMenu.subMenus.push(menu);if(this.settings.arrowSrc)
{var a=arrowElement.cloneNode(0);a.setAttribute('src',this.settings.arrowSrc);this.$eLI[0].firstChild.appendChild(a);}}}});$.extend($.fn,{menuFromElement:function(options,list,bar)
{var createItems=function(ul)
{var menuItems=[],subItems,menuItem,lis,$li,i,subUL,submenu,target,classNames=null;lis=getAllChilds(ul,'LI');for(i=0;i<lis.length;i++)
{subItems=[];if(!lis[i].childNodes.length)
{menuItems.push(new $.MenuItem('',options));continue;}
if((subUL=getOneChild(lis[i],'UL')))
{subItems=createItems(subUL);$(subUL).remove();}
$li=$(lis[i]);if($li[0].childNodes.length==1&&$li[0].childNodes[0].nodeType==3)
target=$li[0].childNodes[0].nodeValue;else
target=$li[0].childNodes;if(options&&options.copyClassAttr)
classNames=$li.attr('class');menuItem=new $.MenuItem({src:target,addClass:classNames},options);menuItems.push(menuItem);if(subItems.length)
new $.Menu(menuItem,subItems,options);}
return menuItems;};return this.each(function()
{var ul,m;if(list||(ul=getOneChild(this,'UL')))
{ul=list?$(list).clone(true)[0]:ul;menuItems=createItems(ul);if(menuItems.length)
{m=new $.Menu(this,menuItems,options);if(bar)
bar.addMenu(m);}
$(ul).hide();}});},menuBarFromUL:function(options)
{return this.each(function()
{var i,lis=getAllChilds(this,'LI');if(lis.length)
{bar=new $.MenuCollection();for(i=0;i<lis.length;i++)
$(lis[i]).menuFromElement(options,null,bar);}});},menu:function(options,items)
{return this.each(function()
{if(items&&items.constructor==Array)
new $.Menu(this,items,options);else
{if(this.nodeName.toUpperCase()=='UL')
$(this).menuBarFromUL(options);else
$(this).menuFromElement(options,items);}});}});var getOneChild=function(elem,name)
{if(!elem)
return null;var n=elem.firstChild;for(;n;n=n.nextSibling)
{if(n.nodeType==1&&n.nodeName.toUpperCase()==name)
return n;}
return null;};var getAllChilds=function(elem,name)
{if(!elem)
return[];var r=[],n=elem.firstChild;for(;n;n=n.nextSibling)
{if(n.nodeType==1&&n.nodeName.toUpperCase()==name)
r[r.length]=n;}
return r;};})(jQuery);(function($){$.dimensions={version:'1.2'};$.each(['Height','Width'],function(i,name){$.fn['inner'+name]=function(){if(!this[0])return;var torl=name=='Height'?'Top':'Left',borr=name=='Height'?'Bottom':'Right';return this.is(':visible')?this[0]['client'+name]:num(this,name.toLowerCase())+num(this,'padding'+torl)+num(this,'padding'+borr);};$.fn['outer'+name]=function(options){if(!this[0])return;var torl=name=='Height'?'Top':'Left',borr=name=='Height'?'Bottom':'Right';options=$.extend({margin:false},options||{});var val=this.is(':visible')?this[0]['offset'+name]:num(this,name.toLowerCase())
+num(this,'border'+torl+'Width')+num(this,'border'+borr+'Width')
+num(this,'padding'+torl)+num(this,'padding'+borr);return val+(options.margin?(num(this,'margin'+torl)+num(this,'margin'+borr)):0);};});$.each(['Left','Top'],function(i,name){$.fn['scroll'+name]=function(val){if(!this[0])return;return val!=undefined?this.each(function(){this==window||this==document?window.scrollTo(name=='Left'?val:$(window)['scrollLeft'](),name=='Top'?val:$(window)['scrollTop']()):this['scroll'+name]=val;}):this[0]==window||this[0]==document?self[(name=='Left'?'pageXOffset':'pageYOffset')]||$.boxModel&&document.documentElement['scroll'+name]||document.body['scroll'+name]:this[0]['scroll'+name];};});$.fn.extend({position:function(){var left=0,top=0,elem=this[0],offset,parentOffset,offsetParent,results;if(elem){offsetParent=this.offsetParent();offset=this.offset();parentOffset=offsetParent.offset();offset.top-=num(elem,'marginTop');offset.left-=num(elem,'marginLeft');parentOffset.top+=num(offsetParent,'borderTopWidth');parentOffset.left+=num(offsetParent,'borderLeftWidth');results={top:offset.top-parentOffset.top,left:offset.left-parentOffset.left};}
return results;},offsetParent:function(){var offsetParent=this[0].offsetParent;while(offsetParent&&(!/^body|html$/i.test(offsetParent.tagName)&&$.css(offsetParent,'position')=='static'))
offsetParent=offsetParent.offsetParent;return $(offsetParent);}});function num(el,prop){return parseInt($.curCSS(el.jquery?el[0]:el,prop,true))||0;};})(jQuery);;(function($){jQuery.fn.chainSelect=function(target,url,settings)
{return this.each(function()
{$(this).change(function()
{settings=$.extend({after:null,before:null,usePost:false,defaultValue:null,parameters:{'_id':$(this).attr('id'),'_name':$(this).attr('name')}},settings);settings.parameters._value=$(this).val();if(settings.before!=null)
{settings.before(target);}
ajaxCallback=function(data,textStatus)
{$(target).html("");data=eval(data);if(data!=null){for(i=0;i<data.length;i++){$(target).get(0).add(new Option(data[i].name,data[i].value),document.all?i:null);}}else{$(target).get(0).add(new Option('- select a country -',0),document.all?i:null);}
if(settings.defaultValue!=null)
{$(target).val(settings.defaultValue);}else
{$("option:first",target).attr("selected","selected");}
if(settings.after!=null)
{settings.after(target);}
$(target).change();};if(settings.usePost==true)
{$.post(url,settings.parameters,ajaxCallback);}else
{$.get(url,settings.parameters,ajaxCallback);}});});};})(jQuery);;(function($){$.extend($.fn,{swapClass:function(c1,c2){var c1Elements=this.filter('.'+c1);this.filter('.'+c2).removeClass(c2).addClass(c1);c1Elements.removeClass(c1).addClass(c2);return this;},replaceClass:function(c1,c2){return this.filter('.'+c1).removeClass(c1).addClass(c2).end();},hoverClass:function(className){className=className||"hover";return this.hover(function(){$(this).addClass(className);},function(){$(this).removeClass(className);});},heightToggle:function(animated,callback){animated?this.animate({height:"toggle"},animated,callback):this.each(function(){jQuery(this)[jQuery(this).is(":hidden")?"show":"hide"]();if(callback)
callback.apply(this,arguments);});},heightHide:function(animated,callback){if(animated){this.animate({height:"hide"},animated,callback);}else{this.hide();if(callback)
this.each(callback);}},prepareBranches:function(settings){if(!settings.prerendered){this.filter(":last-child:not(ul)").addClass(CLASSES.last);this.filter((settings.collapsed?"":"."+CLASSES.closed)+":not(."+CLASSES.open+")").find(">ul").hide();}
return this.filter(":has(>ul)");},applyClasses:function(settings,toggler){this.filter(":has(>ul):not(:has(>a))").find(">span").click(function(event){toggler.apply($(this).next());}).add($("a",this)).hoverClass();if(!settings.prerendered){this.filter(":has(>ul:hidden)").addClass(CLASSES.expandable).replaceClass(CLASSES.last,CLASSES.lastExpandable);this.not(":has(>ul:hidden)").addClass(CLASSES.collapsable).replaceClass(CLASSES.last,CLASSES.lastCollapsable);this.prepend("<div class=\""+CLASSES.hitarea+"\"/>").find("div."+CLASSES.hitarea).each(function(){var classes="";$.each($(this).parent().attr("class").split(" "),function(){classes+=this+"-hitarea ";});$(this).addClass(classes);});}
this.find("div."+CLASSES.hitarea).click(toggler);},treeview:function(settings){settings=$.extend({cookieId:"treeview"},settings);if(settings.add){return this.trigger("add",[settings.add]);}
if(settings.toggle){var callback=settings.toggle;settings.toggle=function(){return callback.apply($(this).parent()[0],arguments);};}
function treeController(tree,control){function handler(filter){return function(){toggler.apply($("div."+CLASSES.hitarea,tree).filter(function(){return filter?$(this).parent("."+filter).length:true;}));return false;};}
$("a:eq(0)",control).click(handler(CLASSES.collapsable));$("a:eq(1)",control).click(handler(CLASSES.expandable));$("a:eq(2)",control).click(handler());}
function toggler(){$(this).parent().find(">.hitarea").swapClass(CLASSES.collapsableHitarea,CLASSES.expandableHitarea).swapClass(CLASSES.lastCollapsableHitarea,CLASSES.lastExpandableHitarea).end().swapClass(CLASSES.collapsable,CLASSES.expandable).swapClass(CLASSES.lastCollapsable,CLASSES.lastExpandable).find(">ul").heightToggle(settings.animated,settings.toggle);if(settings.unique){$(this).parent().siblings().find(">.hitarea").replaceClass(CLASSES.collapsableHitarea,CLASSES.expandableHitarea).replaceClass(CLASSES.lastCollapsableHitarea,CLASSES.lastExpandableHitarea).end().replaceClass(CLASSES.collapsable,CLASSES.expandable).replaceClass(CLASSES.lastCollapsable,CLASSES.lastExpandable).find(">ul").heightHide(settings.animated,settings.toggle);}}
function serialize(){function binary(arg){return arg?1:0;}
var data=[];branches.each(function(i,e){data[i]=$(e).is(":has(>ul:visible)")?1:0;});$.cookie(settings.cookieId,data.join(""));}
function deserialize(){var stored=$.cookie(settings.cookieId);if(stored){var data=stored.split("");branches.each(function(i,e){$(e).find(">ul")[parseInt(data[i])?"show":"hide"]();});}}
this.addClass("treeview");var branches=this.find("li").prepareBranches(settings);switch(settings.persist){case"cookie":var toggleCallback=settings.toggle;settings.toggle=function(){serialize();if(toggleCallback){toggleCallback.apply(this,arguments);}};deserialize();break;case"location":var current=this.find("a").filter(function(){return this.href.toLowerCase()==location.href.toLowerCase();});if(current.length){current.addClass("selected").parents("ul, li").add(current.next()).show();}
break;}
branches.applyClasses(settings,toggler);if(settings.control){treeController(this,settings.control);$(settings.control).show();}
return this.bind("add",function(event,branches){$(branches).prev().removeClass(CLASSES.last).removeClass(CLASSES.lastCollapsable).removeClass(CLASSES.lastExpandable).find(">.hitarea").removeClass(CLASSES.lastCollapsableHitarea).removeClass(CLASSES.lastExpandableHitarea);$(branches).find("li").andSelf().prepareBranches(settings).applyClasses(settings,toggler);});}});var CLASSES=$.fn.treeview.classes={open:"open",closed:"closed",expandable:"expandable",expandableHitarea:"expandable-hitarea",lastExpandableHitarea:"lastExpandable-hitarea",collapsable:"collapsable",collapsableHitarea:"collapsable-hitarea",lastCollapsableHitarea:"lastCollapsable-hitarea",lastCollapsable:"lastCollapsable",lastExpandable:"lastExpandable",last:"last",hitarea:"hitarea"};$.fn.Treeview=$.fn.treeview;})(jQuery);(function($){$.fn.bgIframe=$.fn.bgiframe=function(s){if($.browser.msie&&/6.0/.test(navigator.userAgent)){s=$.extend({top:'auto',left:'auto',width:'auto',height:'auto',opacity:true,src:'javascript:false;'},s||{});var prop=function(n){return n&&n.constructor==Number?n+'px':n;},html='<iframe class="bgiframe"frameborder="0"tabindex="-1"src="'+s.src+'"'+'style="display:block;position:absolute;z-index:-1;'+
(s.opacity!==false?'filter:Alpha(Opacity=\'0\');':'')+'top:'+(s.top=='auto'?'expression(((parseInt(this.parentNode.currentStyle.borderTopWidth)||0)*-1)+\'px\')':prop(s.top))+';'+'left:'+(s.left=='auto'?'expression(((parseInt(this.parentNode.currentStyle.borderLeftWidth)||0)*-1)+\'px\')':prop(s.left))+';'+'width:'+(s.width=='auto'?'expression(this.parentNode.offsetWidth+\'px\')':prop(s.width))+';'+'height:'+(s.height=='auto'?'expression(this.parentNode.offsetHeight+\'px\')':prop(s.height))+';'+'"/>';return this.each(function(){if($('> iframe.bgiframe',this).length==0)
this.insertBefore(document.createElement(html),this.firstChild);});}
return this;};})(jQuery);;(function($){$.fn.extend({contextMenu:function(o,callback){if(o.menu==undefined)return false;if(o.inSpeed==undefined)o.inSpeed=150;if(o.outSpeed==undefined)o.outSpeed=75;if(o.inSpeed==0)o.inSpeed=-1;if(o.outSpeed==0)o.outSpeed=-1;$(this).each(function(){var el=$(this);var offset=$(el).offset();$('#'+o.menu).addClass('contextMenu');$(this).mousedown(function(e){var evt=e;$(this).mouseup(function(e){var srcElement=$(this);$(this).unbind('mouseup');if(evt.button==2){$(".contextMenu").hide();var menu=$('#'+o.menu);if($(el).hasClass('disabled'))return false;var d={},x,y;if(self.innerHeight){d.pageYOffset=self.pageYOffset;d.pageXOffset=self.pageXOffset;d.innerHeight=self.innerHeight;d.innerWidth=self.innerWidth;}else if(document.documentElement&&document.documentElement.clientHeight){d.pageYOffset=document.documentElement.scrollTop;d.pageXOffset=document.documentElement.scrollLeft;d.innerHeight=document.documentElement.clientHeight;d.innerWidth=document.documentElement.clientWidth;}else if(document.body){d.pageYOffset=document.body.scrollTop;d.pageXOffset=document.body.scrollLeft;d.innerHeight=document.body.clientHeight;d.innerWidth=document.body.clientWidth;}
(e.pageX)?x=e.pageX:x=e.clientX+d.scrollLeft;(e.pageY)?y=e.pageY:x=e.clientY+d.scrollTop;$(document).unbind('click');$(menu).css({top:y,left:x}).fadeIn(o.inSpeed);$(menu).find('A').mouseover(function(){$(menu).find('LI.hover').removeClass('hover');$(this).parent().addClass('hover');}).mouseout(function(){$(menu).find('LI.hover').removeClass('hover');});$(document).keypress(function(e){switch(e.keyCode){case 38:if($(menu).find('LI.hover').size()==0){$(menu).find('LI:last').addClass('hover');}else{$(menu).find('LI.hover').removeClass('hover').prevAll('LI:not(.disabled)').eq(0).addClass('hover');if($(menu).find('LI.hover').size()==0)$(menu).find('LI:last').addClass('hover');}
break;case 40:if($(menu).find('LI.hover').size()==0){$(menu).find('LI:first').addClass('hover');}else{$(menu).find('LI.hover').removeClass('hover').nextAll('LI:not(.disabled)').eq(0).addClass('hover');if($(menu).find('LI.hover').size()==0)$(menu).find('LI:first').addClass('hover');}
break;case 13:$(menu).find('LI.hover A').trigger('click');break;case 27:$(document).trigger('click');break}});$('#'+o.menu).find('A').unbind('click');$('#'+o.menu).find('LI:not(.disabled) A').click(function(){$(document).unbind('click').unbind('keypress');$(".contextMenu").hide();if(callback)callback($(this).attr('href').substr(1),$(srcElement),{x:x-offset.left,y:y-offset.top,docX:x,docY:y});return false;});setTimeout(function(){$(document).click(function(){$(document).unbind('click').unbind('keypress');$(menu).fadeOut(o.outSpeed);return false;});},0);}});});if($.browser.mozilla){$('#'+o.menu).each(function(){$(this).css({'MozUserSelect':'none'});});}else if($.browser.msie){$('#'+o.menu).each(function(){$(this).bind('selectstart.disableTextSelect',function(){return false;});});}else{$('#'+o.menu).each(function(){$(this).bind('mousedown.disableTextSelect',function(){return false;});});}
$(el).add('UL.contextMenu').bind('contextmenu',function(){return false;});});return $(this);},disableContextMenuItems:function(o){if(o==undefined){$(this).find('LI').addClass('disabled');return($(this));}
$(this).each(function(){if(o!=undefined){var d=o.split(',');for(var i=0;i<d.length;i++){$(this).find('A[href="'+d[i]+'"]').parent().addClass('disabled');}}});return($(this));},enableContextMenuItems:function(o){if(o==undefined){$(this).find('LI.disabled').removeClass('disabled');return($(this));}
$(this).each(function(){if(o!=undefined){var d=o.split(',');for(var i=0;i<d.length;i++){$(this).find('A[href="'+d[i]+'"]').parent().removeClass('disabled');}}});return($(this));},disableContextMenu:function(){$(this).each(function(){$(this).addClass('disabled');});return($(this));},enableContextMenu:function(){$(this).each(function(){$(this).removeClass('disabled');});return($(this));},destroyContextMenu:function(){$(this).each(function(){$(this).unbind('mousedown').unbind('mouseup');});return($(this));}});})(jQuery);;(function($){var cells=[];$(document).ready(function(){$('table thead.sticky').each(function(){var height=$(this).parent('table').css('position','relative').height();$('th',this).each(function(){var html=$(this).html();if(html==' '){html=' ';}
if($(this).children().size()==0){html='<span>'+html+'</span>';}
$('<div class="sticky-header" style="position: fixed; display: none; top: 0px;">'+html+'</div>').prependTo(this);var div=$('div.sticky-header',this).css({'marginLeft':'-'+$(this).css('paddingLeft'),'marginRight':'-'+$(this).css('paddingRight'),'paddingLeft':$(this).css('paddingLeft'),'paddingTop':$(this).css('paddingTop'),'paddingRight':$(this).css('paddingRight'),'paddingBottom':$(this).css('paddingBottom')})[0];$(div).css('paddingRight',parseInt($(div).css('paddingRight'))+$(this).width()-$(div).width()+'px');cells.push(div);div.cell=this;div.table=$(this).parent('table')[0];div.stickyMax=height;div.stickyPosition=$(this).y();});});});var scroll=function(){$(cells).each(function(){var scroll=document.documentElement.scrollTop||document.body.scrollTop;var offset=scroll-this.stickyPosition-4;if(offset>0&&offset<this.stickyMax-100){$(this).css({display:'block'});}
else{$(this).css('display','none');}});};$(window).scroll(scroll);$(document.documentElement).scroll(scroll);var resize=function(){$(cells).each(function(){$(this).css({'position':'relative','top':'0'});this.stickyPosition=$(this.cell).y();this.stickyMax=$(this.table).height();});};$(window).resize(resize);$.fn.x=function(n){var result=null;this.each(function(){var o=this;if(n===undefined){var x=0;if(o.offsetParent){while(o.offsetParent){x+=o.offsetLeft;o=o.offsetParent;}}
if(result===null){result=x;}else{result=Math.min(result,x);}}else{o.style.left=n+'px';}});return result;};$.fn.y=function(n){var result=null;this.each(function(){var o=this;if(n===undefined){var y=0;if(o.offsetParent){while(o.offsetParent){y+=o.offsetTop;o=o.offsetParent;}}
if(result===null){result=y;}else{result=Math.min(result,y);}}else{o.style.top=n+'px';}});return result;};})(jQuery);(function($){var lastChecked=null;$(document).ready(function(){$('.form-checkbox').click(function(event){var isSelector=$(this).parent().parent().parent().parent().attr('class');if(isSelector!='selector'){return;}
if(!lastChecked){lastChecked=this;return;}
if(event.shiftKey){var start=$('.form-checkbox').index(this);var end=$('.form-checkbox').index(lastChecked);if(start==end){return;}
var validLastcheck=$(lastChecked).parent().parent().attr('class');var validthischeck=$(this).parent().parent().attr('class');var params=new Array("listing-box","columnheader","sticky","");for(i=0;i<params.length;i++){if(params[i]==validLastcheck||params[i]==validthischeck){return;}}
var min=Math.min(start,end);var max=Math.max(start,end);if(lastChecked.checked&&this.checked){lastChecked.checked=true;}else if(lastChecked.checked&&!this.checked){lastChecked.checked=false;}else if(!lastChecked.checked&&this.checked){lastChecked.checked=true;}else if(!lastChecked.checked&&!this.checked){lastChecked.checked=false;}
for(i=min;i<=max;i++){$('.form-checkbox')[i].checked=lastChecked.checked;}
$('.selector tbody tr td:first-child input:checkbox').each(function(){var oldClass=$(this).parent().parent().attr('class');if(this.checked){$(this).parent().parent().removeClass().addClass('row-selected '+oldClass);}else{var lastClass=$(this).parent().parent().attr('class');var str=lastClass.toString().substring(12);if(lastClass.substring(0,12)=="row-selected"){$(this).parent().parent().removeClass().addClass(str);}}});}
lastChecked=this;});});})(jQuery);$(document).ready(function(){cj('textarea.huge:not(.processed)').TextAreaResizer();cj('textarea.form-textarea:not(.processed)').TextAreaResizer();});(function($){var textarea,staticOffset;var iLastMousePos=0;var iMin=32;var grip;$.fn.TextAreaResizer=function(){return this.each(function(){textarea=$(this).addClass('processed'),staticOffset=null;$(this).wrap('<div class="resizable-textarea"><span></span></div>').parent().append($('<div class="grippie"></div>').bind("mousedown",{el:this},startDrag));var grippie=$('div.grippie',$(this).parent())[0];grippie.style.marginRight=(grippie.offsetWidth-$(this)[0].offsetWidth)+'px';});};function startDrag(e){textarea=$(e.data.el);textarea.blur();iLastMousePos=mousePosition(e).y;staticOffset=textarea.height()-iLastMousePos;textarea.css('opacity',0.25);$(document).mousemove(performDrag).mouseup(endDrag);return false;}
function performDrag(e){var iThisMousePos=mousePosition(e).y;var iMousePos=staticOffset+iThisMousePos;if(iLastMousePos>=(iThisMousePos)){iMousePos-=5;}
iLastMousePos=iThisMousePos;iMousePos=Math.max(iMin,iMousePos);textarea.height(iMousePos+'px');if(iMousePos<iMin){endDrag(e);}
return false;}
function endDrag(e){$(document).unbind('mousemove',performDrag).unbind('mouseup',endDrag);textarea.css('opacity',1);textarea.focus();textarea=null;staticOffset=null;iLastMousePos=0;}
function mousePosition(e){return{x:e.clientX+document.documentElement.scrollLeft,y:e.clientY+document.documentElement.scrollTop};};})(jQuery);(function($){$.extend({progressBar:new function(){this.defaults={increment:2,speed:15,showText:true,width:120,boxImage:'images/progressbar.gif',barImage:{0:'images/progressbg_red.gif',30:'images/progressbg_orange.gif',70:'images/progressbg_green.gif'},height:12};this.construct=function(arg1,arg2){var argpercentage=null;var argconfig=null;if(arg1!=null){if(!isNaN(arg1)){argpercentage=arg1;if(arg2!=null){argconfig=arg2;}}else{argconfig=arg1;}}
return this.each(function(child){var pb=this;if(argpercentage!=null&&this.bar!=null&&this.config!=null){this.config.tpercentage=argpercentage;if(argconfig!=null)
pb.config=$.extend(this.config,argconfig);}else{var $this=$(this);var config=$.extend({},$.progressBar.defaults,argconfig);var percentage=argpercentage;if(argpercentage==null)
var percentage=$this.html().replace("%","");$this.html("");var bar=document.createElement('img');var text=document.createElement('span');bar.id=this.id+"_percentImage";text.id=this.id+"_percentText";bar.title=percentage+"%";bar.alt=percentage+"%";bar.src=config.boxImage;bar.width=config.width;var $bar=$(bar);var $text=$(text);this.bar=$bar;this.ntext=$text;this.config=config;this.config.cpercentage=0;this.config.tpercentage=percentage;$bar.css("width",config.width+"px");$bar.css("height",config.height+"px");$bar.css("background-image","url("+getBarImage(this.config.cpercentage,config)+")");$bar.css("padding","0");$bar.css("margin","0");$this.append($bar);$this.append($text);}
function getBarImage(percentage,config){var image=config.barImage;if(typeof(config.barImage)=='object'){for(var i in config.barImage){if(percentage>=parseInt(i)){image=config.barImage[i];}else{break;}}}
return image;}
var t=setInterval(function(){var config=pb.config;var cpercentage=parseInt(config.cpercentage);var tpercentage=parseInt(config.tpercentage);var increment=parseInt(config.increment);var bar=pb.bar;var text=pb.ntext;var pixels=config.width/100;bar.css("background-image","url("+getBarImage(cpercentage,config)+")");bar.css("background-position",(((config.width*-1))+(cpercentage*pixels))+'px 50%');if(config.showText)
text.html(" "+Math.round(cpercentage)+"%");if(cpercentage>tpercentage){if(cpercentage-increment<tpercentage){pb.config.cpercentage=0+tpercentage}else{pb.config.cpercentage-=increment;}}
else if(pb.config.cpercentage<pb.config.tpercentage){if(cpercentage+increment>tpercentage){pb.config.cpercentage=tpercentage}else{pb.config.cpercentage+=increment;}}
else{clearInterval(t);}},pb.config.speed);});};}});$.fn.extend({progressBar:$.progressBar.construct});})(jQuery);;(function($){$.fn.ajaxSubmit=function(options){if(!this.length){log('ajaxSubmit: skipping submit process - no element selected');return this;}
if(typeof options=='function')
options={success:options};var url=this.attr('action')||window.location.href;url=(url.match(/^([^#]+)/)||[])[1];url=url||'';options=$.extend({url:url,type:this.attr('method')||'GET'},options||{});var veto={};this.trigger('form-pre-serialize',[this,options,veto]);if(veto.veto){log('ajaxSubmit: submit vetoed via form-pre-serialize trigger');return this;}
if(options.beforeSerialize&&options.beforeSerialize(this,options)===false){log('ajaxSubmit: submit aborted via beforeSerialize callback');return this;}
var a=this.formToArray(options.semantic);if(options.data){options.extraData=options.data;for(var n in options.data){if(options.data[n]instanceof Array){for(var k in options.data[n])
a.push({name:n,value:options.data[n][k]});}
else
a.push({name:n,value:options.data[n]});}}
if(options.beforeSubmit&&options.beforeSubmit(a,this,options)===false){log('ajaxSubmit: submit aborted via beforeSubmit callback');return this;}
this.trigger('form-submit-validate',[a,this,options,veto]);if(veto.veto){log('ajaxSubmit: submit vetoed via form-submit-validate trigger');return this;}
var q=$.param(a);if(options.type.toUpperCase()=='GET'){options.url+=(options.url.indexOf('?')>=0?'&':'?')+q;options.data=null;}
else
options.data=q;var $form=this,callbacks=[];if(options.resetForm)callbacks.push(function(){$form.resetForm();});if(options.clearForm)callbacks.push(function(){$form.clearForm();});if(!options.dataType&&options.target){var oldSuccess=options.success||function(){};callbacks.push(function(data){$(options.target).html(data).each(oldSuccess,arguments);});}
else if(options.success)
callbacks.push(options.success);options.success=function(data,status){for(var i=0,max=callbacks.length;i<max;i++)
callbacks[i].apply(options,[data,status,$form]);};var files=$('input:file',this).fieldValue();var found=false;for(var j=0;j<files.length;j++)
if(files[j])
found=true;if(options.iframe||found){if(options.closeKeepAlive)
$.get(options.closeKeepAlive,fileUpload);else
fileUpload();}
else
$.ajax(options);this.trigger('form-submit-notify',[this,options]);return this;function fileUpload(){var form=$form[0];if($(':input[name=submit]',form).length){alert('Error: Form elements must not be named "submit".');return;}
var opts=$.extend({},$.ajaxSettings,options);var s=$.extend(true,{},$.extend(true,{},$.ajaxSettings),opts);var id='jqFormIO'+(new Date().getTime());var $io=$('<iframe id="'+id+'" name="'+id+'" src="about:blank" />');var io=$io[0];$io.css({position:'absolute',top:'-1000px',left:'-1000px'});var xhr={aborted:0,responseText:null,responseXML:null,status:0,statusText:'n/a',getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(){this.aborted=1;$io.attr('src','about:blank');}};var g=opts.global;if(g&&!$.active++)$.event.trigger("ajaxStart");if(g)$.event.trigger("ajaxSend",[xhr,opts]);if(s.beforeSend&&s.beforeSend(xhr,s)===false){s.global&&$.active--;return;}
if(xhr.aborted)
return;var cbInvoked=0;var timedOut=0;var sub=form.clk;if(sub){var n=sub.name;if(n&&!sub.disabled){options.extraData=options.extraData||{};options.extraData[n]=sub.value;if(sub.type=="image"){options.extraData[name+'.x']=form.clk_x;options.extraData[name+'.y']=form.clk_y;}}}
setTimeout(function(){var t=$form.attr('target'),a=$form.attr('action');form.setAttribute('target',id);if(form.getAttribute('method')!='POST')
form.setAttribute('method','POST');if(form.getAttribute('action')!=opts.url)
form.setAttribute('action',opts.url);if(!options.skipEncodingOverride){$form.attr({encoding:'multipart/form-data',enctype:'multipart/form-data'});}
if(opts.timeout)
setTimeout(function(){timedOut=true;cb();},opts.timeout);var extraInputs=[];try{if(options.extraData)
for(var n in options.extraData)
extraInputs.push($('<input type="hidden" name="'+n+'" value="'+options.extraData[n]+'" />').appendTo(form)[0]);$io.appendTo('body');io.attachEvent?io.attachEvent('onload',cb):io.addEventListener('load',cb,false);form.submit();}
finally{form.setAttribute('action',a);t?form.setAttribute('target',t):$form.removeAttr('target');$(extraInputs).remove();}},10);var nullCheckFlag=0;function cb(){if(cbInvoked++)return;io.detachEvent?io.detachEvent('onload',cb):io.removeEventListener('load',cb,false);var ok=true;try{if(timedOut)throw'timeout';var data,doc;doc=io.contentWindow?io.contentWindow.document:io.contentDocument?io.contentDocument:io.document;if((doc.body==null||doc.body.innerHTML=='')&&!nullCheckFlag){nullCheckFlag=1;cbInvoked--;setTimeout(cb,100);return;}
xhr.responseText=doc.body?doc.body.innerHTML:null;xhr.responseXML=doc.XMLDocument?doc.XMLDocument:doc;xhr.getResponseHeader=function(header){var headers={'content-type':opts.dataType};return headers[header];};if(opts.dataType=='json'||opts.dataType=='script'){var ta=doc.getElementsByTagName('textarea')[0];xhr.responseText=ta?ta.value:xhr.responseText;}
else if(opts.dataType=='xml'&&!xhr.responseXML&&xhr.responseText!=null){xhr.responseXML=toXml(xhr.responseText);}
data=$.httpData(xhr,opts.dataType);}
catch(e){ok=false;$.handleError(opts,xhr,'error',e);}
if(ok){opts.success(data,'success');if(g)$.event.trigger("ajaxSuccess",[xhr,opts]);}
if(g)$.event.trigger("ajaxComplete",[xhr,opts]);if(g&&!--$.active)$.event.trigger("ajaxStop");if(opts.complete)opts.complete(xhr,ok?'success':'error');setTimeout(function(){$io.remove();xhr.responseXML=null;},100);};function toXml(s,doc){if(window.ActiveXObject){doc=new ActiveXObject('Microsoft.XMLDOM');doc.async='false';doc.loadXML(s);}
else
doc=(new DOMParser()).parseFromString(s,'text/xml');return(doc&&doc.documentElement&&doc.documentElement.tagName!='parsererror')?doc:null;};};};$.fn.ajaxForm=function(options){return this.ajaxFormUnbind().bind('submit.form-plugin',function(){$(this).ajaxSubmit(options);return false;}).each(function(){$(":submit,input:image",this).bind('click.form-plugin',function(e){var form=this.form;form.clk=this;if(this.type=='image'){if(e.offsetX!=undefined){form.clk_x=e.offsetX;form.clk_y=e.offsetY;}else if(typeof $.fn.offset=='function'){var offset=$(this).offset();form.clk_x=e.pageX-offset.left;form.clk_y=e.pageY-offset.top;}else{form.clk_x=e.pageX-this.offsetLeft;form.clk_y=e.pageY-this.offsetTop;}}
setTimeout(function(){form.clk=form.clk_x=form.clk_y=null;},10);});});};$.fn.ajaxFormUnbind=function(){this.unbind('submit.form-plugin');return this.each(function(){$(":submit,input:image",this).unbind('click.form-plugin');});};$.fn.formToArray=function(semantic){var a=[];if(this.length==0)return a;var form=this[0];var els=semantic?form.getElementsByTagName('*'):form.elements;if(!els)return a;for(var i=0,max=els.length;i<max;i++){var el=els[i];var n=el.name;if(!n)continue;if(semantic&&form.clk&&el.type=="image"){if(!el.disabled&&form.clk==el)
a.push({name:n+'.x',value:form.clk_x},{name:n+'.y',value:form.clk_y});continue;}
var v=$.fieldValue(el,true);if(v&&v.constructor==Array){for(var j=0,jmax=v.length;j<jmax;j++)
a.push({name:n,value:v[j]});}
else if(v!==null&&typeof v!='undefined')
a.push({name:n,value:v});}
if(!semantic&&form.clk){var inputs=form.getElementsByTagName("input");for(var i=0,max=inputs.length;i<max;i++){var input=inputs[i];var n=input.name;if(n&&!input.disabled&&input.type=="image"&&form.clk==input)
a.push({name:n+'.x',value:form.clk_x},{name:n+'.y',value:form.clk_y});}}
return a;};$.fn.formSerialize=function(semantic){return $.param(this.formToArray(semantic));};$.fn.fieldSerialize=function(successful){var a=[];this.each(function(){var n=this.name;if(!n)return;var v=$.fieldValue(this,successful);if(v&&v.constructor==Array){for(var i=0,max=v.length;i<max;i++)
a.push({name:n,value:v[i]});}
else if(v!==null&&typeof v!='undefined')
a.push({name:this.name,value:v});});return $.param(a);};$.fn.fieldValue=function(successful){for(var val=[],i=0,max=this.length;i<max;i++){var el=this[i];var v=$.fieldValue(el,successful);if(v===null||typeof v=='undefined'||(v.constructor==Array&&!v.length))
continue;v.constructor==Array?$.merge(val,v):val.push(v);}
return val;};$.fieldValue=function(el,successful){var n=el.name,t=el.type,tag=el.tagName.toLowerCase();if(typeof successful=='undefined')successful=true;if(successful&&(!n||el.disabled||t=='reset'||t=='button'||(t=='checkbox'||t=='radio')&&!el.checked||(t=='submit'||t=='image')&&el.form&&el.form.clk!=el||tag=='select'&&el.selectedIndex==-1))
return null;if(tag=='select'){var index=el.selectedIndex;if(index<0)return null;var a=[],ops=el.options;var one=(t=='select-one');var max=(one?index+1:ops.length);for(var i=(one?index:0);i<max;i++){var op=ops[i];if(op.selected){var v=op.value;if(!v)
v=(op.attributes&&op.attributes['value']&&!(op.attributes['value'].specified))?op.text:op.value;if(one)return v;a.push(v);}}
return a;}
return el.value;};$.fn.clearForm=function(){return this.each(function(){$('input,select,textarea',this).clearFields();});};$.fn.clearFields=$.fn.clearInputs=function(){return this.each(function(){var t=this.type,tag=this.tagName.toLowerCase();if(t=='text'||t=='password'||tag=='textarea')
this.value='';else if(t=='checkbox'||t=='radio')
this.checked=false;else if(tag=='select')
this.selectedIndex=-1;});};$.fn.resetForm=function(){return this.each(function(){if(typeof this.reset=='function'||(typeof this.reset=='object'&&!this.reset.nodeType))
this.reset();});};$.fn.enable=function(b){if(b==undefined)b=true;return this.each(function(){this.disabled=!b;});};$.fn.selected=function(select){if(select==undefined)select=true;return this.each(function(){var t=this.type;if(t=='checkbox'||t=='radio')
this.checked=select;else if(this.tagName.toLowerCase()=='option'){var $sel=$(this).parent('select');if(select&&$sel[0]&&$sel[0].type=='select-one'){$sel.find('option').selected(false);}
this.selected=select;}});};function log(){if($.fn.ajaxSubmit.debug&&window.console&&window.console.log)
window.console.log('[jquery.form] '+Array.prototype.join.call(arguments,''));};})(jQuery);(function($){$.fn.tokenInput=function(url,options){var settings=$.extend({url:url,hintText:options.hintText,noResultsText:"No results",searchingText:"Searching...",searchDelay:600,minChars:2,tokenLimit:null,jsonContainer:null,method:"GET",contentType:"json",queryParam:"name",onResult:null},options);settings.classes=$.extend({tokenList:"token-input-list",token:"token-input-token",tokenDelete:"token-input-delete-token",selectedToken:"token-input-selected-token",highlightedToken:"token-input-highlighted-token",dropdown:"token-input-dropdown",dropdownItem:"token-input-dropdown-item",dropdownItem2:"token-input-dropdown-item2",selectedDropdownItem:"token-input-selected-dropdown-item",inputToken:"token-input-input-token"},options.classes);return this.each(function(){var list=new $.TokenList(this,settings);});};$.TokenList=function(input,settings){var POSITION={BEFORE:0,AFTER:1,END:2};var KEY={BACKSPACE:8,TAB:9,RETURN:13,ESC:27,LEFT:37,UP:38,RIGHT:39,DOWN:40,COMMA:188};var saved_tokens=[];var token_count=0;var cache=new $.TokenList.Cache();var timeout;var input_box=$("<input type=\"text\">").css({outline:"none"}).focus(function(){if(settings.tokenLimit==null||settings.tokenLimit!=token_count){show_dropdown_hint();}}).blur(function(){hide_dropdown();}).keydown(function(event){var previous_token;var next_token;switch(event.keyCode){case KEY.LEFT:case KEY.RIGHT:case KEY.UP:case KEY.DOWN:if(!$(this).val()){previous_token=input_token.prev();next_token=input_token.next();if((previous_token.length&&previous_token.get(0)===selected_token)||(next_token.length&&next_token.get(0)===selected_token)){if(event.keyCode==KEY.LEFT||event.keyCode==KEY.UP){deselect_token($(selected_token),POSITION.BEFORE);}else{deselect_token($(selected_token),POSITION.AFTER);}}else if((event.keyCode==KEY.LEFT||event.keyCode==KEY.UP)&&previous_token.length){select_token($(previous_token.get(0)));}else if((event.keyCode==KEY.RIGHT||event.keyCode==KEY.DOWN)&&next_token.length){select_token($(next_token.get(0)));}}else{var dropdown_item=null;if(event.keyCode==KEY.DOWN||event.keyCode==KEY.RIGHT){dropdown_item=$(selected_dropdown_item).next();}else{dropdown_item=$(selected_dropdown_item).prev();}
if(dropdown_item.length){select_dropdown_item(dropdown_item);}
return false;}
break;case KEY.BACKSPACE:previous_token=input_token.prev();if(!$(this).val().length){if(selected_token){delete_token($(selected_token));}else if(previous_token.length){select_token($(previous_token.get(0)));}
return false;}else if($(this).val().length==1){hide_dropdown();}else{setTimeout(function(){do_search(false);},5);}
break;case KEY.TAB:case KEY.RETURN:case KEY.COMMA:if(selected_dropdown_item){add_token($(selected_dropdown_item));return false;}
break;case KEY.ESC:hide_dropdown();return true;default:if(is_printable_character(event.keyCode)){setTimeout(function(){do_search(false);},5);}
break;}});var hidden_input=$(input).hide().focus(function(){input_box.focus();}).blur(function(){input_box.blur();});var selected_token=null;var selected_dropdown_item=null;var token_list=$("<ul />").addClass(settings.classes.tokenList).insertAfter(hidden_input).click(function(event){var li=get_element_from_event(event,"li");if(li&&li.get(0)!=input_token.get(0)){toggle_select_token(li);return false;}else{input_box.focus();if(selected_token){deselect_token($(selected_token),POSITION.END);}}}).mouseover(function(event){var li=get_element_from_event(event,"li");if(li&&selected_token!==this){li.addClass(settings.classes.highlightedToken);}}).mouseout(function(event){var li=get_element_from_event(event,"li");if(li&&selected_token!==this){li.removeClass(settings.classes.highlightedToken);}}).mousedown(function(event){var li=get_element_from_event(event,"li");if(li){return false;}});var dropdown=$("<div>").addClass(settings.classes.dropdown).insertAfter(token_list).hide();var input_token=$("<li />").addClass(settings.classes.inputToken).appendTo(token_list).append(input_box);init_list();function init_list(){li_data=settings.prePopulate;if(li_data&&li_data.length){for(var i in li_data){if(li_data[i].id){var this_token=$("<li><p>"+li_data[i].name+"</p> </li>").addClass(settings.classes.token).insertBefore(input_token);$("<span>x</span>").addClass(settings.classes.tokenDelete).appendTo(this_token).click(function(){delete_token($(this).parent());return false;});$.data(this_token.get(0),"tokeninput",{"id":li_data[i].id,"name":li_data[i].name});input_box.val("").focus();hide_dropdown();var id_string=li_data[i].id;if(hidden_input.val()){id_string=','+id_string;}
hidden_input.val(hidden_input.val()+id_string);}}}}
function is_printable_character(keycode){if((keycode>=48&&keycode<=90)||(keycode>=96&&keycode<=111)||(keycode>=186&&keycode<=192)||(keycode>=219&&keycode<=222)){return true;}else{return false;}}
function get_element_from_event(event,element_type){var target=$(event.target);var element=null;if(target.is(element_type)){element=target;}else if(target.parent(element_type).length){element=target.parent(element_type+":first");}
return element;}
function insert_token(id,value){var this_token=$("<li><p>"+value+"</p> </li>").addClass(settings.classes.token).insertBefore(input_token);$("<span>x</span>").addClass(settings.classes.tokenDelete).appendTo(this_token).click(function(){delete_token($(this).parent());return false;});$.data(this_token.get(0),"tokeninput",{"id":id,"name":value});return this_token;}
function add_token(item){var li_data=$.data(item.get(0),"tokeninput");var this_token=insert_token(li_data.id,li_data.name);input_box.val("").focus();hide_dropdown();var id_string=li_data.id;if(hidden_input.val()){id_string=','+id_string;}
hidden_input.val(hidden_input.val()+id_string);token_count++;if(settings.tokenLimit!=null&&settings.tokenLimit>=token_count){input_box.hide();hide_dropdown();}}
function select_token(token){token.addClass(settings.classes.selectedToken);selected_token=token.get(0);input_box.val("");hide_dropdown();}
function deselect_token(token,position){token.removeClass(settings.classes.selectedToken);selected_token=null;if(position==POSITION.BEFORE){input_token.insertBefore(token);}else if(position==POSITION.AFTER){input_token.insertAfter(token);}else{input_token.appendTo(token_list);}
input_box.focus();}
function toggle_select_token(token){if(selected_token==token.get(0)){deselect_token(token,POSITION.END);}else{if(selected_token){deselect_token($(selected_token),POSITION.END);}
select_token(token);}}
function delete_token(token){var token_data=$.data(token.get(0),"tokeninput");token.remove();selected_token=null;input_box.focus();var str=hidden_input.val()
var resultantTokenStr='';if(str.indexOf(',')!=-1){var deleteTokenStr=','+token_data.id;if(str.indexOf(token_data.id)==0){deleteTokenStr=token_data.id+',';}
var start=str.indexOf(deleteTokenStr);var end=start+deleteTokenStr.length;resultantTokenStr=str.slice(0,start)+str.slice(end,str.length);}
hidden_input.val(resultantTokenStr);token_count--;if(settings.tokenLimit!=null){input_box.show().val("").focus();}}
function hide_dropdown(){dropdown.hide().empty();selected_dropdown_item=null;}
function show_dropdown_searching(){dropdown.html("<p>"+settings.searchingText+"</p>").show();}
function show_dropdown_hint(){dropdown.html("<p>"+settings.hintText+"</p>").show();}
function highlight_term(value,term){return value.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)("+term+")(?![^<>]*>)(?![^&;]+;)","gi"),"<b>$1</b>");}
function populate_dropdown(query,results){if(results.length){dropdown.empty();var dropdown_ul=$("<ul>").appendTo(dropdown).mouseover(function(event){select_dropdown_item(get_element_from_event(event,"li"));}).click(function(event){add_token(get_element_from_event(event,"li"));}).mousedown(function(event){return false;}).hide();for(var i in results){if(results.hasOwnProperty(i)){var this_li=$("<li>"+highlight_term(results[i].name,query)+"</li>").appendTo(dropdown_ul);if(i%2){this_li.addClass(settings.classes.dropdownItem);}else{this_li.addClass(settings.classes.dropdownItem2);}
if(i==0){select_dropdown_item(this_li);}
$.data(this_li.get(0),"tokeninput",{"id":results[i].id,"name":results[i].name});}}
dropdown.show();dropdown_ul.slideDown("fast");}else{dropdown.html("<p>"+settings.noResultsText+"</p>").show();}}
function select_dropdown_item(item){if(item){if(selected_dropdown_item){deselect_dropdown_item($(selected_dropdown_item));}
item.addClass(settings.classes.selectedDropdownItem);selected_dropdown_item=item.get(0);}}
function deselect_dropdown_item(item){item.removeClass(settings.classes.selectedDropdownItem);selected_dropdown_item=null;}
function do_search(immediate){var query=input_box.val().toLowerCase();if(query&&query.length){if(selected_token){deselect_token($(selected_token),POSITION.AFTER);}
if(query.length>=settings.minChars){show_dropdown_searching();if(immediate){run_search(query);}else{clearTimeout(timeout);timeout=setTimeout(function(){run_search(query);},settings.searchDelay);}}else{hide_dropdown();}}}
function run_search(query){var cached_results=cache.get(query);if(cached_results){populate_dropdown(query,cached_results);}else{var queryStringDelimiter=settings.url.indexOf("?")<0?"?":"&";var callback=function(results){if($.isFunction(settings.onResult)){results=settings.onResult.call(this,results);}
cache.add(query,settings.jsonContainer?results[settings.jsonContainer]:results);populate_dropdown(query,settings.jsonContainer?results[settings.jsonContainer]:results);};if(settings.method=="POST"){$.post(settings.url+queryStringDelimiter+settings.queryParam+"="+query,{},callback,settings.contentType);}else{$.get(settings.url+queryStringDelimiter+settings.queryParam+"="+query,{},callback,settings.contentType);}}}};$.TokenList.Cache=function(options){var settings=$.extend({max_size:50},options);var data={};var size=0;var flush=function(){data={};size=0;};this.add=function(query,results){if(size>settings.max_size){flush();}
if(!data[query]){size++;}
data[query]=results;};this.get=function(query){return data[query];};};})(jQuery);(function($){function TimeEntry(){this._disabledInputs=[];this.regional=[];this.regional['']={show24Hours:false,separator:':',ampmPrefix:'',ampmNames:['AM','PM'],spinnerTexts:['Now','Previous field','Next field','Increment','Decrement']};this._defaults={appendText:'',showSeconds:false,timeSteps:[1,1,1],initialField:0,useMouseWheel:true,defaultTime:null,minTime:null,maxTime:null,spinnerImage:'spinnerDefault.png',spinnerSize:[20,20,8],spinnerBigImage:'',spinnerBigSize:[40,40,16],spinnerIncDecOnly:false,spinnerRepeat:[500,250],beforeShow:null,beforeSetTime:null};$.extend(this._defaults,this.regional['']);}
var PROP_NAME='timeEntry';$.extend(TimeEntry.prototype,{markerClassName:'hasTimeEntry',setDefaults:function(options){extendRemove(this._defaults,options||{});return this;},_connectTimeEntry:function(target,options){var input=$(target);if(input.hasClass(this.markerClassName)){return;}
var inst={};inst.options=$.extend({},options);inst._selectedHour=0;inst._selectedMinute=0;inst._selectedSecond=0;inst._field=0;inst.input=$(target);$.data(target,PROP_NAME,inst);var spinnerImage=this._get(inst,'spinnerImage');var spinnerText=this._get(inst,'spinnerText');var spinnerSize=this._get(inst,'spinnerSize');var appendText=this._get(inst,'appendText');var spinner=(!spinnerImage?null:$('<span class="timeEntry_control" style="display: inline-block; '+'background: url(\''+spinnerImage+'\') 0 0 no-repeat; '+'width: '+spinnerSize[0]+'px; height: '+spinnerSize[1]+'px;'+
($.browser.mozilla&&$.browser.version<'1.9'?' padding-left: '+spinnerSize[0]+'px; padding-bottom: '+
(spinnerSize[1]-18)+'px;':'')+'"></span>'));input.wrap('<span class="timeEntry_wrap"></span>').after(appendText?'<span class="timeEntry_append">'+appendText+'</span>':'').after(spinner||'');input.addClass(this.markerClassName).bind('focus.timeEntry',this._doFocus).bind('blur.timeEntry',this._doBlur).bind('click.timeEntry',this._doClick).bind('keydown.timeEntry',this._doKeyDown).bind('keypress.timeEntry',this._doKeyPress);if($.browser.mozilla){input.bind('input.timeEntry',function(event){$.timeentry._parseTime(inst);});}
if($.browser.msie){input.bind('paste.timeEntry',function(event){setTimeout(function(){$.timeentry._parseTime(inst);},1);});}
if(this._get(inst,'useMouseWheel')&&$.fn.mousewheel){input.mousewheel(this._doMouseWheel);}
if(spinner){spinner.mousedown(this._handleSpinner).mouseup(this._endSpinner).mouseover(this._expandSpinner).mouseout(this._endSpinner).mousemove(this._describeSpinner);}},_enableTimeEntry:function(input){this._enableDisable(input,false);},_disableTimeEntry:function(input){this._enableDisable(input,true);},_enableDisable:function(input,disable){var inst=$.data(input,PROP_NAME);if(!inst){return;}
input.disabled=disable;if(input.nextSibling&&input.nextSibling.nodeName.toLowerCase()=='span'){$.timeEntry._changeSpinner(inst,input.nextSibling,(disable?5:-1));}
$.timeEntry._disabledInputs=$.map($.timeEntry._disabledInputs,function(value){return(value==input?null:value);});if(disable){$.timeEntry._disabledInputs.push(input);}},_isDisabledTimeEntry:function(input){return $.inArray(input,this._disabledInputs)>-1;},_changeTimeEntry:function(input,options){var inst=$.data(input,PROP_NAME);if(inst){var currentTime=this._extractTime(inst);extendRemove(inst.options,options||{});if(currentTime){this._setTime(inst,new Date(0,0,0,currentTime[0],currentTime[1],currentTime[2]));}}
$.data(input,PROP_NAME,inst);},_destroyTimeEntry:function(input){$input=$(input);if(!$input.hasClass(this.markerClassName)){return;}
$input.removeClass(this.markerClassName).unbind('.timeEntry');if($.fn.mousewheel){$input.unmousewheel();}
this._disabledInputs=$.map(this._disabledInputs,function(value){return(value==input?null:value);});$input.parent().replaceWith($input);$.removeData(input,PROP_NAME);},_setTimeTimeEntry:function(input,time){var inst=$.data(input,PROP_NAME);if(inst){this._setTime(inst,time?(typeof time=='object'?new Date(time.getTime()):time):null);}},_getTimeTimeEntry:function(input){var inst=$.data(input,PROP_NAME);var currentTime=(inst?this._extractTime(inst):null);return(!currentTime?null:new Date(0,0,0,currentTime[0],currentTime[1],currentTime[2]));},_doFocus:function(target){var input=(target.nodeName&&target.nodeName.toLowerCase()=='input'?target:this);if($.timeEntry._lastInput==input||$.timeEntry._isDisabledTimeEntry(input)){$.timeEntry._focussed=false;return;}
var inst=$.data(input,PROP_NAME);$.timeEntry._focussed=true;$.timeEntry._lastInput=input;$.timeEntry._blurredInput=null;var beforeShow=$.timeEntry._get(inst,'beforeShow');extendRemove(inst.options,(beforeShow?beforeShow.apply(input,[input]):{}));$.data(input,PROP_NAME,inst);$.timeEntry._parseTime(inst);setTimeout(function(){$.timeEntry._showField(inst);},10);},_doBlur:function(event){$.timeEntry._blurredInput=$.timeEntry._lastInput;$.timeEntry._lastInput=null;},_doClick:function(event){var input=event.target;var inst=$.data(input,PROP_NAME);if(!$.timeEntry._focussed){var fieldSize=$.timeEntry._get(inst,'separator').length+2;inst._field=0;if(input.selectionStart!=null){for(var field=0;field<=Math.max(1,inst._secondField,inst._ampmField);field++){var end=(field!=inst._ampmField?(field*fieldSize)+2:(inst._ampmField*fieldSize)+$.timeEntry._get(inst,'ampmPrefix').length+
$.timeEntry._get(inst,'ampmNames')[0].length);inst._field=field;if(input.selectionStart<end){break;}}}
else if(input.createTextRange){var src=$(event.srcElement);var range=input.createTextRange();var convert=function(value){return{thin:2,medium:4,thick:6}[value]||value;};var offsetX=event.clientX+document.documentElement.scrollLeft-
(src.offset().left+parseInt(convert(src.css('border-left-width')),10))-
range.offsetLeft;for(var field=0;field<=Math.max(1,inst._secondField,inst._ampmField);field++){var end=(field!=inst._ampmField?(field*fieldSize)+2:(inst._ampmField*fieldSize)+$.timeEntry._get(inst,'ampmPrefix').length+
$.timeEntry._get(inst,'ampmNames')[0].length);range.collapse();range.moveEnd('character',end);inst._field=field;if(offsetX<range.boundingWidth){break;}}}}
$.data(input,PROP_NAME,inst);$.timeEntry._showField(inst);$.timeEntry._focussed=false;},_doKeyDown:function(event){if(event.keyCode>=48){return true;}
var inst=$.data(event.target,PROP_NAME);switch(event.keyCode){case 9:return(event.shiftKey?$.timeEntry._changeField(inst,-1,true):$.timeEntry._changeField(inst,+1,true));case 35:if(event.ctrlKey){$.timeEntry._setValue(inst,'');}
else{inst._field=Math.max(1,inst._secondField,inst._ampmField);$.timeEntry._adjustField(inst,0);}
break;case 36:if(event.ctrlKey){$.timeEntry._setTime(inst);}
else{inst._field=0;$.timeEntry._adjustField(inst,0);}
break;case 37:$.timeEntry._changeField(inst,-1,false);break;case 38:$.timeEntry._adjustField(inst,+1);break;case 39:$.timeEntry._changeField(inst,+1,false);break;case 40:$.timeEntry._adjustField(inst,-1);break;case 46:$.timeEntry._setValue(inst,'');break;}
return false;},_doKeyPress:function(event){var chr=String.fromCharCode(event.charCode==undefined?event.keyCode:event.charCode);if(chr<' '){return true;}
var inst=$.data(event.target,PROP_NAME);$.timeEntry._handleKeyPress(inst,chr);return false;},_doMouseWheel:function(event,delta){if($.timeEntry._isDisabledTimeEntry(event.target)){return;}
delta=($.browser.opera?-delta/Math.abs(delta):($.browser.safari?delta/Math.abs(delta):delta));var inst=$.data(event.target,PROP_NAME);inst.input.focus();if(!inst.input.val()){$.timeEntry._parseTime(inst);}
$.timeEntry._adjustField(inst,delta);event.preventDefault();},_expandSpinner:function(event){var spinner=$.timeEntry._getSpinnerTarget(event);var inst=$.data($.timeEntry._getInput(spinner),PROP_NAME);var spinnerBigImage=$.timeEntry._get(inst,'spinnerBigImage');if(spinnerBigImage){inst._expanded=true;var offset=$(spinner).offset();var relative=null;$(spinner).parents().each(function(){var parent=$(this);if(parent.css('position')=='relative'||parent.css('position')=='absolute'){relative=parent.offset();}
return!relative;});var spinnerSize=$.timeEntry._get(inst,'spinnerSize');var spinnerBigSize=$.timeEntry._get(inst,'spinnerBigSize');$('<div class="timeEntry_expand" style="position: absolute; left: '+
(offset.left-(spinnerBigSize[0]-spinnerSize[0])/2-
(relative?relative.left:0))+'px; top: '+(offset.top-
(spinnerBigSize[1]-spinnerSize[1])/2-(relative?relative.top:0))+'px; width: '+spinnerBigSize[0]+'px; height: '+
spinnerBigSize[1]+'px; background: transparent url('+
spinnerBigImage+') no-repeat 0px 0px; z-index: 10;"></div>').mousedown($.timeEntry._handleSpinner).mouseup($.timeEntry._endSpinner).mouseout($.timeEntry._endExpand).mousemove($.timeEntry._describeSpinner).insertAfter(spinner);}},_getInput:function(spinner){return $(spinner).siblings('.'+$.timeEntry.markerClassName)[0];},_describeSpinner:function(event){var spinner=$.timeEntry._getSpinnerTarget(event);var inst=$.data($.timeEntry._getInput(spinner),PROP_NAME);spinner.title=$.timeEntry._get(inst,'spinnerTexts')
[$.timeEntry._getSpinnerRegion(inst,event)];},_handleSpinner:function(event){var spinner=$.timeEntry._getSpinnerTarget(event);var input=$.timeEntry._getInput(spinner);if($.timeEntry._isDisabledTimeEntry(input)){return;}
if(input==$.timeEntry._blurredInput){$.timeEntry._lastInput=input;$.timeEntry._blurredInput=null;}
var inst=$.data(input,PROP_NAME);$.timeEntry._doFocus(input);var region=$.timeEntry._getSpinnerRegion(inst,event);$.timeEntry._changeSpinner(inst,spinner,region);$.timeEntry._actionSpinner(inst,region);$.timeEntry._timer=null;$.timeEntry._handlingSpinner=true;var spinnerRepeat=$.timeEntry._get(inst,'spinnerRepeat');if(region>=3&&spinnerRepeat[0]){$.timeEntry._timer=setTimeout(function(){$.timeEntry._repeatSpinner(inst,region);},spinnerRepeat[0]);$(spinner).one('mouseout',$.timeEntry._releaseSpinner).one('mouseup',$.timeEntry._releaseSpinner);}},_actionSpinner:function(inst,region){if(!inst.input.val()){$.timeEntry._parseTime(inst);}
switch(region){case 0:this._setTime(inst);break;case 1:this._changeField(inst,-1,false);break;case 2:this._changeField(inst,+1,false);break;case 3:this._adjustField(inst,+1);break;case 4:this._adjustField(inst,-1);break;}},_repeatSpinner:function(inst,region){if(!$.timeEntry._timer){return;}
$.timeEntry._lastInput=$.timeEntry._blurredInput;this._actionSpinner(inst,region);this._timer=setTimeout(function(){$.timeEntry._repeatSpinner(inst,region);},this._get(inst,'spinnerRepeat')[1]);},_releaseSpinner:function(event){clearTimeout($.timeEntry._timer);$.timeEntry._timer=null;},_endExpand:function(event){$.timeEntry._timer=null;var spinner=$.timeEntry._getSpinnerTarget(event);var input=$.timeEntry._getInput(spinner);var inst=$.data(input,PROP_NAME);$(spinner).remove();inst._expanded=false;},_endSpinner:function(event){$.timeEntry._timer=null;var spinner=$.timeEntry._getSpinnerTarget(event);var input=$.timeEntry._getInput(spinner);var inst=$.data(input,PROP_NAME);if(!$.timeEntry._isDisabledTimeEntry(input)){$.timeEntry._changeSpinner(inst,spinner,-1);}
if($.timeEntry._handlingSpinner){$.timeEntry._lastInput=$.timeEntry._blurredInput;}
if($.timeEntry._lastInput&&$.timeEntry._handlingSpinner){$.timeEntry._showField(inst);}
$.timeEntry._handlingSpinner=false;},_getSpinnerTarget:function(event){return event.target||event.srcElement;},_getSpinnerRegion:function(inst,event){var spinner=this._getSpinnerTarget(event);var pos=($.browser.opera||$.browser.safari?$.timeEntry._findPos(spinner):$(spinner).offset());var scrolled=($.browser.safari?$.timeEntry._findScroll(spinner):[document.documentElement.scrollLeft||document.body.scrollLeft,document.documentElement.scrollTop||document.body.scrollTop]);var spinnerIncDecOnly=this._get(inst,'spinnerIncDecOnly');var left=(spinnerIncDecOnly?99:event.clientX+scrolled[0]-
pos.left-($.browser.msie?2:0));var top=event.clientY+scrolled[1]-pos.top-($.browser.msie?2:0);var spinnerSize=this._get(inst,(inst._expanded?'spinnerBigSize':'spinnerSize'));var right=(spinnerIncDecOnly?99:spinnerSize[0]-1-left);var bottom=spinnerSize[1]-1-top;if(spinnerSize[2]>0&&Math.abs(left-right)<=spinnerSize[2]&&Math.abs(top-bottom)<=spinnerSize[2]){return 0;}
var min=Math.min(left,top,right,bottom);return(min==left?1:(min==right?2:(min==top?3:4)));},_changeSpinner:function(inst,spinner,region){$(spinner).css('background-position','-'+((region+1)*this._get(inst,(inst._expanded?'spinnerBigSize':'spinnerSize'))[0])+'px 0px');},_findPos:function(obj){var curLeft=curTop=0;if(obj.offsetParent){curLeft=obj.offsetLeft;curTop=obj.offsetTop;while(obj=obj.offsetParent){var origCurLeft=curLeft;curLeft+=obj.offsetLeft;if(curLeft<0){curLeft=origCurLeft;}
curTop+=obj.offsetTop;}}
return{left:curLeft,top:curTop};},_findScroll:function(obj){var isFixed=false;$(obj).parents().each(function(){isFixed|=$(this).css('position')=='fixed';});if(isFixed){return[0,0];}
var scrollLeft=obj.scrollLeft;var scrollTop=obj.scrollTop;while(obj=obj.parentNode){scrollLeft+=obj.scrollLeft||0;scrollTop+=obj.scrollTop||0;}
return[scrollLeft,scrollTop];},_get:function(inst,name){return(inst.options[name]!=null?inst.options[name]:$.timeEntry._defaults[name]);},_parseTime:function(inst){var currentTime=this._extractTime(inst);var showSeconds=this._get(inst,'showSeconds');if(currentTime){inst._selectedHour=currentTime[0];inst._selectedMinute=currentTime[1];inst._selectedSecond=currentTime[2];}
else{var now=this._constrainTime(inst);inst._selectedHour=now[0];inst._selectedMinute=now[1];inst._selectedSecond=(showSeconds?now[2]:0);}
inst._secondField=(showSeconds?2:-1);inst._ampmField=(this._get(inst,'show24Hours')?-1:(showSeconds?3:2));inst._lastChr='';inst._field=Math.max(0,Math.min(Math.max(1,inst._secondField,inst._ampmField),this._get(inst,'initialField')));if(inst.input.val()!=''){this._showTime(inst);}},_extractTime:function(inst){var value=inst.input.val();var separator=this._get(inst,'separator');var currentTime=value.split(separator);if(separator==''&&value!=''){currentTime[0]=value.substring(0,2);currentTime[1]=value.substring(2,4);currentTime[2]=value.substring(4,6);}
var ampmNames=this._get(inst,'ampmNames');var show24Hours=this._get(inst,'show24Hours');if(currentTime.length>=2){var isAM=!show24Hours&&(value.indexOf(ampmNames[0])>-1);var isPM=!show24Hours&&(value.indexOf(ampmNames[1])>-1);var hour=parseInt(currentTime[0],10);hour=(isNaN(hour)?0:hour);hour=((isAM||isPM)&&hour==12?0:hour)+(isPM?12:0);var minute=parseInt(currentTime[1],10);minute=(isNaN(minute)?0:minute);var second=(currentTime.length>=3?parseInt(currentTime[2],10):0);second=(isNaN(second)||!this._get(inst,'showSeconds')?0:second);return this._constrainTime(inst,[hour,minute,second]);}
return null;},_constrainTime:function(inst,fields){var specified=(fields!=null);if(!specified){var now=this._determineTime(this._get(inst,'defaultTime'))||new Date();fields=[now.getHours(),now.getMinutes(),now.getSeconds()];}
var reset=false;var timeSteps=this._get(inst,'timeSteps');for(var i=0;i<timeSteps.length;i++){if(reset){fields[i]=0;}
else if(timeSteps[i]>1){fields[i]=Math.round(fields[i]/timeSteps[i])*timeSteps[i];reset=true;}}
return fields;},_showTime:function(inst){var show24Hours=this._get(inst,'show24Hours');var separator=this._get(inst,'separator');var currentTime=(this._formatNumber(show24Hours?inst._selectedHour:((inst._selectedHour+11)%12)+1)+separator+
this._formatNumber(inst._selectedMinute)+
(this._get(inst,'showSeconds')?separator+
this._formatNumber(inst._selectedSecond):'')+
(show24Hours?'':this._get(inst,'ampmPrefix')+
this._get(inst,'ampmNames')[(inst._selectedHour<12?0:1)]));this._setValue(inst,currentTime);this._showField(inst);},_showField:function(inst){var input=inst.input[0];if(inst.input.is(':hidden')||$.timeEntry._lastInput!=input){return;}
var separator=this._get(inst,'separator');var fieldSize=separator.length+2;var start=(inst._field!=inst._ampmField?(inst._field*fieldSize):(inst._ampmField*fieldSize)-separator.length+this._get(inst,'ampmPrefix').length);var end=start+(inst._field!=inst._ampmField?2:this._get(inst,'ampmNames')[0].length);if(input.setSelectionRange){input.setSelectionRange(start,end);}
else if(input.createTextRange){var range=input.createTextRange();range.moveStart('character',start);range.moveEnd('character',end-inst.input.val().length);range.select();}
if(!input.disabled){input.focus();}},_formatNumber:function(value){return(value<10?'0':'')+value;},_setValue:function(inst,value){if(value!=inst.input.val()){inst.input.val(value).trigger('change');}},_changeField:function(inst,offset,moveOut){var atFirstLast=(inst.input.val()==''||inst._field==(offset==-1?0:Math.max(1,inst._secondField,inst._ampmField)));if(!atFirstLast){inst._field+=offset;}
this._showField(inst);inst._lastChr='';$.data(inst.input[0],PROP_NAME,inst);return(atFirstLast&&moveOut);},_adjustField:function(inst,offset){if(inst.input.val()==''){offset=0;}
var timeSteps=this._get(inst,'timeSteps');this._setTime(inst,new Date(0,0,0,inst._selectedHour+(inst._field==0?offset*timeSteps[0]:0)+
(inst._field==inst._ampmField?offset*12:0),inst._selectedMinute+(inst._field==1?offset*timeSteps[1]:0),inst._selectedSecond+(inst._field==inst._secondField?offset*timeSteps[2]:0)));},_setTime:function(inst,time){time=this._determineTime(time);var fields=this._constrainTime(inst,time?[time.getHours(),time.getMinutes(),time.getSeconds()]:null);time=new Date(0,0,0,fields[0],fields[1],fields[2]);var time=this._normaliseTime(time);var minTime=this._normaliseTime(this._determineTime(this._get(inst,'minTime')));var maxTime=this._normaliseTime(this._determineTime(this._get(inst,'maxTime')));time=(minTime&&time<minTime?minTime:(maxTime&&time>maxTime?maxTime:time));var beforeSetTime=this._get(inst,'beforeSetTime');if(beforeSetTime){time=beforeSetTime.apply(inst.input[0],[this._getTimeTimeEntry(inst.input[0]),time,minTime,maxTime]);}
inst._selectedHour=time.getHours();inst._selectedMinute=time.getMinutes();inst._selectedSecond=time.getSeconds();this._showTime(inst);$.data(inst.input[0],PROP_NAME,inst);},_determineTime:function(setting){var offsetNumeric=function(offset){var time=new Date();time.setTime(time.getTime()+offset*1000);return time;};var offsetString=function(offset){var time=new Date();var hour=time.getHours();var minute=time.getMinutes();var second=time.getSeconds();var pattern=/([+-]?[0-9]+)\s*(s|S|m|M|h|H)?/g;var matches=pattern.exec(offset);while(matches){switch(matches[2]||'s'){case's':case'S':second+=parseInt(matches[1],10);break;case'm':case'M':minute+=parseInt(matches[1],10);break;case'h':case'H':hour+=parseInt(matches[1],10);break;}
matches=pattern.exec(offset);}
time=new Date(0,0,10,hour,minute,second,0);if(/^!/.test(offset)){if(time.getDate()>10){time=new Date(0,0,10,23,59,59);}
else if(time.getDate()<10){time=new Date(0,0,10,0,0,0);}}
return time;};return(setting?(typeof setting=='string'?offsetString(setting):(typeof setting=='number'?offsetNumeric(setting):setting)):null);},_normaliseTime:function(time){if(!time){return null;}
time.setFullYear(1900);time.setMonth(0);time.setDate(0);return time;},_handleKeyPress:function(inst,chr){if(chr==this._get(inst,'separator')){this._changeField(inst,+1,false);}
else if(chr>='0'&&chr<='9'){var key=parseInt(chr,10);var value=parseInt(inst._lastChr+chr,10);var show24Hours=this._get(inst,'show24Hours');var hour=(inst._field!=0?inst._selectedHour:(show24Hours?(value<24?value:key):(value>=1&&value<=12?value:(key>0?key:inst._selectedHour))%12+
(inst._selectedHour>=12?12:0)));var minute=(inst._field!=1?inst._selectedMinute:(value<60?value:key));var second=(inst._field!=inst._secondField?inst._selectedSecond:(value<60?value:key));var fields=this._constrainTime(inst,[hour,minute,second]);this._setTime(inst,new Date(0,0,0,fields[0],fields[1],fields[2]));inst._lastChr=chr;}
else if(!this._get(inst,'show24Hours')){var ampmNames=this._get(inst,'ampmNames');if((chr==ampmNames[0].substring(0,1).toLowerCase()&&inst._selectedHour>=12)||(chr==ampmNames[1].substring(0,1).toLowerCase()&&inst._selectedHour<12)){var saveField=inst._field;inst._field=inst._ampmField;this._adjustField(inst,+1);inst._field=saveField;this._showField(inst);}}}});function extendRemove(target,props){$.extend(target,props);for(var name in props){if(props[name]==null){target[name]=null;}}
return target;}
$.fn.timeEntry=function(options){var otherArgs=Array.prototype.slice.call(arguments,1);if(typeof options=='string'&&(options=='isDisabled'||options=='getTime')){return $.timeEntry['_'+options+'TimeEntry'].apply($.timeEntry,[this[0]].concat(otherArgs));}
return this.each(function(){var nodeName=this.nodeName.toLowerCase();if(nodeName=='input'){if(typeof options=='string'){$.timeEntry['_'+options+'TimeEntry'].apply($.timeEntry,[this].concat(otherArgs));}
else{var inlineSettings=($.fn.metadata?$(this).metadata():{});$.timeEntry._connectTimeEntry(this,$.extend(inlineSettings,options));}}});};$.timeEntry=new TimeEntry();})(jQuery);(function($){$.fn.extend({mousewheel:function(f){if(!f.guid)f.guid=$.event.guid++;if(!$.event._mwCache)$.event._mwCache=[];return this.each(function(){if(this._mwHandlers)return this._mwHandlers.push(f);else this._mwHandlers=[];this._mwHandlers.push(f);var s=this;this._mwHandler=function(e){e=$.event.fix(e||window.event);$.extend(e,this._mwCursorPos||{});var delta=0,returnValue=true;if(e.wheelDelta)delta=e.wheelDelta/120;if(e.detail)delta=-e.detail/3;if(window.opera)delta=-e.wheelDelta;for(var i=0;i<s._mwHandlers.length;i++)
if(s._mwHandlers[i])
if(s._mwHandlers[i].call(s,e,delta)===false){returnValue=false;e.preventDefault();e.stopPropagation();}
return returnValue;};if($.browser.mozilla&&!this._mwFixCursorPos){this._mwFixCursorPos=function(e){this._mwCursorPos={pageX:e.pageX,pageY:e.pageY,clientX:e.clientX,clientY:e.clientY};};$(this).bind('mousemove',this._mwFixCursorPos);}
if(this.addEventListener)
if($.browser.mozilla)this.addEventListener('DOMMouseScroll',this._mwHandler,false);else this.addEventListener('mousewheel',this._mwHandler,false);else
this.onmousewheel=this._mwHandler;$.event._mwCache.push($(this));});},unmousewheel:function(f){return this.each(function(){if(f&&this._mwHandlers){for(var i=0;i<this._mwHandlers.length;i++)
if(this._mwHandlers[i]&&this._mwHandlers[i].guid==f.guid)
delete this._mwHandlers[i];}else{if($.browser.mozilla&&!this._mwFixCursorPos)
$(this).unbind('mousemove',this._mwFixCursorPos);if(this.addEventListener)
if($.browser.mozilla)this.removeEventListener('DOMMouseScroll',this._mwHandler,false);else this.removeEventListener('mousewheel',this._mwHandler,false);else
this.onmousewheel=null;this._mwHandlers=this._mwHandler=this._mwFixCursorPos=this._mwCursorPos=null;}});}});$(window).one('unload',function(){var els=$.event._mwCache||[];for(var i=0;i<els.length;i++)
els[i].unmousewheel();});})(jQuery);(function($){$.fn.toolTip=function(){var clickedElement=null;return this.each(function(){var text=$(this).children().find('div.crm-help').html();if(text!=undefined){$(this).bind('click',function(e){$(document).unbind('click');$("#toolTip").remove();if(clickedElement==$(this).children().attr('id')){clickedElement=null;return;}
var tipX=e.pageX+12;var tipY=e.pageY+12;$("body").append("<div id='toolTip' style='position: absolute; z-index: 100; display: none;'>"+text+"</div>");$("#toolTip").width("50%");$("#toolTip").fadeIn("medium");var tipWidth=$("#toolTip").outerWidth(true);var tipHeight=$("#toolTip").outerHeight(true);if(tipX+tipWidth>$(window).scrollLeft()+$(window).width())tipX=e.pageX-tipWidth;if($(window).height()+$(window).scrollTop()<tipY+tipHeight)tipY=(e.pageY>tipHeight)?e.pageY-tipHeight:tipY;$("#toolTip").css("left",tipX).css("top",tipY);clickedElement=cj(this).children().attr('id');}).bind('mouseout',function(){$(document).click(function(){$("#toolTip").hide();$(document).unbind('click');});});}});}})(jQuery);(function($){$.fn.dataTableSettings=[];var _aoSettings=$.fn.dataTableSettings;$.fn.dataTableExt={};var _oExt=$.fn.dataTableExt;_oExt.sVersion="1.6.2";_oExt.iApiIndex=0;_oExt.oApi={};_oExt.afnFiltering=[];_oExt.aoFeatures=[];_oExt.ofnSearch={};_oExt.afnSortData=[];_oExt.oStdClasses={"sPagePrevEnabled":"paginate_enabled_previous","sPagePrevDisabled":"paginate_disabled_previous","sPageNextEnabled":"paginate_enabled_next","sPageNextDisabled":"paginate_disabled_next","sPageJUINext":"","sPageJUIPrev":"","sPageButton":"paginate_button","sPageButtonActive":"paginate_active","sPageButtonStaticDisabled":"paginate_button","sPageFirst":"first","sPagePrevious":"previous","sPageNext":"next","sPageLast":"last","sStripOdd":"odd","sStripEven":"even","sRowEmpty":"dataTables_empty","sWrapper":"dataTables_wrapper","sFilter":"dataTables_filter","sInfo":"dataTables_info","sPaging":"dataTables_paginate paging_","sLength":"dataTables_length","sProcessing":"dataTables_processing","sSortAsc":"sorting_asc","sSortDesc":"sorting_desc","sSortable":"sorting","sSortableAsc":"sorting_asc_disabled","sSortableDesc":"sorting_desc_disabled","sSortableNone":"sorting_disabled","sSortColumn":"sorting_","sSortJUIAsc":"","sSortJUIDesc":"","sSortJUI":"","sSortJUIAscAllowed":"","sSortJUIDescAllowed":""};_oExt.oJUIClasses={"sPagePrevEnabled":"fg-button ui-state-default ui-corner-left","sPagePrevDisabled":"fg-button ui-state-default ui-corner-left ui-state-disabled","sPageNextEnabled":"fg-button ui-state-default ui-corner-right","sPageNextDisabled":"fg-button ui-state-default ui-corner-right ui-state-disabled","sPageJUINext":"ui-icon ui-icon-circle-arrow-e","sPageJUIPrev":"ui-icon ui-icon-circle-arrow-w","sPageButton":"fg-button ui-state-default","sPageButtonActive":"fg-button ui-state-default ui-state-disabled","sPageButtonStaticDisabled":"fg-button ui-state-default ui-state-disabled","sPageFirst":"first ui-corner-tl ui-corner-bl","sPagePrevious":"previous","sPageNext":"next","sPageLast":"last ui-corner-tr ui-corner-br","sStripOdd":"odd","sStripEven":"even","sRowEmpty":"dataTables_empty","sWrapper":"dataTables_wrapper","sFilter":"dataTables_filter","sInfo":"dataTables_info","sPaging":"dataTables_paginate fg-buttonset fg-buttonset-multi paging_","sLength":"dataTables_length","sProcessing":"dataTables_processing","sSortAsc":"ui-state-default","sSortDesc":"ui-state-default","sSortable":"ui-state-default","sSortableAsc":"ui-state-default","sSortableDesc":"ui-state-default","sSortableNone":"ui-state-default","sSortColumn":"sorting_","sSortJUIAsc":"css_right ui-icon ui-icon-triangle-1-n","sSortJUIDesc":"css_right ui-icon ui-icon-triangle-1-s","sSortJUI":"css_right ui-icon ui-icon-carat-2-n-s","sSortJUIAscAllowed":"css_right ui-icon ui-icon-carat-1-n","sSortJUIDescAllowed":"css_right ui-icon ui-icon-carat-1-s"};_oExt.oPagination={"two_button":{"fnInit":function(oSettings,nPaging,fnCallbackDraw)
{var nPrevious,nNext,nPreviousInner,nNextInner;if(!oSettings.bJUI)
{nPrevious=document.createElement('div');nNext=document.createElement('div');}
else
{nPrevious=document.createElement('a');nNext=document.createElement('a');nNextInner=document.createElement('span');nNextInner.className=oSettings.oClasses.sPageJUINext;nNext.appendChild(nNextInner);nPreviousInner=document.createElement('span');nPreviousInner.className=oSettings.oClasses.sPageJUIPrev;nPrevious.appendChild(nPreviousInner);}
nPrevious.className=oSettings.oClasses.sPagePrevDisabled;nNext.className=oSettings.oClasses.sPageNextDisabled;nPrevious.title=oSettings.oLanguage.oPaginate.sPrevious;nNext.title=oSettings.oLanguage.oPaginate.sNext;nPaging.appendChild(nPrevious);nPaging.appendChild(nNext);$(nPrevious).click(function(){if(oSettings.oApi._fnPageChange(oSettings,"previous"))
{fnCallbackDraw(oSettings);}});$(nNext).click(function(){if(oSettings.oApi._fnPageChange(oSettings,"next"))
{fnCallbackDraw(oSettings);}});$(nPrevious).bind('selectstart',function(){return false;});$(nNext).bind('selectstart',function(){return false;});if(oSettings.sTableId!==''&&typeof oSettings.aanFeatures.p=="undefined")
{nPaging.setAttribute('id',oSettings.sTableId+'_paginate');nPrevious.setAttribute('id',oSettings.sTableId+'_previous');nNext.setAttribute('id',oSettings.sTableId+'_next');}},"fnUpdate":function(oSettings,fnCallbackDraw)
{if(!oSettings.aanFeatures.p)
{return;}
var an=oSettings.aanFeatures.p;for(var i=0,iLen=an.length;i<iLen;i++)
{if(an[i].childNodes.length!==0)
{an[i].childNodes[0].className=(oSettings._iDisplayStart===0)?oSettings.oClasses.sPagePrevDisabled:oSettings.oClasses.sPagePrevEnabled;an[i].childNodes[1].className=(oSettings.fnDisplayEnd()==oSettings.fnRecordsDisplay())?oSettings.oClasses.sPageNextDisabled:oSettings.oClasses.sPageNextEnabled;}}}},"iFullNumbersShowPages":5,"full_numbers":{"fnInit":function(oSettings,nPaging,fnCallbackDraw)
{var nFirst=document.createElement('span');var nPrevious=document.createElement('span');var nList=document.createElement('span');var nNext=document.createElement('span');var nLast=document.createElement('span');nFirst.innerHTML=oSettings.oLanguage.oPaginate.sFirst;nPrevious.innerHTML=oSettings.oLanguage.oPaginate.sPrevious;nNext.innerHTML=oSettings.oLanguage.oPaginate.sNext;nLast.innerHTML=oSettings.oLanguage.oPaginate.sLast;var oClasses=oSettings.oClasses;nFirst.className=oClasses.sPageButton+" "+oClasses.sPageFirst;nPrevious.className=oClasses.sPageButton+" "+oClasses.sPagePrevious;nNext.className=oClasses.sPageButton+" "+oClasses.sPageNext;nLast.className=oClasses.sPageButton+" "+oClasses.sPageLast;nPaging.appendChild(nFirst);nPaging.appendChild(nPrevious);nPaging.appendChild(nList);nPaging.appendChild(nNext);nPaging.appendChild(nLast);$(nFirst).click(function(){if(oSettings.oApi._fnPageChange(oSettings,"first"))
{fnCallbackDraw(oSettings);}});$(nPrevious).click(function(){if(oSettings.oApi._fnPageChange(oSettings,"previous"))
{fnCallbackDraw(oSettings);}});$(nNext).click(function(){if(oSettings.oApi._fnPageChange(oSettings,"next"))
{fnCallbackDraw(oSettings);}});$(nLast).click(function(){if(oSettings.oApi._fnPageChange(oSettings,"last"))
{fnCallbackDraw(oSettings);}});$('span',nPaging).bind('mousedown',function(){return false;}).bind('selectstart',function(){return false;});if(oSettings.sTableId!==''&&typeof oSettings.aanFeatures.p=="undefined")
{nPaging.setAttribute('id',oSettings.sTableId+'_paginate');nFirst.setAttribute('id',oSettings.sTableId+'_first');nPrevious.setAttribute('id',oSettings.sTableId+'_previous');nNext.setAttribute('id',oSettings.sTableId+'_next');nLast.setAttribute('id',oSettings.sTableId+'_last');}},"fnUpdate":function(oSettings,fnCallbackDraw)
{if(!oSettings.aanFeatures.p)
{return;}
var iPageCount=_oExt.oPagination.iFullNumbersShowPages;var iPageCountHalf=Math.floor(iPageCount/2);var iPages=Math.ceil((oSettings.fnRecordsDisplay())/oSettings._iDisplayLength);var iCurrentPage=Math.ceil(oSettings._iDisplayStart/oSettings._iDisplayLength)+1;var sList="";var iStartButton,iEndButton,i,iLen;var oClasses=oSettings.oClasses;if(iPages<iPageCount)
{iStartButton=1;iEndButton=iPages;}
else
{if(iCurrentPage<=iPageCountHalf)
{iStartButton=1;iEndButton=iPageCount;}
else
{if(iCurrentPage>=(iPages-iPageCountHalf))
{iStartButton=iPages-iPageCount+1;iEndButton=iPages;}
else
{iStartButton=iCurrentPage-Math.ceil(iPageCount/2)+1;iEndButton=iStartButton+iPageCount-1;}}}
for(i=iStartButton;i<=iEndButton;i++)
{if(iCurrentPage!=i)
{sList+='<span class="'+oClasses.sPageButton+'">'+i+'</span>';}
else
{sList+='<span class="'+oClasses.sPageButtonActive+'">'+i+'</span>';}}
var an=oSettings.aanFeatures.p;var anButtons,anStatic,nPaginateList;var fnClick=function(){var iTarget=(this.innerHTML*1)-1;oSettings._iDisplayStart=iTarget*oSettings._iDisplayLength;fnCallbackDraw(oSettings);return false;};var fnFalse=function(){return false;};for(i=0,iLen=an.length;i<iLen;i++)
{if(an[i].childNodes.length===0)
{continue;}
nPaginateList=an[i].childNodes[2];nPaginateList.innerHTML=sList;$('span',nPaginateList).click(fnClick).bind('mousedown',fnFalse).bind('selectstart',fnFalse);anButtons=an[i].getElementsByTagName('span');anStatic=[anButtons[0],anButtons[1],anButtons[anButtons.length-2],anButtons[anButtons.length-1]];$(anStatic).removeClass(oClasses.sPageButton+" "+oClasses.sPageButtonActive+" "+oClasses.sPageButtonStaticDisabled);if(iCurrentPage==1)
{anStatic[0].className+=" "+oClasses.sPageButtonStaticDisabled;anStatic[1].className+=" "+oClasses.sPageButtonStaticDisabled;}
else
{anStatic[0].className+=" "+oClasses.sPageButton;anStatic[1].className+=" "+oClasses.sPageButton;}
if(iPages===0||iCurrentPage==iPages||oSettings._iDisplayLength==-1)
{anStatic[2].className+=" "+oClasses.sPageButtonStaticDisabled;anStatic[3].className+=" "+oClasses.sPageButtonStaticDisabled;}
else
{anStatic[2].className+=" "+oClasses.sPageButton;anStatic[3].className+=" "+oClasses.sPageButton;}}}}};_oExt.oSort={"string-asc":function(a,b)
{var x=a.toLowerCase();var y=b.toLowerCase();return((x<y)?-1:((x>y)?1:0));},"string-desc":function(a,b)
{var x=a.toLowerCase();var y=b.toLowerCase();return((x<y)?1:((x>y)?-1:0));},"html-asc":function(a,b)
{var x=a.replace(/<.*?>/g,"").toLowerCase();var y=b.replace(/<.*?>/g,"").toLowerCase();return((x<y)?-1:((x>y)?1:0));},"html-desc":function(a,b)
{var x=a.replace(/<.*?>/g,"").toLowerCase();var y=b.replace(/<.*?>/g,"").toLowerCase();return((x<y)?1:((x>y)?-1:0));},"date-asc":function(a,b)
{var x=Date.parse(a);var y=Date.parse(b);if(isNaN(x))
{x=Date.parse("01/01/1970 00:00:00");}
if(isNaN(y))
{y=Date.parse("01/01/1970 00:00:00");}
return x-y;},"date-desc":function(a,b)
{var x=Date.parse(a);var y=Date.parse(b);if(isNaN(x))
{x=Date.parse("01/01/1970 00:00:00");}
if(isNaN(y))
{y=Date.parse("01/01/1970 00:00:00");}
return y-x;},"numeric-asc":function(a,b)
{var x=a=="-"?0:a;var y=b=="-"?0:b;return x-y;},"numeric-desc":function(a,b)
{var x=a=="-"?0:a;var y=b=="-"?0:b;return y-x;}};_oExt.aTypes=[function(sData)
{if(typeof sData=='number')
{return'numeric';}
else if(typeof sData.charAt!='function')
{return null;}
var sValidFirstChars="0123456789-";var sValidChars="0123456789.";var Char;var bDecimal=false;Char=sData.charAt(0);if(sValidFirstChars.indexOf(Char)==-1)
{return null;}
for(var i=1;i<sData.length;i++)
{Char=sData.charAt(i);if(sValidChars.indexOf(Char)==-1)
{return null;}
if(Char==".")
{if(bDecimal)
{return null;}
bDecimal=true;}}
return'numeric';},function(sData)
{var iParse=Date.parse(sData);if(iParse!==null&&!isNaN(iParse))
{return'date';}
return null;}];_oExt._oExternConfig={"iNextUnique":0};$.fn.dataTable=function(oInit)
{function classSettings()
{this.fnRecordsTotal=function()
{if(this.oFeatures.bServerSide){return this._iRecordsTotal;}else{return this.aiDisplayMaster.length;}};this.fnRecordsDisplay=function()
{if(this.oFeatures.bServerSide){return this._iRecordsDisplay;}else{return this.aiDisplay.length;}};this.fnDisplayEnd=function()
{if(this.oFeatures.bServerSide){return this._iDisplayStart+this.aiDisplay.length;}else{return this._iDisplayEnd;}};this.sInstance=null;this.oFeatures={"bPaginate":true,"bLengthChange":true,"bFilter":true,"bSort":true,"bInfo":true,"bAutoWidth":true,"bProcessing":false,"bSortClasses":true,"bStateSave":false,"bServerSide":false};this.aanFeatures=[];this.oLanguage={"sProcessing":"Processing...","sLengthMenu":"Show _MENU_ entries","sZeroRecords":"No matching records found","sInfo":"Showing _START_ to _END_ of _TOTAL_ entries","sInfoEmpty":"Showing 0 to 0 of 0 entries","sInfoFiltered":"(filtered from _MAX_ total entries)","sInfoPostFix":"","sSearch":"Search:","sUrl":"","oPaginate":{"sFirst":"First","sPrevious":"Previous","sNext":"Next","sLast":"Last"}};this.aoData=[];this.aiDisplay=[];this.aiDisplayMaster=[];this.aoColumns=[];this.iNextId=0;this.asDataSearch=[];this.oPreviousSearch={"sSearch":"","bEscapeRegex":true};this.aoPreSearchCols=[];this.aaSorting=[[0,'asc',0]];this.aaSortingFixed=null;this.asStripClasses=[];this.fnRowCallback=null;this.fnHeaderCallback=null;this.fnFooterCallback=null;this.aoDrawCallback=[];this.fnInitComplete=null;this.sTableId="";this.nTable=null;this.iDefaultSortIndex=0;this.bInitialised=false;this.aoOpenRows=[];this.sDom='lfrtip';this.sPaginationType="two_button";this.iCookieDuration=60*60*2;this.sAjaxSource=null;this.bAjaxDataGet=true;this.fnServerData=$.getJSON;this.iServerDraw=0;this._iDisplayLength=10;this._iDisplayStart=0;this._iDisplayEnd=10;this._iRecordsTotal=0;this._iRecordsDisplay=0;this.bJUI=false;this.oClasses=_oExt.oStdClasses;this.bFiltered=false;this.bSorted=false;}
this.oApi={};this.fnDraw=function(bComplete)
{var oSettings=_fnSettingsFromNode(this[_oExt.iApiIndex]);if(typeof bComplete!='undefined'&&bComplete===false)
{_fnCalculateEnd(oSettings);_fnDraw(oSettings);}
else
{_fnReDraw(oSettings);}};this.fnFilter=function(sInput,iColumn,bEscapeRegex)
{var oSettings=_fnSettingsFromNode(this[_oExt.iApiIndex]);if(typeof bEscapeRegex=='undefined')
{bEscapeRegex=true;}
if(typeof iColumn=="undefined"||iColumn===null)
{_fnFilterComplete(oSettings,{"sSearch":sInput,"bEscapeRegex":bEscapeRegex},1);}
else
{oSettings.aoPreSearchCols[iColumn].sSearch=sInput;oSettings.aoPreSearchCols[iColumn].bEscapeRegex=bEscapeRegex;_fnFilterComplete(oSettings,oSettings.oPreviousSearch,1);}};this.fnSettings=function(nNode)
{return _fnSettingsFromNode(this[_oExt.iApiIndex]);};this.fnVersionCheck=function(sVersion)
{var fnZPad=function(Zpad,count)
{while(Zpad.length<count){Zpad+='0';}
return Zpad;};var aThis=_oExt.sVersion.split('.');var aThat=sVersion.split('.');var sThis='',sThat='';for(var i=0,iLen=aThat.length;i<iLen;i++)
{sThis+=fnZPad(aThis[i],3);sThat+=fnZPad(aThat[i],3);}
return parseInt(sThis,10)>=parseInt(sThat,10);};this.fnSort=function(aaSort)
{var oSettings=_fnSettingsFromNode(this[_oExt.iApiIndex]);oSettings.aaSorting=aaSort;_fnSort(oSettings);};this.fnSortListener=function(nNode,iColumn,fnCallback)
{_fnSortAttachListener(_fnSettingsFromNode(this[_oExt.iApiIndex]),nNode,iColumn,fnCallback);};this.fnAddData=function(mData,bRedraw)
{if(mData.length===0)
{return[];}
var aiReturn=[];var iTest;var oSettings=_fnSettingsFromNode(this[_oExt.iApiIndex]);if(typeof mData[0]=="object")
{for(var i=0;i<mData.length;i++)
{iTest=_fnAddData(oSettings,mData[i]);if(iTest==-1)
{return aiReturn;}
aiReturn.push(iTest);}}
else
{iTest=_fnAddData(oSettings,mData);if(iTest==-1)
{return aiReturn;}
aiReturn.push(iTest);}
oSettings.aiDisplay=oSettings.aiDisplayMaster.slice();_fnBuildSearchArray(oSettings,1);if(typeof bRedraw=='undefined'||bRedraw)
{_fnReDraw(oSettings);}
return aiReturn;};this.fnDeleteRow=function(mTarget,fnCallBack,bNullRow)
{var oSettings=_fnSettingsFromNode(this[_oExt.iApiIndex]);var i,iAODataIndex;iAODataIndex=(typeof mTarget=='object')?_fnNodeToDataIndex(oSettings,mTarget):mTarget;for(i=0;i<oSettings.aiDisplayMaster.length;i++)
{if(oSettings.aiDisplayMaster[i]==iAODataIndex)
{oSettings.aiDisplayMaster.splice(i,1);break;}}
for(i=0;i<oSettings.aiDisplay.length;i++)
{if(oSettings.aiDisplay[i]==iAODataIndex)
{oSettings.aiDisplay.splice(i,1);break;}}
_fnBuildSearchArray(oSettings,1);if(typeof fnCallBack=="function")
{fnCallBack.call(this);}
if(oSettings._iDisplayStart>=oSettings.aiDisplay.length)
{oSettings._iDisplayStart-=oSettings._iDisplayLength;if(oSettings._iDisplayStart<0)
{oSettings._iDisplayStart=0;}}
_fnCalculateEnd(oSettings);_fnDraw(oSettings);var aData=oSettings.aoData[iAODataIndex]._aData.slice();if(typeof bNullRow!="undefined"&&bNullRow===true)
{oSettings.aoData[iAODataIndex]=null;}
return aData;};this.fnClearTable=function(bRedraw)
{var oSettings=_fnSettingsFromNode(this[_oExt.iApiIndex]);_fnClearTable(oSettings);if(typeof bRedraw=='undefined'||bRedraw)
{_fnDraw(oSettings);}};this.fnOpen=function(nTr,sHtml,sClass)
{var oSettings=_fnSettingsFromNode(this[_oExt.iApiIndex]);this.fnClose(nTr);var nNewRow=document.createElement("tr");var nNewCell=document.createElement("td");nNewRow.appendChild(nNewCell);nNewCell.className=sClass;nNewCell.colSpan=_fnVisbleColumns(oSettings);nNewCell.innerHTML=sHtml;var nTrs=$('tbody tr',oSettings.nTable);if($.inArray(nTr,nTrs)!=-1)
{$(nNewRow).insertAfter(nTr);}
if(!oSettings.oFeatures.bServerSide)
{oSettings.aoOpenRows.push({"nTr":nNewRow,"nParent":nTr});}
return nNewRow;};this.fnClose=function(nTr)
{var oSettings=_fnSettingsFromNode(this[_oExt.iApiIndex]);for(var i=0;i<oSettings.aoOpenRows.length;i++)
{if(oSettings.aoOpenRows[i].nParent==nTr)
{var nTrParent=oSettings.aoOpenRows[i].nTr.parentNode;if(nTrParent)
{nTrParent.removeChild(oSettings.aoOpenRows[i].nTr);}
oSettings.aoOpenRows.splice(i,1);return 0;}}
return 1;};this.fnGetData=function(mRow)
{var oSettings=_fnSettingsFromNode(this[_oExt.iApiIndex]);if(typeof mRow!='undefined')
{var iRow=(typeof mRow=='object')?_fnNodeToDataIndex(oSettings,mRow):mRow;return oSettings.aoData[iRow]._aData;}
return _fnGetDataMaster(oSettings);};this.fnGetNodes=function(iRow)
{var oSettings=_fnSettingsFromNode(this[_oExt.iApiIndex]);if(typeof iRow!='undefined')
{return oSettings.aoData[iRow].nTr;}
return _fnGetTrNodes(oSettings);};this.fnGetPosition=function(nNode)
{var oSettings=_fnSettingsFromNode(this[_oExt.iApiIndex]);var i;if(nNode.nodeName=="TR")
{return _fnNodeToDataIndex(oSettings,nNode);}
else if(nNode.nodeName=="TD")
{var iDataIndex=_fnNodeToDataIndex(oSettings,nNode.parentNode);var iCorrector=0;for(var j=0;j<oSettings.aoColumns.length;j++)
{if(oSettings.aoColumns[j].bVisible)
{if(oSettings.aoData[iDataIndex].nTr.getElementsByTagName('td')[j-iCorrector]==nNode)
{return[iDataIndex,j-iCorrector,j];}}
else
{iCorrector++;}}}
return null;};this.fnUpdate=function(mData,mRow,iColumn,bRedraw)
{var oSettings=_fnSettingsFromNode(this[_oExt.iApiIndex]);var iVisibleColumn;var sDisplay;var iRow=(typeof mRow=='object')?_fnNodeToDataIndex(oSettings,mRow):mRow;if(typeof mData!='object')
{sDisplay=mData;oSettings.aoData[iRow]._aData[iColumn]=sDisplay;if(oSettings.aoColumns[iColumn].fnRender!==null)
{sDisplay=oSettings.aoColumns[iColumn].fnRender({"iDataRow":iRow,"iDataColumn":iColumn,"aData":oSettings.aoData[iRow]._aData,"oSettings":oSettings});if(oSettings.aoColumns[iColumn].bUseRendered)
{oSettings.aoData[iRow]._aData[iColumn]=sDisplay;}}
iVisibleColumn=_fnColumnIndexToVisible(oSettings,iColumn);if(iVisibleColumn!==null)
{oSettings.aoData[iRow].nTr.getElementsByTagName('td')[iVisibleColumn].innerHTML=sDisplay;}}
else
{if(mData.length!=oSettings.aoColumns.length)
{alert('DataTables warning: An array passed to fnUpdate must have the same number of '+'columns as the table in question - in this case '+oSettings.aoColumns.length);return 1;}
for(var i=0;i<mData.length;i++)
{sDisplay=mData[i];oSettings.aoData[iRow]._aData[i]=sDisplay;if(oSettings.aoColumns[i].fnRender!==null)
{sDisplay=oSettings.aoColumns[i].fnRender({"iDataRow":iRow,"iDataColumn":i,"aData":oSettings.aoData[iRow]._aData,"oSettings":oSettings});if(oSettings.aoColumns[i].bUseRendered)
{oSettings.aoData[iRow]._aData[i]=sDisplay;}}
iVisibleColumn=_fnColumnIndexToVisible(oSettings,i);if(iVisibleColumn!==null)
{oSettings.aoData[iRow].nTr.getElementsByTagName('td')[iVisibleColumn].innerHTML=sDisplay;}}}
_fnBuildSearchArray(oSettings,1);if(typeof bRedraw!='undefined'&&bRedraw)
{_fnReDraw(oSettings);}
return 0;};this.fnSetColumnVis=function(iCol,bShow)
{var oSettings=_fnSettingsFromNode(this[_oExt.iApiIndex]);var i,iLen;var iColumns=oSettings.aoColumns.length;var nTd,anTds;if(oSettings.aoColumns[iCol].bVisible==bShow)
{return;}
var nTrHead=$('thead:eq(0)>tr',oSettings.nTable)[0];var nTrFoot=$('tfoot:eq(0)>tr',oSettings.nTable)[0];var anTheadTh=[];var anTfootTh=[];for(i=0;i<iColumns;i++)
{anTheadTh.push(oSettings.aoColumns[i].nTh);anTfootTh.push(oSettings.aoColumns[i].nTf);}
if(bShow)
{var iInsert=0;for(i=0;i<iCol;i++)
{if(oSettings.aoColumns[i].bVisible)
{iInsert++;}}
if(iInsert>=_fnVisbleColumns(oSettings))
{nTrHead.appendChild(anTheadTh[iCol]);if(nTrFoot)
{nTrFoot.appendChild(anTfootTh[iCol]);}
for(i=0,iLen=oSettings.aoData.length;i<iLen;i++)
{nTd=oSettings.aoData[i]._anHidden[iCol];oSettings.aoData[i].nTr.appendChild(nTd);}}
else
{var iBefore;for(i=iCol;i<iColumns;i++)
{iBefore=_fnColumnIndexToVisible(oSettings,i);if(iBefore!==null)
{break;}}
nTrHead.insertBefore(anTheadTh[iCol],nTrHead.getElementsByTagName('th')[iBefore]);if(nTrFoot)
{nTrFoot.insertBefore(anTfootTh[iCol],nTrFoot.getElementsByTagName('th')[iBefore]);}
anTds=_fnGetTdNodes(oSettings);for(i=0,iLen=oSettings.aoData.length;i<iLen;i++)
{nTd=oSettings.aoData[i]._anHidden[iCol];oSettings.aoData[i].nTr.insertBefore(nTd,$('>td:eq('+iBefore+')',oSettings.aoData[i].nTr)[0]);}}
oSettings.aoColumns[iCol].bVisible=true;}
else
{nTrHead.removeChild(anTheadTh[iCol]);if(nTrFoot)
{nTrFoot.removeChild(anTfootTh[iCol]);}
anTds=_fnGetTdNodes(oSettings);for(i=0,iLen=oSettings.aoData.length;i<iLen;i++)
{nTd=anTds[(i*oSettings.aoColumns.length)+iCol];oSettings.aoData[i]._anHidden[iCol]=nTd;nTd.parentNode.removeChild(nTd);}
oSettings.aoColumns[iCol].bVisible=false;}
for(i=0,iLen=oSettings.aoOpenRows.length;i<iLen;i++)
{oSettings.aoOpenRows[i].nTr.colSpan=_fnVisbleColumns(oSettings);}
_fnSaveState(oSettings);};this.fnPageChange=function(sAction,bRedraw)
{var oSettings=_fnSettingsFromNode(this[_oExt.iApiIndex]);_fnPageChange(oSettings,sAction);_fnCalculateEnd(oSettings);if(typeof bRedraw=='undefined'||bRedraw)
{_fnDraw(oSettings);}};function _fnExternApiFunc(sFunc)
{return function(){var aArgs=[_fnSettingsFromNode(this[_oExt.iApiIndex])].concat(Array.prototype.slice.call(arguments));return _oExt.oApi[sFunc].apply(this,aArgs);};}
for(var sFunc in _oExt.oApi)
{if(sFunc)
{this[sFunc]=_fnExternApiFunc(sFunc);}}
function _fnInitalise(oSettings)
{if(oSettings.bInitialised===false)
{setTimeout(function(){_fnInitalise(oSettings);},200);return;}
_fnAddOptionsHtml(oSettings);_fnDrawHead(oSettings);if(oSettings.oFeatures.bSort)
{_fnSort(oSettings,false);_fnSortingClasses(oSettings);}
else
{oSettings.aiDisplay=oSettings.aiDisplayMaster.slice();_fnCalculateEnd(oSettings);_fnDraw(oSettings);}
if(oSettings.sAjaxSource!==null&&!oSettings.oFeatures.bServerSide)
{_fnProcessingDisplay(oSettings,true);oSettings.fnServerData(oSettings.sAjaxSource,null,function(json){for(var i=0;i<json.aaData.length;i++)
{_fnAddData(oSettings,json.aaData[i]);}
oSettings.iInitDisplayStart=oSettings._iDisplayStart;if(oSettings.oFeatures.bSort)
{_fnSort(oSettings);}
else
{oSettings.aiDisplay=oSettings.aiDisplayMaster.slice();_fnCalculateEnd(oSettings);_fnDraw(oSettings);}
_fnProcessingDisplay(oSettings,false);if(typeof oSettings.fnInitComplete=='function')
{oSettings.fnInitComplete(oSettings,json);}});return;}
if(typeof oSettings.fnInitComplete=='function')
{oSettings.fnInitComplete(oSettings);}
if(!oSettings.oFeatures.bServerSide)
{_fnProcessingDisplay(oSettings,false);}}
function _fnLanguageProcess(oSettings,oLanguage,bInit)
{_fnMap(oSettings.oLanguage,oLanguage,'sProcessing');_fnMap(oSettings.oLanguage,oLanguage,'sLengthMenu');_fnMap(oSettings.oLanguage,oLanguage,'sZeroRecords');_fnMap(oSettings.oLanguage,oLanguage,'sInfo');_fnMap(oSettings.oLanguage,oLanguage,'sInfoEmpty');_fnMap(oSettings.oLanguage,oLanguage,'sInfoFiltered');_fnMap(oSettings.oLanguage,oLanguage,'sInfoPostFix');_fnMap(oSettings.oLanguage,oLanguage,'sSearch');if(typeof oLanguage.oPaginate!='undefined')
{_fnMap(oSettings.oLanguage.oPaginate,oLanguage.oPaginate,'sFirst');_fnMap(oSettings.oLanguage.oPaginate,oLanguage.oPaginate,'sPrevious');_fnMap(oSettings.oLanguage.oPaginate,oLanguage.oPaginate,'sNext');_fnMap(oSettings.oLanguage.oPaginate,oLanguage.oPaginate,'sLast');}
if(bInit)
{_fnInitalise(oSettings);}}
function _fnAddColumn(oSettings,oOptions,nTh)
{oSettings.aoColumns[oSettings.aoColumns.length++]={"sType":null,"_bAutoType":true,"bVisible":true,"bSearchable":true,"bSortable":true,"asSorting":['asc','desc'],"sSortingClass":oSettings.oClasses.sSortable,"sSortingClassJUI":oSettings.oClasses.sSortJUI,"sTitle":nTh?nTh.innerHTML:'',"sName":'',"sWidth":null,"sClass":null,"fnRender":null,"bUseRendered":true,"iDataSort":oSettings.aoColumns.length-1,"sSortDataType":'std',"nTh":nTh?nTh:document.createElement('th'),"nTf":null};var iLength=oSettings.aoColumns.length-1;var oCol=oSettings.aoColumns[iLength];if(typeof oOptions!='undefined'&&oOptions!==null)
{if(typeof oOptions.sType!='undefined')
{oCol.sType=oOptions.sType;oCol._bAutoType=false;}
_fnMap(oCol,oOptions,"bVisible");_fnMap(oCol,oOptions,"bSearchable");_fnMap(oCol,oOptions,"bSortable");_fnMap(oCol,oOptions,"sTitle");_fnMap(oCol,oOptions,"sName");_fnMap(oCol,oOptions,"sWidth");_fnMap(oCol,oOptions,"sClass");_fnMap(oCol,oOptions,"fnRender");_fnMap(oCol,oOptions,"bUseRendered");_fnMap(oCol,oOptions,"iDataSort");_fnMap(oCol,oOptions,"asSorting");_fnMap(oCol,oOptions,"sSortDataType");}
if(!oSettings.oFeatures.bSort)
{oCol.bSortable=false;}
if(!oCol.bSortable||($.inArray('asc',oCol.asSorting)==-1&&$.inArray('desc',oCol.asSorting)==-1))
{oCol.sSortingClass=oSettings.oClasses.sSortableNone;oCol.sSortingClassJUI="";}
else if($.inArray('asc',oCol.asSorting)!=-1&&$.inArray('desc',oCol.asSorting)==-1)
{oCol.sSortingClass=oSettings.oClasses.sSortableAsc;oCol.sSortingClassJUI=oSettings.oClasses.sSortJUIAscAllowed;}
else if($.inArray('asc',oCol.asSorting)==-1&&$.inArray('desc',oCol.asSorting)!=-1)
{oCol.sSortingClass=oSettings.oClasses.sSortableDesc;oCol.sSortingClassJUI=oSettings.oClasses.sSortJUIDescAllowed;}
if(typeof oSettings.aoPreSearchCols[iLength]=='undefined'||oSettings.aoPreSearchCols[iLength]===null)
{oSettings.aoPreSearchCols[iLength]={"sSearch":"","bEscapeRegex":true};}
else if(typeof oSettings.aoPreSearchCols[iLength].bEscapeRegex=='undefined')
{oSettings.aoPreSearchCols[iLength].bEscapeRegex=true;}}
function _fnAddData(oSettings,aData)
{if(aData.length!=oSettings.aoColumns.length)
{alert("DataTables warning: Added data does not match known number of columns");return-1;}
var iThisIndex=oSettings.aoData.length;oSettings.aoData.push({"nTr":document.createElement('tr'),"_iId":oSettings.iNextId++,"_aData":aData.slice(),"_anHidden":[],"_sRowStripe":''});var nTd,sThisType;for(var i=0;i<aData.length;i++)
{nTd=document.createElement('td');if(typeof oSettings.aoColumns[i].fnRender=='function')
{var sRendered=oSettings.aoColumns[i].fnRender({"iDataRow":iThisIndex,"iDataColumn":i,"aData":aData,"oSettings":oSettings});nTd.innerHTML=sRendered;if(oSettings.aoColumns[i].bUseRendered)
{oSettings.aoData[iThisIndex]._aData[i]=sRendered;}}
else
{nTd.innerHTML=aData[i];}
if(oSettings.aoColumns[i].sClass!==null)
{nTd.className=oSettings.aoColumns[i].sClass;}
if(oSettings.aoColumns[i]._bAutoType&&oSettings.aoColumns[i].sType!='string')
{sThisType=_fnDetectType(oSettings.aoData[iThisIndex]._aData[i]);if(oSettings.aoColumns[i].sType===null)
{oSettings.aoColumns[i].sType=sThisType;}
else if(oSettings.aoColumns[i].sType!=sThisType)
{oSettings.aoColumns[i].sType='string';}}
if(oSettings.aoColumns[i].bVisible)
{oSettings.aoData[iThisIndex].nTr.appendChild(nTd);}
else
{oSettings.aoData[iThisIndex]._anHidden[i]=nTd;}}
oSettings.aiDisplayMaster.push(iThisIndex);return iThisIndex;}
function _fnGatherData(oSettings)
{var iLoop,i,iLen,j,jLen,jInner,nTds,nTrs,nTd,aLocalData,iThisIndex,iRow,iRows,iColumn,iColumns;if(oSettings.sAjaxSource===null)
{nTrs=oSettings.nTable.getElementsByTagName('tbody')[0].childNodes;for(i=0,iLen=nTrs.length;i<iLen;i++)
{if(nTrs[i].nodeName=="TR")
{iThisIndex=oSettings.aoData.length;oSettings.aoData.push({"nTr":nTrs[i],"_iId":oSettings.iNextId++,"_aData":[],"_anHidden":[],"_sRowStripe":''});oSettings.aiDisplayMaster.push(iThisIndex);aLocalData=oSettings.aoData[iThisIndex]._aData;nTds=nTrs[i].childNodes;jInner=0;for(j=0,jLen=nTds.length;j<jLen;j++)
{if(nTds[j].nodeName=="TD")
{aLocalData[jInner]=nTds[j].innerHTML;jInner++;}}}}}
nTrs=_fnGetTrNodes(oSettings);nTds=[];for(i=0,iLen=nTrs.length;i<iLen;i++)
{for(j=0,jLen=nTrs[i].childNodes.length;j<jLen;j++)
{nTd=nTrs[i].childNodes[j];if(nTd.nodeName=="TD")
{nTds.push(nTd);}}}
if(nTds.length!=nTrs.length*oSettings.aoColumns.length)
{alert("DataTables warning: Unexpected number of TD elements. Expected "+
(nTrs.length*oSettings.aoColumns.length)+" and got "+nTds.length+". DataTables does "+"not support rowspan / colspan in the table body, and there must be one cell for each "+"row/column combination.");}
for(iColumn=0,iColumns=oSettings.aoColumns.length;iColumn<iColumns;iColumn++)
{if(oSettings.aoColumns[iColumn].sTitle===null)
{oSettings.aoColumns[iColumn].sTitle=oSettings.aoColumns[iColumn].nTh.innerHTML;}
var
bAutoType=oSettings.aoColumns[iColumn]._bAutoType,bRender=typeof oSettings.aoColumns[iColumn].fnRender=='function',bClass=oSettings.aoColumns[iColumn].sClass!==null,bVisible=oSettings.aoColumns[iColumn].bVisible,nCell,sThisType,sRendered;if(bAutoType||bRender||bClass||!bVisible)
{for(iRow=0,iRows=oSettings.aoData.length;iRow<iRows;iRow++)
{nCell=nTds[(iRow*iColumns)+iColumn];if(bAutoType)
{if(oSettings.aoColumns[iColumn].sType!='string')
{sThisType=_fnDetectType(oSettings.aoData[iRow]._aData[iColumn]);if(oSettings.aoColumns[iColumn].sType===null)
{oSettings.aoColumns[iColumn].sType=sThisType;}
else if(oSettings.aoColumns[iColumn].sType!=sThisType)
{oSettings.aoColumns[iColumn].sType='string';}}}
if(bRender)
{sRendered=oSettings.aoColumns[iColumn].fnRender({"iDataRow":iRow,"iDataColumn":iColumn,"aData":oSettings.aoData[iRow]._aData,"oSettings":oSettings});nCell.innerHTML=sRendered;if(oSettings.aoColumns[iColumn].bUseRendered)
{oSettings.aoData[iRow]._aData[iColumn]=sRendered;}}
if(bClass)
{nCell.className+=' '+oSettings.aoColumns[iColumn].sClass;}
if(!bVisible)
{oSettings.aoData[iRow]._anHidden[iColumn]=nCell;nCell.parentNode.removeChild(nCell);}}}}}
function _fnDrawHead(oSettings)
{var i,nTh,iLen;var iThs=oSettings.nTable.getElementsByTagName('thead')[0].getElementsByTagName('th').length;var iCorrector=0;if(iThs!==0)
{for(i=0,iLen=oSettings.aoColumns.length;i<iLen;i++)
{nTh=oSettings.aoColumns[i].nTh;if(oSettings.aoColumns[i].bVisible)
{if(oSettings.aoColumns[i].sWidth!==null)
{nTh.style.width=oSettings.aoColumns[i].sWidth;}
if(oSettings.aoColumns[i].sTitle!=nTh.innerHTML)
{nTh.innerHTML=oSettings.aoColumns[i].sTitle;}}
else
{nTh.parentNode.removeChild(nTh);iCorrector++;}}}
else
{var nTr=document.createElement("tr");for(i=0,iLen=oSettings.aoColumns.length;i<iLen;i++)
{nTh=oSettings.aoColumns[i].nTh;nTh.innerHTML=oSettings.aoColumns[i].sTitle;if(oSettings.aoColumns[i].bVisible)
{if(oSettings.aoColumns[i].sClass!==null)
{nTh.className=oSettings.aoColumns[i].sClass;}
if(oSettings.aoColumns[i].sWidth!==null)
{nTh.style.width=oSettings.aoColumns[i].sWidth;}
nTr.appendChild(nTh);}}
$('thead:eq(0)',oSettings.nTable).html('')[0].appendChild(nTr);}
if(oSettings.bJUI)
{for(i=0,iLen=oSettings.aoColumns.length;i<iLen;i++)
{oSettings.aoColumns[i].nTh.insertBefore(document.createElement('span'),oSettings.aoColumns[i].nTh.firstChild);}}
if(oSettings.oFeatures.bSort)
{for(i=0;i<oSettings.aoColumns.length;i++)
{if(oSettings.aoColumns[i].bSortable!==false)
{_fnSortAttachListener(oSettings,oSettings.aoColumns[i].nTh,i);}
else
{$(oSettings.aoColumns[i].nTh).addClass(oSettings.oClasses.sSortableNone);}}
$('thead:eq(0) th',oSettings.nTable).mousedown(function(e){if(e.shiftKey)
{this.onselectstart=function(){return false;};return false;}});}
var nTfoot=oSettings.nTable.getElementsByTagName('tfoot');if(nTfoot.length!==0)
{iCorrector=0;var nTfs=nTfoot[0].getElementsByTagName('th');for(i=0,iLen=nTfs.length;i<iLen;i++)
{oSettings.aoColumns[i].nTf=nTfs[i-iCorrector];if(!oSettings.aoColumns[i].bVisible)
{nTfs[i-iCorrector].parentNode.removeChild(nTfs[i-iCorrector]);iCorrector++;}}}}
function _fnDraw(oSettings)
{var i,iLen;var anRows=[];var iRowCount=0;var bRowError=false;var iStrips=oSettings.asStripClasses.length;var iOpenRows=oSettings.aoOpenRows.length;if(oSettings.oFeatures.bServerSide&&!_fnAjaxUpdate(oSettings))
{return;}
if(typeof oSettings.iInitDisplayStart!='undefined'&&oSettings.iInitDisplayStart!=-1)
{oSettings._iDisplayStart=(oSettings.iInitDisplayStart>=oSettings.fnRecordsDisplay())?0:oSettings.iInitDisplayStart;oSettings.iInitDisplayStart=-1;_fnCalculateEnd(oSettings);}
if(oSettings.aiDisplay.length!==0)
{var iStart=oSettings._iDisplayStart;var iEnd=oSettings._iDisplayEnd;if(oSettings.oFeatures.bServerSide)
{iStart=0;iEnd=oSettings.aoData.length;}
for(var j=iStart;j<iEnd;j++)
{var aoData=oSettings.aoData[oSettings.aiDisplay[j]];var nRow=aoData.nTr;if(iStrips!==0)
{var sStrip=oSettings.asStripClasses[iRowCount%iStrips];if(aoData._sRowStripe!=sStrip)
{$(nRow).removeClass(aoData._sRowStripe).addClass(sStrip);aoData._sRowStripe=sStrip;}}
if(typeof oSettings.fnRowCallback=="function")
{nRow=oSettings.fnRowCallback(nRow,oSettings.aoData[oSettings.aiDisplay[j]]._aData,iRowCount,j);if(!nRow&&!bRowError)
{alert("DataTables warning: A node was not returned by fnRowCallback");bRowError=true;}}
anRows.push(nRow);iRowCount++;if(iOpenRows!==0)
{for(var k=0;k<iOpenRows;k++)
{if(nRow==oSettings.aoOpenRows[k].nParent)
{anRows.push(oSettings.aoOpenRows[k].nTr);}}}}}
else
{anRows[0]=document.createElement('tr');if(typeof oSettings.asStripClasses[0]!='undefined')
{anRows[0].className=oSettings.asStripClasses[0];}
var nTd=document.createElement('td');nTd.setAttribute('valign',"top");nTd.colSpan=oSettings.aoColumns.length;nTd.className=oSettings.oClasses.sRowEmpty;nTd.innerHTML=oSettings.oLanguage.sZeroRecords;anRows[iRowCount].appendChild(nTd);}
if(typeof oSettings.fnHeaderCallback=='function')
{oSettings.fnHeaderCallback($('thead:eq(0)>tr',oSettings.nTable)[0],_fnGetDataMaster(oSettings),oSettings._iDisplayStart,oSettings.fnDisplayEnd(),oSettings.aiDisplay);}
if(typeof oSettings.fnFooterCallback=='function')
{oSettings.fnFooterCallback($('tfoot:eq(0)>tr',oSettings.nTable)[0],_fnGetDataMaster(oSettings),oSettings._iDisplayStart,oSettings.fnDisplayEnd(),oSettings.aiDisplay);}
var nBody=oSettings.nTable.getElementsByTagName('tbody');if(nBody[0])
{var nTrs=nBody[0].childNodes;for(i=nTrs.length-1;i>=0;i--)
{nTrs[i].parentNode.removeChild(nTrs[i]);}
for(i=0,iLen=anRows.length;i<iLen;i++)
{nBody[0].appendChild(anRows[i]);}}
for(i=0,iLen=oSettings.aoDrawCallback.length;i<iLen;i++)
{oSettings.aoDrawCallback[i].fn(oSettings);}
oSettings.bSorted=false;oSettings.bFiltered=false;if(typeof oSettings._bInitComplete=="undefined")
{oSettings._bInitComplete=true;if(oSettings.oFeatures.bAutoWidth&&oSettings.nTable.offsetWidth!==0)
{oSettings.nTable.style.width=oSettings.nTable.offsetWidth+"px";}}}
function _fnReDraw(oSettings)
{if(oSettings.oFeatures.bSort)
{_fnSort(oSettings,oSettings.oPreviousSearch);}
else if(oSettings.oFeatures.bFilter)
{_fnFilterComplete(oSettings,oSettings.oPreviousSearch);}
else
{_fnCalculateEnd(oSettings);_fnDraw(oSettings);}}
function _fnAjaxUpdate(oSettings)
{if(oSettings.bAjaxDataGet)
{_fnProcessingDisplay(oSettings,true);var iColumns=oSettings.aoColumns.length;var aoData=[];var i;oSettings.iServerDraw++;aoData.push({"name":"sEcho","value":oSettings.iServerDraw});aoData.push({"name":"iColumns","value":iColumns});aoData.push({"name":"sColumns","value":_fnColumnOrdering(oSettings)});aoData.push({"name":"iDisplayStart","value":oSettings._iDisplayStart});aoData.push({"name":"iDisplayLength","value":oSettings.oFeatures.bPaginate!==false?oSettings._iDisplayLength:-1});if(oSettings.oFeatures.bFilter!==false)
{aoData.push({"name":"sSearch","value":oSettings.oPreviousSearch.sSearch});aoData.push({"name":"bEscapeRegex","value":oSettings.oPreviousSearch.bEscapeRegex});for(i=0;i<iColumns;i++)
{aoData.push({"name":"sSearch_"+i,"value":oSettings.aoPreSearchCols[i].sSearch});aoData.push({"name":"bEscapeRegex_"+i,"value":oSettings.aoPreSearchCols[i].bEscapeRegex});aoData.push({"name":"bSearchable_"+i,"value":oSettings.aoColumns[i].bSearchable});}}
if(oSettings.oFeatures.bSort!==false)
{var iFixed=oSettings.aaSortingFixed!==null?oSettings.aaSortingFixed.length:0;var iUser=oSettings.aaSorting.length;aoData.push({"name":"iSortingCols","value":iFixed+iUser});for(i=0;i<iFixed;i++)
{aoData.push({"name":"iSortCol_"+i,"value":oSettings.aaSortingFixed[i][0]});aoData.push({"name":"sSortDir_"+i,"value":oSettings.aaSortingFixed[i][1]});}
for(i=0;i<iUser;i++)
{aoData.push({"name":"iSortCol_"+(i+iFixed),"value":oSettings.aaSorting[i][0]});aoData.push({"name":"sSortDir_"+(i+iFixed),"value":oSettings.aaSorting[i][1]});}
for(i=0;i<iColumns;i++)
{aoData.push({"name":"bSortable_"+i,"value":oSettings.aoColumns[i].bSortable});}}
oSettings.fnServerData(oSettings.sAjaxSource,aoData,function(json){_fnAjaxUpdateDraw(oSettings,json);});return false;}
else
{return true;}}
function _fnAjaxUpdateDraw(oSettings,json)
{if(typeof json.sEcho!='undefined')
{if(json.sEcho*1<oSettings.iServerDraw)
{return;}
else
{oSettings.iServerDraw=json.sEcho*1;}}
_fnClearTable(oSettings);oSettings._iRecordsTotal=json.iTotalRecords;oSettings._iRecordsDisplay=json.iTotalDisplayRecords;var sOrdering=_fnColumnOrdering(oSettings);var bReOrder=(typeof json.sColumns!='undefined'&&sOrdering!==""&&json.sColumns!=sOrdering);if(bReOrder)
{var aiIndex=_fnReOrderIndex(oSettings,json.sColumns);}
for(var i=0,iLen=json.aaData.length;i<iLen;i++)
{if(bReOrder)
{var aData=[];for(var j=0,jLen=oSettings.aoColumns.length;j<jLen;j++)
{aData.push(json.aaData[i][aiIndex[j]]);}
_fnAddData(oSettings,aData);}
else
{_fnAddData(oSettings,json.aaData[i]);}}
oSettings.aiDisplay=oSettings.aiDisplayMaster.slice();oSettings.bAjaxDataGet=false;_fnDraw(oSettings);oSettings.bAjaxDataGet=true;_fnProcessingDisplay(oSettings,false);}
function _fnAddOptionsHtml(oSettings)
{var nHolding=document.createElement('div');oSettings.nTable.parentNode.insertBefore(nHolding,oSettings.nTable);var nWrapper=document.createElement('div');nWrapper.className=oSettings.oClasses.sWrapper;if(oSettings.sTableId!=='')
{nWrapper.setAttribute('id',oSettings.sTableId+'_wrapper');}
var nInsertNode=nWrapper;var sDom=oSettings.sDom.replace("H","fg-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix");sDom=sDom.replace("F","fg-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix");var aDom=sDom.split('');var nTmp,iPushFeature,cOption,nNewNode,cNext,sClass,j;for(var i=0;i<aDom.length;i++)
{iPushFeature=0;cOption=aDom[i];if(cOption=='<')
{nNewNode=document.createElement('div');cNext=aDom[i+1];if(cNext=="'"||cNext=='"')
{sClass="";j=2;while(aDom[i+j]!=cNext)
{sClass+=aDom[i+j];j++;}
nNewNode.className=sClass;i+=j;}
nInsertNode.appendChild(nNewNode);nInsertNode=nNewNode;}
else if(cOption=='>')
{nInsertNode=nInsertNode.parentNode;}
else if(cOption=='l'&&oSettings.oFeatures.bPaginate&&oSettings.oFeatures.bLengthChange)
{nTmp=_fnFeatureHtmlLength(oSettings);iPushFeature=1;}
else if(cOption=='f'&&oSettings.oFeatures.bFilter)
{nTmp=_fnFeatureHtmlFilter(oSettings);iPushFeature=1;}
else if(cOption=='r'&&oSettings.oFeatures.bProcessing)
{nTmp=_fnFeatureHtmlProcessing(oSettings);iPushFeature=1;}
else if(cOption=='t')
{nTmp=oSettings.nTable;iPushFeature=1;}
else if(cOption=='i'&&oSettings.oFeatures.bInfo)
{nTmp=_fnFeatureHtmlInfo(oSettings);iPushFeature=1;}
else if(cOption=='p'&&oSettings.oFeatures.bPaginate)
{nTmp=_fnFeatureHtmlPaginate(oSettings);iPushFeature=1;}
else if(_oExt.aoFeatures.length!==0)
{var aoFeatures=_oExt.aoFeatures;for(var k=0,kLen=aoFeatures.length;k<kLen;k++)
{if(cOption==aoFeatures[k].cFeature)
{nTmp=aoFeatures[k].fnInit(oSettings);if(nTmp)
{iPushFeature=1;}
break;}}}
if(iPushFeature==1)
{if(typeof oSettings.aanFeatures[cOption]!='object')
{oSettings.aanFeatures[cOption]=[];}
oSettings.aanFeatures[cOption].push(nTmp);nInsertNode.appendChild(nTmp);}}
nHolding.parentNode.replaceChild(nWrapper,nHolding);}
function _fnFeatureHtmlFilter(oSettings)
{var nFilter=document.createElement('div');if(oSettings.sTableId!==''&&typeof oSettings.aanFeatures.f=="undefined")
{nFilter.setAttribute('id',oSettings.sTableId+'_filter');}
nFilter.className=oSettings.oClasses.sFilter;var sSpace=oSettings.oLanguage.sSearch===""?"":" ";nFilter.innerHTML=oSettings.oLanguage.sSearch+sSpace+'<input type="text" />';var jqFilter=$("input",nFilter);jqFilter.val(oSettings.oPreviousSearch.sSearch.replace('"','"'));jqFilter.keyup(function(e){var n=oSettings.aanFeatures.f;for(var i=0,iLen=n.length;i<iLen;i++)
{if(n[i]!=this.parentNode)
{$('input',n[i]).val(this.value);}}
_fnFilterComplete(oSettings,{"sSearch":this.value,"bEscapeRegex":oSettings.oPreviousSearch.bEscapeRegex});});jqFilter.keypress(function(e){if(e.keyCode==13)
{return false;}});return nFilter;}
function _fnFilterComplete(oSettings,oInput,iForce)
{_fnFilter(oSettings,oInput.sSearch,iForce,oInput.bEscapeRegex);for(var i=0;i<oSettings.aoPreSearchCols.length;i++)
{_fnFilterColumn(oSettings,oSettings.aoPreSearchCols[i].sSearch,i,oSettings.aoPreSearchCols[i].bEscapeRegex);}
if(_oExt.afnFiltering.length!==0)
{_fnFilterCustom(oSettings);}
oSettings.bFiltered=true;oSettings._iDisplayStart=0;_fnCalculateEnd(oSettings);_fnDraw(oSettings);_fnBuildSearchArray(oSettings,0);}
function _fnFilterCustom(oSettings)
{var afnFilters=_oExt.afnFiltering;for(var i=0,iLen=afnFilters.length;i<iLen;i++)
{var iCorrector=0;for(var j=0,jLen=oSettings.aiDisplay.length;j<jLen;j++)
{var iDisIndex=oSettings.aiDisplay[j-iCorrector];if(!afnFilters[i](oSettings,oSettings.aoData[iDisIndex]._aData,iDisIndex))
{oSettings.aiDisplay.splice(j-iCorrector,1);iCorrector++;}}}}
function _fnFilterColumn(oSettings,sInput,iColumn,bEscapeRegex)
{if(sInput==="")
{return;}
var iIndexCorrector=0;var sRegexMatch=bEscapeRegex?_fnEscapeRegex(sInput):sInput;var rpSearch=new RegExp(sRegexMatch,"i");for(var i=oSettings.aiDisplay.length-1;i>=0;i--)
{var sData=_fnDataToSearch(oSettings.aoData[oSettings.aiDisplay[i]]._aData[iColumn],oSettings.aoColumns[iColumn].sType);if(!rpSearch.test(sData))
{oSettings.aiDisplay.splice(i,1);iIndexCorrector++;}}}
function _fnFilter(oSettings,sInput,iForce,bEscapeRegex)
{var i;if(typeof iForce=='undefined'||iForce===null)
{iForce=0;}
if(_oExt.afnFiltering.length!==0)
{iForce=1;}
var asSearch=bEscapeRegex?_fnEscapeRegex(sInput).split(' '):sInput.split(' ');var sRegExpString='^(?=.*?'+asSearch.join(')(?=.*?')+').*$';var rpSearch=new RegExp(sRegExpString,"i");if(sInput.length<=0)
{oSettings.aiDisplay.splice(0,oSettings.aiDisplay.length);oSettings.aiDisplay=oSettings.aiDisplayMaster.slice();}
else
{if(oSettings.aiDisplay.length==oSettings.aiDisplayMaster.length||oSettings.oPreviousSearch.sSearch.length>sInput.length||iForce==1||sInput.indexOf(oSettings.oPreviousSearch.sSearch)!==0)
{oSettings.aiDisplay.splice(0,oSettings.aiDisplay.length);_fnBuildSearchArray(oSettings,1);for(i=0;i<oSettings.aiDisplayMaster.length;i++)
{if(rpSearch.test(oSettings.asDataSearch[i]))
{oSettings.aiDisplay.push(oSettings.aiDisplayMaster[i]);}}}
else
{var iIndexCorrector=0;for(i=0;i<oSettings.asDataSearch.length;i++)
{if(!rpSearch.test(oSettings.asDataSearch[i]))
{oSettings.aiDisplay.splice(i-iIndexCorrector,1);iIndexCorrector++;}}}}
oSettings.oPreviousSearch.sSearch=sInput;oSettings.oPreviousSearch.bEscapeRegex=bEscapeRegex;}
function _fnBuildSearchArray(oSettings,iMaster)
{oSettings.asDataSearch.splice(0,oSettings.asDataSearch.length);var aArray=(typeof iMaster!='undefined'&&iMaster==1)?oSettings.aiDisplayMaster:oSettings.aiDisplay;for(var i=0,iLen=aArray.length;i<iLen;i++)
{oSettings.asDataSearch[i]='';for(var j=0,jLen=oSettings.aoColumns.length;j<jLen;j++)
{if(oSettings.aoColumns[j].bSearchable)
{var sData=oSettings.aoData[aArray[i]]._aData[j];oSettings.asDataSearch[i]+=_fnDataToSearch(sData,oSettings.aoColumns[j].sType)+' ';}}}}
function _fnDataToSearch(sData,sType)
{if(typeof _oExt.ofnSearch[sType]=="function")
{return _oExt.ofnSearch[sType](sData);}
else if(sType=="html")
{return sData.replace(/\n/g," ").replace(/<.*?>/g,"");}
else if(typeof sData=="string")
{return sData.replace(/\n/g," ");}
return sData;}
function _fnSort(oSettings,bApplyClasses)
{var aaSort=[];var oSort=_oExt.oSort;var aoData=oSettings.aoData;var iDataSort;var iDataType;var i,j,jLen;if(!oSettings.oFeatures.bServerSide&&(oSettings.aaSorting.length!==0||oSettings.aaSortingFixed!==null))
{if(oSettings.aaSortingFixed!==null)
{aaSort=oSettings.aaSortingFixed.concat(oSettings.aaSorting);}
else
{aaSort=oSettings.aaSorting.slice();}
for(i=0;i<aaSort.length;i++)
{var iColumn=aaSort[i][0];var sDataType=oSettings.aoColumns[iColumn].sSortDataType;if(typeof _oExt.afnSortData[sDataType]!='undefined')
{var iCorrector=0;var aData=_oExt.afnSortData[sDataType](oSettings,iColumn);for(j=0,jLen=aoData.length;j<jLen;j++)
{if(aoData[j]!==null)
{aoData[j]._aData[iColumn]=aData[iCorrector];iCorrector++;}}}}
if(!window.runtime)
{var fnLocalSorting;var sDynamicSort="fnLocalSorting = function(a,b){"+"var iTest;";for(i=0;i<aaSort.length-1;i++)
{iDataSort=oSettings.aoColumns[aaSort[i][0]].iDataSort;iDataType=oSettings.aoColumns[iDataSort].sType;sDynamicSort+="iTest = oSort['"+iDataType+"-"+aaSort[i][1]+"']"+"( aoData[a]._aData["+iDataSort+"], aoData[b]._aData["+iDataSort+"] ); if ( iTest === 0 )";}
iDataSort=oSettings.aoColumns[aaSort[aaSort.length-1][0]].iDataSort;iDataType=oSettings.aoColumns[iDataSort].sType;sDynamicSort+="iTest = oSort['"+iDataType+"-"+aaSort[aaSort.length-1][1]+"']"+"( aoData[a]._aData["+iDataSort+"], aoData[b]._aData["+iDataSort+"] );"+"if (iTest===0) return oSort['numeric-"+aaSort[aaSort.length-1][1]+"'](a, b); "+"return iTest;}";eval(sDynamicSort);oSettings.aiDisplayMaster.sort(fnLocalSorting);}
else
{var aAirSort=[];var iLen=aaSort.length;for(i=0;i<iLen;i++)
{iDataSort=oSettings.aoColumns[aaSort[i][0]].iDataSort;aAirSort.push([iDataSort,oSettings.aoColumns[iDataSort].sType+'-'+aaSort[i][1]]);}
oSettings.aiDisplayMaster.sort(function(a,b){var iTest;for(var i=0;i<iLen;i++)
{iTest=oSort[aAirSort[i][1]](aoData[a]._aData[aAirSort[i][0]],aoData[b]._aData[aAirSort[i][0]]);if(iTest!==0)
{return iTest;}}
return 0;});}}
if(typeof bApplyClasses=='undefined'||bApplyClasses)
{_fnSortingClasses(oSettings);}
oSettings.bSorted=true;if(oSettings.oFeatures.bFilter)
{_fnFilterComplete(oSettings,oSettings.oPreviousSearch,1);}
else
{oSettings.aiDisplay=oSettings.aiDisplayMaster.slice();oSettings._iDisplayStart=0;_fnCalculateEnd(oSettings);_fnDraw(oSettings);}}
function _fnSortAttachListener(oSettings,nNode,iDataIndex,fnCallback)
{$(nNode).click(function(e){if(oSettings.aoColumns[iDataIndex].bSortable===false)
{return;}
var fnInnerSorting=function(){var iColumn,iNextSort;if(e.shiftKey)
{var bFound=false;for(var i=0;i<oSettings.aaSorting.length;i++)
{if(oSettings.aaSorting[i][0]==iDataIndex)
{bFound=true;iColumn=oSettings.aaSorting[i][0];iNextSort=oSettings.aaSorting[i][2]+1;if(typeof oSettings.aoColumns[iColumn].asSorting[iNextSort]=='undefined')
{oSettings.aaSorting.splice(i,1);}
else
{oSettings.aaSorting[i][1]=oSettings.aoColumns[iColumn].asSorting[iNextSort];oSettings.aaSorting[i][2]=iNextSort;}
break;}}
if(bFound===false)
{oSettings.aaSorting.push([iDataIndex,oSettings.aoColumns[iDataIndex].asSorting[0],0]);}}
else
{if(oSettings.aaSorting.length==1&&oSettings.aaSorting[0][0]==iDataIndex)
{iColumn=oSettings.aaSorting[0][0];iNextSort=oSettings.aaSorting[0][2]+1;if(typeof oSettings.aoColumns[iColumn].asSorting[iNextSort]=='undefined')
{iNextSort=0;}
oSettings.aaSorting[0][1]=oSettings.aoColumns[iColumn].asSorting[iNextSort];oSettings.aaSorting[0][2]=iNextSort;}
else
{oSettings.aaSorting.splice(0,oSettings.aaSorting.length);oSettings.aaSorting.push([iDataIndex,oSettings.aoColumns[iDataIndex].asSorting[0],0]);}}
_fnSort(oSettings);};if(!oSettings.oFeatures.bProcessing)
{fnInnerSorting();}
else
{_fnProcessingDisplay(oSettings,true);setTimeout(function(){fnInnerSorting();if(!oSettings.oFeatures.bServerSide)
{_fnProcessingDisplay(oSettings,false);}},0);}
if(typeof fnCallback=='function')
{fnCallback(oSettings);}});}
function _fnSortingClasses(oSettings)
{var i,iLen,j,jLen,iFound;var aaSort,sClass;var iColumns=oSettings.aoColumns.length;var oClasses=oSettings.oClasses;for(i=0;i<iColumns;i++)
{if(oSettings.aoColumns[i].bSortable)
{$(oSettings.aoColumns[i].nTh).removeClass(oClasses.sSortAsc+" "+oClasses.sSortDesc+" "+oSettings.aoColumns[i].sSortingClass);}}
if(oSettings.aaSortingFixed!==null)
{aaSort=oSettings.aaSortingFixed.concat(oSettings.aaSorting);}
else
{aaSort=oSettings.aaSorting.slice();}
for(i=0;i<oSettings.aoColumns.length;i++)
{if(oSettings.aoColumns[i].bSortable)
{sClass=oSettings.aoColumns[i].sSortingClass;iFound=-1;for(j=0;j<aaSort.length;j++)
{if(aaSort[j][0]==i)
{sClass=(aaSort[j][1]=="asc")?oClasses.sSortAsc:oClasses.sSortDesc;iFound=j;break;}}
$(oSettings.aoColumns[i].nTh).addClass(sClass);if(oSettings.bJUI)
{var jqSpan=$("span",oSettings.aoColumns[i].nTh);jqSpan.removeClass(oClasses.sSortJUIAsc+" "+oClasses.sSortJUIDesc+" "+
oClasses.sSortJUI+" "+oClasses.sSortJUIAscAllowed+" "+oClasses.sSortJUIDescAllowed);var sSpanClass;if(iFound==-1)
{sSpanClass=oSettings.aoColumns[i].sSortingClassJUI;}
else if(aaSort[iFound][1]=="asc")
{sSpanClass=oClasses.sSortJUIAsc;}
else
{sSpanClass=oClasses.sSortJUIDesc;}
jqSpan.addClass(sSpanClass);}}
else
{$(oSettings.aoColumns[i].nTh).addClass(oSettings.aoColumns[i].sSortingClass);}}
sClass=oClasses.sSortColumn;if(oSettings.oFeatures.bSort&&oSettings.oFeatures.bSortClasses)
{var nTds=_fnGetTdNodes(oSettings);if(nTds.length>=iColumns)
{for(i=0;i<iColumns;i++)
{if(nTds[i].className.indexOf(sClass+"1")!=-1)
{for(j=0,jLen=(nTds.length/iColumns);j<jLen;j++)
{nTds[(iColumns*j)+i].className=nTds[(iColumns*j)+i].className.replace(" "+sClass+"1","");}}
else if(nTds[i].className.indexOf(sClass+"2")!=-1)
{for(j=0,jLen=(nTds.length/iColumns);j<jLen;j++)
{nTds[(iColumns*j)+i].className=nTds[(iColumns*j)+i].className.replace(" "+sClass+"2","");}}
else if(nTds[i].className.indexOf(sClass+"3")!=-1)
{for(j=0,jLen=(nTds.length/iColumns);j<jLen;j++)
{nTds[(iColumns*j)+i].className=nTds[(iColumns*j)+i].className.replace(" "+sClass+"3","");}}}}
var iClass=1,iTargetCol;for(i=0;i<aaSort.length;i++)
{iTargetCol=parseInt(aaSort[i][0],10);for(j=0,jLen=(nTds.length/iColumns);j<jLen;j++)
{nTds[(iColumns*j)+iTargetCol].className+=" "+sClass+iClass;}
if(iClass<3)
{iClass++;}}}}
function _fnFeatureHtmlPaginate(oSettings)
{var nPaginate=document.createElement('div');nPaginate.className=oSettings.oClasses.sPaging+oSettings.sPaginationType;_oExt.oPagination[oSettings.sPaginationType].fnInit(oSettings,nPaginate,function(oSettings){_fnCalculateEnd(oSettings);_fnDraw(oSettings);});if(typeof oSettings.aanFeatures.p=="undefined")
{oSettings.aoDrawCallback.push({"fn":function(oSettings){_oExt.oPagination[oSettings.sPaginationType].fnUpdate(oSettings,function(oSettings){_fnCalculateEnd(oSettings);_fnDraw(oSettings);});},"sName":"pagination"});}
return nPaginate;}
function _fnPageChange(oSettings,sAction)
{var iOldStart=oSettings._iDisplayStart;if(sAction=="first")
{oSettings._iDisplayStart=0;}
else if(sAction=="previous")
{oSettings._iDisplayStart=oSettings._iDisplayLength>=0?oSettings._iDisplayStart-oSettings._iDisplayLength:0;if(oSettings._iDisplayStart<0)
{oSettings._iDisplayStart=0;}}
else if(sAction=="next")
{if(oSettings._iDisplayLength>=0)
{if(oSettings._iDisplayStart+oSettings._iDisplayLength<oSettings.fnRecordsDisplay())
{oSettings._iDisplayStart+=oSettings._iDisplayLength;}}
else
{oSettings._iDisplayStart=0;}}
else if(sAction=="last")
{if(oSettings._iDisplayLength>=0)
{var iPages=parseInt((oSettings.fnRecordsDisplay()-1)/oSettings._iDisplayLength,10)+1;oSettings._iDisplayStart=(iPages-1)*oSettings._iDisplayLength;}
else
{oSettings._iDisplayStart=0;}}
else
{alert("DataTables warning: unknown paging action: "+sAction);}
return iOldStart!=oSettings._iDisplayStart;}
function _fnFeatureHtmlInfo(oSettings)
{var nInfo=document.createElement('div');nInfo.className=oSettings.oClasses.sInfo;if(typeof oSettings.aanFeatures.i=="undefined")
{oSettings.aoDrawCallback.push({"fn":_fnUpdateInfo,"sName":"information"});if(oSettings.sTableId!=='')
{nInfo.setAttribute('id',oSettings.sTableId+'_info');}}
return nInfo;}
function _fnUpdateInfo(oSettings)
{if(!oSettings.oFeatures.bInfo||oSettings.aanFeatures.i.length===0)
{return;}
var nFirst=oSettings.aanFeatures.i[0];if(oSettings.fnRecordsDisplay()===0&&oSettings.fnRecordsDisplay()==oSettings.fnRecordsTotal())
{nFirst.innerHTML=oSettings.oLanguage.sInfoEmpty+oSettings.oLanguage.sInfoPostFix;}
else if(oSettings.fnRecordsDisplay()===0)
{nFirst.innerHTML=oSettings.oLanguage.sInfoEmpty+' '+
oSettings.oLanguage.sInfoFiltered.replace('_MAX_',oSettings.fnRecordsTotal())+oSettings.oLanguage.sInfoPostFix;}
else if(oSettings.fnRecordsDisplay()==oSettings.fnRecordsTotal())
{nFirst.innerHTML=oSettings.oLanguage.sInfo.replace('_START_',oSettings._iDisplayStart+1).replace('_END_',oSettings.fnDisplayEnd()).replace('_TOTAL_',oSettings.fnRecordsDisplay())+
oSettings.oLanguage.sInfoPostFix;}
else
{nFirst.innerHTML=oSettings.oLanguage.sInfo.replace('_START_',oSettings._iDisplayStart+1).replace('_END_',oSettings.fnDisplayEnd()).replace('_TOTAL_',oSettings.fnRecordsDisplay())+' '+
oSettings.oLanguage.sInfoFiltered.replace('_MAX_',oSettings.fnRecordsTotal())+
oSettings.oLanguage.sInfoPostFix;}
var n=oSettings.aanFeatures.i;if(n.length>1)
{var sInfo=nFirst.innerHTML;for(var i=1,iLen=n.length;i<iLen;i++)
{n[i].innerHTML=sInfo;}}}
function _fnFeatureHtmlLength(oSettings)
{var sName=(oSettings.sTableId==="")?"":'name="'+oSettings.sTableId+'_length"';var sStdMenu='<select size="1" '+sName+'>'+'<option value="10">10</option>'+'<option value="25">25</option>'+'<option value="50">50</option>'+'<option value="100">100</option>'+'</select>';var nLength=document.createElement('div');if(oSettings.sTableId!==''&&typeof oSettings.aanFeatures.l=="undefined")
{nLength.setAttribute('id',oSettings.sTableId+'_length');}
nLength.className=oSettings.oClasses.sLength;nLength.innerHTML=oSettings.oLanguage.sLengthMenu.replace('_MENU_',sStdMenu);$('select option[value="'+oSettings._iDisplayLength+'"]',nLength).attr("selected",true);$('select',nLength).change(function(e){var iVal=$(this).val();var n=oSettings.aanFeatures.l;for(var i=0,iLen=n.length;i<iLen;i++)
{if(n[i]!=this.parentNode)
{$('select',n[i]).val(iVal);}}
oSettings._iDisplayLength=parseInt(iVal,10);_fnCalculateEnd(oSettings);if(oSettings._iDisplayEnd==oSettings.aiDisplay.length)
{oSettings._iDisplayStart=oSettings._iDisplayEnd-oSettings._iDisplayLength;if(oSettings._iDisplayStart<0)
{oSettings._iDisplayStart=0;}}
if(oSettings._iDisplayLength==-1)
{oSettings._iDisplayStart=0;}
_fnDraw(oSettings);});return nLength;}
function _fnFeatureHtmlProcessing(oSettings)
{var nProcessing=document.createElement('div');if(oSettings.sTableId!==''&&typeof oSettings.aanFeatures.r=="undefined")
{nProcessing.setAttribute('id',oSettings.sTableId+'_processing');}
nProcessing.innerHTML=oSettings.oLanguage.sProcessing;nProcessing.className=oSettings.oClasses.sProcessing;oSettings.nTable.parentNode.insertBefore(nProcessing,oSettings.nTable);return nProcessing;}
function _fnProcessingDisplay(oSettings,bShow)
{if(oSettings.oFeatures.bProcessing)
{var an=oSettings.aanFeatures.r;for(var i=0,iLen=an.length;i<iLen;i++)
{an[i].style.visibility=bShow?"visible":"hidden";}}}
function _fnVisibleToColumnIndex(oSettings,iMatch)
{var iColumn=-1;for(var i=0;i<oSettings.aoColumns.length;i++)
{if(oSettings.aoColumns[i].bVisible===true)
{iColumn++;}
if(iColumn==iMatch)
{return i;}}
return null;}
function _fnColumnIndexToVisible(oSettings,iMatch)
{var iVisible=-1;for(var i=0;i<oSettings.aoColumns.length;i++)
{if(oSettings.aoColumns[i].bVisible===true)
{iVisible++;}
if(i==iMatch)
{return oSettings.aoColumns[i].bVisible===true?iVisible:null;}}
return null;}
function _fnNodeToDataIndex(s,n)
{for(var i=0,iLen=s.aoData.length;i<iLen;i++)
{if(s.aoData[i]!==null&&s.aoData[i].nTr==n)
{return i;}}
return null;}
function _fnVisbleColumns(oS)
{var iVis=0;for(var i=0;i<oS.aoColumns.length;i++)
{if(oS.aoColumns[i].bVisible===true)
{iVis++;}}
return iVis;}
function _fnCalculateEnd(oSettings)
{if(oSettings.oFeatures.bPaginate===false)
{oSettings._iDisplayEnd=oSettings.aiDisplay.length;}
else
{if(oSettings._iDisplayStart+oSettings._iDisplayLength>oSettings.aiDisplay.length||oSettings._iDisplayLength==-1)
{oSettings._iDisplayEnd=oSettings.aiDisplay.length;}
else
{oSettings._iDisplayEnd=oSettings._iDisplayStart+oSettings._iDisplayLength;}}}
function _fnConvertToWidth(sWidth,nParent)
{if(!sWidth||sWidth===null||sWidth==='')
{return 0;}
if(typeof nParent=="undefined")
{nParent=document.getElementsByTagName('body')[0];}
var iWidth;var nTmp=document.createElement("div");nTmp.style.width=sWidth;nParent.appendChild(nTmp);iWidth=nTmp.offsetWidth;nParent.removeChild(nTmp);return(iWidth);}
function _fnCalculateColumnWidths(oSettings)
{var iTableWidth=oSettings.nTable.offsetWidth;var iTotalUserIpSize=0;var iTmpWidth;var iVisibleColumns=0;var iColums=oSettings.aoColumns.length;var i;var oHeaders=$('thead:eq(0)>th',oSettings.nTable);for(i=0;i<iColums;i++)
{if(oSettings.aoColumns[i].bVisible)
{iVisibleColumns++;if(oSettings.aoColumns[i].sWidth!==null)
{iTmpWidth=_fnConvertToWidth(oSettings.aoColumns[i].sWidth,oSettings.nTable.parentNode);iTotalUserIpSize+=iTmpWidth;oSettings.aoColumns[i].sWidth=iTmpWidth+"px";}}}
if(iColums==oHeaders.length&&iTotalUserIpSize===0&&iVisibleColumns==iColums)
{for(i=0;i<oSettings.aoColumns.length;i++)
{oSettings.aoColumns[i].sWidth=oHeaders[i].offsetWidth+"px";}}
else
{var nCalcTmp=oSettings.nTable.cloneNode(false);nCalcTmp.setAttribute("id",'');var sTableTmp='<table class="'+nCalcTmp.className+'">';var sCalcHead="<tr>";var sCalcHtml="<tr>";for(i=0;i<iColums;i++)
{if(oSettings.aoColumns[i].bVisible)
{sCalcHead+='<th>'+oSettings.aoColumns[i].sTitle+'</th>';if(oSettings.aoColumns[i].sWidth!==null)
{var sWidth='';if(oSettings.aoColumns[i].sWidth!==null)
{sWidth=' style="width:'+oSettings.aoColumns[i].sWidth+';"';}
sCalcHtml+='<td'+sWidth+' tag_index="'+i+'">'+fnGetMaxLenString(oSettings,i)+'</td>';}
else
{sCalcHtml+='<td tag_index="'+i+'">'+fnGetMaxLenString(oSettings,i)+'</td>';}}}
sCalcHead+="</tr>";sCalcHtml+="</tr>";nCalcTmp=$(sTableTmp+sCalcHead+sCalcHtml+'</table>')[0];nCalcTmp.style.width=iTableWidth+"px";nCalcTmp.style.visibility="hidden";nCalcTmp.style.position="absolute";oSettings.nTable.parentNode.appendChild(nCalcTmp);var oNodes=$("tr:eq(1)>td",nCalcTmp);var iIndex;for(i=0;i<oNodes.length;i++)
{iIndex=oNodes[i].getAttribute('tag_index');var iContentWidth=$("td",nCalcTmp).eq(i).width();var iSetWidth=oSettings.aoColumns[i].sWidth?oSettings.aoColumns[i].sWidth.slice(0,-2):0;oSettings.aoColumns[iIndex].sWidth=Math.max(iContentWidth,iSetWidth)+"px";}
oSettings.nTable.parentNode.removeChild(nCalcTmp);}}
function fnGetMaxLenString(oSettings,iCol)
{var iMax=0;var iMaxIndex=-1;for(var i=0;i<oSettings.aoData.length;i++)
{if(oSettings.aoData[i]._aData[iCol].length>iMax)
{iMax=oSettings.aoData[i]._aData[iCol].length;iMaxIndex=i;}}
if(iMaxIndex>=0)
{return oSettings.aoData[iMaxIndex]._aData[iCol];}
return'';}
function _fnArrayCmp(aArray1,aArray2)
{if(aArray1.length!=aArray2.length)
{return 1;}
for(var i=0;i<aArray1.length;i++)
{if(aArray1[i]!=aArray2[i])
{return 2;}}
return 0;}
function _fnDetectType(sData)
{var aTypes=_oExt.aTypes;var iLen=aTypes.length;for(var i=0;i<iLen;i++)
{var sType=aTypes[i](sData);if(sType!==null)
{return sType;}}
return'string';}
function _fnSettingsFromNode(nTable)
{for(var i=0;i<_aoSettings.length;i++)
{if(_aoSettings[i].nTable==nTable)
{return _aoSettings[i];}}
return null;}
function _fnGetDataMaster(oSettings)
{var aData=[];var iLen=oSettings.aoData.length;for(var i=0;i<iLen;i++)
{if(oSettings.aoData[i]===null)
{aData.push(null);}
else
{aData.push(oSettings.aoData[i]._aData);}}
return aData;}
function _fnGetTrNodes(oSettings)
{var aNodes=[];var iLen=oSettings.aoData.length;for(var i=0;i<iLen;i++)
{if(oSettings.aoData[i]===null)
{aNodes.push(null);}
else
{aNodes.push(oSettings.aoData[i].nTr);}}
return aNodes;}
function _fnGetTdNodes(oSettings)
{var nTrs=_fnGetTrNodes(oSettings);var nTds=[],nTd;var anReturn=[];var iCorrector;var iRow,iRows,iColumn,iColumns;for(iRow=0,iRows=nTrs.length;iRow<iRows;iRow++)
{nTds=[];for(iColumn=0,iColumns=nTrs[iRow].childNodes.length;iColumn<iColumns;iColumn++)
{nTd=nTrs[iRow].childNodes[iColumn];if(nTd.nodeName=="TD")
{nTds.push(nTd);}}
iCorrector=0;for(iColumn=0,iColumns=oSettings.aoColumns.length;iColumn<iColumns;iColumn++)
{if(oSettings.aoColumns[iColumn].bVisible)
{anReturn.push(nTds[iColumn-iCorrector]);}
else
{anReturn.push(oSettings.aoData[iRow]._anHidden[iColumn]);iCorrector++;}}}
return anReturn;}
function _fnEscapeRegex(sVal)
{var acEscape=['/','.','*','+','?','|','(',')','[',']','{','}','\\','$','^'];var reReplace=new RegExp('(\\'+acEscape.join('|\\')+')','g');return sVal.replace(reReplace,'\\$1');}
function _fnReOrderIndex(oSettings,sColumns)
{var aColumns=sColumns.split(',');var aiReturn=[];for(var i=0,iLen=oSettings.aoColumns.length;i<iLen;i++)
{for(var j=0;j<iLen;j++)
{if(oSettings.aoColumns[i].sName==aColumns[j])
{aiReturn.push(j);break;}}}
return aiReturn;}
function _fnColumnOrdering(oSettings)
{var sNames='';for(var i=0,iLen=oSettings.aoColumns.length;i<iLen;i++)
{sNames+=oSettings.aoColumns[i].sName+',';}
if(sNames.length==iLen)
{return"";}
return sNames.slice(0,-1);}
function _fnClearTable(oSettings)
{oSettings.aoData.length=0;oSettings.aiDisplayMaster.length=0;oSettings.aiDisplay.length=0;_fnCalculateEnd(oSettings);}
function _fnSaveState(oSettings)
{if(!oSettings.oFeatures.bStateSave)
{return;}
var i;var sValue="{";sValue+='"iStart": '+oSettings._iDisplayStart+',';sValue+='"iEnd": '+oSettings._iDisplayEnd+',';sValue+='"iLength": '+oSettings._iDisplayLength+',';sValue+='"sFilter": "'+oSettings.oPreviousSearch.sSearch.replace('"','\\"')+'",';sValue+='"sFilterEsc": '+oSettings.oPreviousSearch.bEscapeRegex+',';sValue+='"aaSorting": [ ';for(i=0;i<oSettings.aaSorting.length;i++)
{sValue+="["+oSettings.aaSorting[i][0]+",'"+oSettings.aaSorting[i][1]+"'],";}
sValue=sValue.substring(0,sValue.length-1);sValue+="],";sValue+='"aaSearchCols": [ ';for(i=0;i<oSettings.aoPreSearchCols.length;i++)
{sValue+="['"+oSettings.aoPreSearchCols[i].sSearch.replace("'","\'")+"',"+oSettings.aoPreSearchCols[i].bEscapeRegex+"],";}
sValue=sValue.substring(0,sValue.length-1);sValue+="],";sValue+='"abVisCols": [ ';for(i=0;i<oSettings.aoColumns.length;i++)
{sValue+=oSettings.aoColumns[i].bVisible+",";}
sValue=sValue.substring(0,sValue.length-1);sValue+="]";sValue+="}";_fnCreateCookie("SpryMedia_DataTables_"+oSettings.sInstance,sValue,oSettings.iCookieDuration);}
function _fnLoadState(oSettings,oInit)
{if(!oSettings.oFeatures.bStateSave)
{return;}
var oData;var sData=_fnReadCookie("SpryMedia_DataTables_"+oSettings.sInstance);if(sData!==null&&sData!=='')
{try
{if(typeof JSON=='object'&&typeof JSON.parse=='function')
{oData=JSON.parse(sData.replace(/'/g,'"'));}
else
{oData=eval('('+sData+')');}}
catch(e)
{return;}
oSettings._iDisplayStart=oData.iStart;oSettings.iInitDisplayStart=oData.iStart;oSettings._iDisplayEnd=oData.iEnd;oSettings._iDisplayLength=oData.iLength;oSettings.oPreviousSearch.sSearch=oData.sFilter;oSettings.aaSorting=oData.aaSorting.slice();oSettings.saved_aaSorting=oData.aaSorting.slice();if(typeof oData.sFilterEsc!='undefined')
{oSettings.oPreviousSearch.bEscapeRegex=oData.sFilterEsc;}
if(typeof oData.aaSearchCols!='undefined')
{for(var i=0;i<oData.aaSearchCols.length;i++)
{oSettings.aoPreSearchCols[i]={"sSearch":oData.aaSearchCols[i][0],"bEscapeRegex":oData.aaSearchCols[i][1]};}}
if(typeof oData.abVisCols!='undefined')
{oInit.saved_aoColumns=[];for(i=0;i<oData.abVisCols.length;i++)
{oInit.saved_aoColumns[i]={};oInit.saved_aoColumns[i].bVisible=oData.abVisCols[i];}}}}
function _fnCreateCookie(sName,sValue,iSecs)
{var date=new Date();date.setTime(date.getTime()+(iSecs*1000));sName+='_'+window.location.pathname.replace(/[\/:]/g,"").toLowerCase();document.cookie=sName+"="+encodeURIComponent(sValue)+"; expires="+date.toGMTString()+"; path=/";}
function _fnReadCookie(sName)
{var sNameEQ=sName+'_'+window.location.pathname.replace(/[\/:]/g,"").toLowerCase()+"=";var sCookieContents=document.cookie.split(';');for(var i=0;i<sCookieContents.length;i++)
{var c=sCookieContents[i];while(c.charAt(0)==' ')
{c=c.substring(1,c.length);}
if(c.indexOf(sNameEQ)===0)
{return decodeURIComponent(c.substring(sNameEQ.length,c.length));}}
return null;}
function _fnGetUniqueThs(nThead)
{var nTrs=nThead.getElementsByTagName('tr');if(nTrs.length==1)
{return nTrs[0].getElementsByTagName('th');}
var aLayout=[],aReturn=[];var ROWSPAN=2,COLSPAN=3,TDELEM=4;var i,j,k,iLen,jLen,iColumnShifted;var fnShiftCol=function(a,i,j){while(typeof a[i][j]!='undefined'){j++;}
return j;};var fnAddRow=function(i){if(typeof aLayout[i]=='undefined'){aLayout[i]=[];}};for(i=0,iLen=nTrs.length;i<iLen;i++)
{fnAddRow(i);var iColumn=0;var nTds=[];for(j=0,jLen=nTrs[i].childNodes.length;j<jLen;j++)
{if(nTrs[i].childNodes[j].nodeName=="TD"||nTrs[i].childNodes[j].nodeName=="TH")
{nTds.push(nTrs[i].childNodes[j]);}}
for(j=0,jLen=nTds.length;j<jLen;j++)
{var iColspan=nTds[j].getAttribute('colspan')*1;var iRowspan=nTds[j].getAttribute('rowspan')*1;if(!iColspan||iColspan===0||iColspan===1)
{iColumnShifted=fnShiftCol(aLayout,i,iColumn);aLayout[i][iColumnShifted]=(nTds[j].nodeName=="TD")?TDELEM:nTds[j];if(iRowspan||iRowspan===0||iRowspan===1)
{for(k=1;k<iRowspan;k++)
{fnAddRow(i+k);aLayout[i+k][iColumnShifted]=ROWSPAN;}}
iColumn++;}
else
{iColumnShifted=fnShiftCol(aLayout,i,iColumn);for(k=0;k<iColspan;k++)
{aLayout[i][iColumnShifted+k]=COLSPAN;}
iColumn+=iColspan;}}}
for(i=0,iLen=aLayout[0].length;i<iLen;i++)
{for(j=0,jLen=aLayout.length;j<jLen;j++)
{if(typeof aLayout[j][i]=='object')
{aReturn.push(aLayout[j][i]);}}}
return aReturn;}
function _fnMap(oRet,oSrc,sName,sMappedName)
{if(typeof sMappedName=='undefined')
{sMappedName=sName;}
if(typeof oSrc[sName]!='undefined')
{oRet[sMappedName]=oSrc[sName];}}
this.oApi._fnInitalise=_fnInitalise;this.oApi._fnLanguageProcess=_fnLanguageProcess;this.oApi._fnAddColumn=_fnAddColumn;this.oApi._fnAddData=_fnAddData;this.oApi._fnGatherData=_fnGatherData;this.oApi._fnDrawHead=_fnDrawHead;this.oApi._fnDraw=_fnDraw;this.oApi._fnAjaxUpdate=_fnAjaxUpdate;this.oApi._fnAddOptionsHtml=_fnAddOptionsHtml;this.oApi._fnFeatureHtmlFilter=_fnFeatureHtmlFilter;this.oApi._fnFeatureHtmlInfo=_fnFeatureHtmlInfo;this.oApi._fnFeatureHtmlPaginate=_fnFeatureHtmlPaginate;this.oApi._fnPageChange=_fnPageChange;this.oApi._fnFeatureHtmlLength=_fnFeatureHtmlLength;this.oApi._fnFeatureHtmlProcessing=_fnFeatureHtmlProcessing;this.oApi._fnProcessingDisplay=_fnProcessingDisplay;this.oApi._fnFilterComplete=_fnFilterComplete;this.oApi._fnFilterColumn=_fnFilterColumn;this.oApi._fnFilter=_fnFilter;this.oApi._fnSortingClasses=_fnSortingClasses;this.oApi._fnVisibleToColumnIndex=_fnVisibleToColumnIndex;this.oApi._fnColumnIndexToVisible=_fnColumnIndexToVisible;this.oApi._fnNodeToDataIndex=_fnNodeToDataIndex;this.oApi._fnVisbleColumns=_fnVisbleColumns;this.oApi._fnBuildSearchArray=_fnBuildSearchArray;this.oApi._fnDataToSearch=_fnDataToSearch;this.oApi._fnCalculateEnd=_fnCalculateEnd;this.oApi._fnConvertToWidth=_fnConvertToWidth;this.oApi._fnCalculateColumnWidths=_fnCalculateColumnWidths;this.oApi._fnArrayCmp=_fnArrayCmp;this.oApi._fnDetectType=_fnDetectType;this.oApi._fnGetDataMaster=_fnGetDataMaster;this.oApi._fnGetTrNodes=_fnGetTrNodes;this.oApi._fnGetTdNodes=_fnGetTdNodes;this.oApi._fnEscapeRegex=_fnEscapeRegex;this.oApi._fnReOrderIndex=_fnReOrderIndex;this.oApi._fnColumnOrdering=_fnColumnOrdering;this.oApi._fnClearTable=_fnClearTable;this.oApi._fnSaveState=_fnSaveState;this.oApi._fnLoadState=_fnLoadState;this.oApi._fnCreateCookie=_fnCreateCookie;this.oApi._fnReadCookie=_fnReadCookie;this.oApi._fnGetUniqueThs=_fnGetUniqueThs;this.oApi._fnReDraw=_fnReDraw;var _that=this;return this.each(function()
{var i=0,iLen,j,jLen;for(i=0,iLen=_aoSettings.length;i<iLen;i++)
{if(_aoSettings[i].nTable==this)
{alert("DataTables warning: Unable to re-initialise DataTable. "+"Please use the API to make any configuration changes required.");return _aoSettings[i];}}
var oSettings=new classSettings();_aoSettings.push(oSettings);var bInitHandedOff=false;var bUsePassedData=false;var sId=this.getAttribute('id');if(sId!==null)
{oSettings.sTableId=sId;oSettings.sInstance=sId;}
else
{oSettings.sInstance=_oExt._oExternConfig.iNextUnique++;}
oSettings.nTable=this;oSettings.oApi=_that.oApi;if(typeof oInit!='undefined'&&oInit!==null)
{_fnMap(oSettings.oFeatures,oInit,"bPaginate");_fnMap(oSettings.oFeatures,oInit,"bLengthChange");_fnMap(oSettings.oFeatures,oInit,"bFilter");_fnMap(oSettings.oFeatures,oInit,"bSort");_fnMap(oSettings.oFeatures,oInit,"bInfo");_fnMap(oSettings.oFeatures,oInit,"bProcessing");_fnMap(oSettings.oFeatures,oInit,"bAutoWidth");_fnMap(oSettings.oFeatures,oInit,"bSortClasses");_fnMap(oSettings.oFeatures,oInit,"bServerSide");_fnMap(oSettings,oInit,"asStripClasses");_fnMap(oSettings,oInit,"fnRowCallback");_fnMap(oSettings,oInit,"fnHeaderCallback");_fnMap(oSettings,oInit,"fnFooterCallback");_fnMap(oSettings,oInit,"fnInitComplete");_fnMap(oSettings,oInit,"fnServerData");_fnMap(oSettings,oInit,"aaSorting");_fnMap(oSettings,oInit,"aaSortingFixed");_fnMap(oSettings,oInit,"sPaginationType");_fnMap(oSettings,oInit,"sAjaxSource");_fnMap(oSettings,oInit,"iCookieDuration");_fnMap(oSettings,oInit,"sDom");_fnMap(oSettings,oInit,"oSearch","oPreviousSearch");_fnMap(oSettings,oInit,"aoSearchCols","aoPreSearchCols");_fnMap(oSettings,oInit,"iDisplayLength","_iDisplayLength");_fnMap(oSettings,oInit,"bJQueryUI","bJUI");if(typeof oInit.fnDrawCallback=='function')
{oSettings.aoDrawCallback.push({"fn":oInit.fnDrawCallback,"sName":"user"});}
if(oSettings.oFeatures.bServerSide&&oSettings.oFeatures.bSort&&oSettings.oFeatures.bSortClasses)
{oSettings.aoDrawCallback.push({"fn":_fnSortingClasses,"sName":"server_side_sort_classes"});}
if(typeof oInit.bJQueryUI!='undefined'&&oInit.bJQueryUI)
{oSettings.oClasses=_oExt.oJUIClasses;if(typeof oInit.sDom=='undefined')
{oSettings.sDom='<"H"lfr>t<"F"ip>';}}
if(typeof oInit.iDisplayStart!='undefined'&&typeof oSettings.iInitDisplayStart=='undefined')
{oSettings.iInitDisplayStart=oInit.iDisplayStart;oSettings._iDisplayStart=oInit.iDisplayStart;}
if(typeof oInit.bStateSave!='undefined')
{oSettings.oFeatures.bStateSave=oInit.bStateSave;_fnLoadState(oSettings,oInit);oSettings.aoDrawCallback.push({"fn":_fnSaveState,"sName":"state_save"});}
if(typeof oInit.aaData!='undefined')
{bUsePassedData=true;}
if(typeof oInit!='undefined'&&typeof oInit.aoData!='undefined')
{oInit.aoColumns=oInit.aoData;}
if(typeof oInit.oLanguage!='undefined')
{if(typeof oInit.oLanguage.sUrl!='undefined'&&oInit.oLanguage.sUrl!=="")
{oSettings.oLanguage.sUrl=oInit.oLanguage.sUrl;$.getJSON(oSettings.oLanguage.sUrl,null,function(json){_fnLanguageProcess(oSettings,json,true);});bInitHandedOff=true;}
else
{_fnLanguageProcess(oSettings,oInit.oLanguage,false);}}}
else
{oInit={};}
if(typeof oInit.asStripClasses=='undefined')
{oSettings.asStripClasses.push(oSettings.oClasses.sStripOdd);oSettings.asStripClasses.push(oSettings.oClasses.sStripEven);}
var nThead=this.getElementsByTagName('thead');var nThs=nThead.length===0?null:_fnGetUniqueThs(nThead[0]);var bUseCols=typeof oInit.aoColumns!='undefined';for(i=0,iLen=bUseCols?oInit.aoColumns.length:nThs.length;i<iLen;i++)
{var oCol=bUseCols?oInit.aoColumns[i]:null;var nTh=nThs?nThs[i]:null;if(typeof oInit.saved_aoColumns!='undefined'&&oInit.saved_aoColumns.length==iLen)
{if(oCol===null)
{oCol={};}
oCol.bVisible=oInit.saved_aoColumns[i].bVisible;}
_fnAddColumn(oSettings,oCol,nTh);}
for(i=0,iLen=oSettings.aaSorting.length;i<iLen;i++)
{var oColumn=oSettings.aoColumns[oSettings.aaSorting[i][0]];if(typeof oSettings.aaSorting[i][2]=='undefined')
{oSettings.aaSorting[i][2]=0;}
if(typeof oInit.aaSorting=="undefined"&&typeof oSettings.saved_aaSorting=="undefined")
{oSettings.aaSorting[i][1]=oColumn.asSorting[0];}
for(j=0,jLen=oColumn.asSorting.length;j<jLen;j++)
{if(oSettings.aaSorting[i][1]==oColumn.asSorting[j])
{oSettings.aaSorting[i][2]=j;break;}}}
if(this.getElementsByTagName('thead').length===0)
{this.appendChild(document.createElement('thead'));}
if(this.getElementsByTagName('tbody').length===0)
{this.appendChild(document.createElement('tbody'));}
if(bUsePassedData)
{for(i=0;i<oInit.aaData.length;i++)
{_fnAddData(oSettings,oInit.aaData[i]);}}
else
{_fnGatherData(oSettings);}
oSettings.aiDisplay=oSettings.aiDisplayMaster.slice();if(oSettings.oFeatures.bAutoWidth)
{_fnCalculateColumnWidths(oSettings);}
oSettings.bInitialised=true;if(bInitHandedOff===false)
{_fnInitalise(oSettings);}});};})(jQuery);(function($){$.fn.dashboard=function(options){var dashboard={};dashboard.element=this.empty();dashboard.ready=false;dashboard.columns=Array();dashboard.widgets=Array();dashboard.saveColumns=function(){for(var c in dashboard.columns){var col=dashboard.columns[c];if(typeof col=='object'){if(col.element.children(':visible').not(col.emptyPlaceholder).length>0){col.emptyPlaceholder.hide();}
else{col.emptyPlaceholder.show();}}}
if(!dashboard.ready){return;}
var params={};for(var c in dashboard.columns){if(typeof dashboard.columns[c]=='object')var ids=dashboard.columns[c].element.sortable('toArray');for(var w in ids){if(typeof ids[w]=='string')var id=ids[w].substring('widget-'.length);if(typeof dashboard.widgets[id]=='object')params['columns['+c+']['+id+']']=(dashboard.widgets[id].minimized?'1':'0');}}
$.extend(params,opts.ajaxCallbacks.saveColumns.data);$.post(opts.ajaxCallbacks.saveColumns.url,params,function(response,status){invokeCallback(opts.callbacks.saveColumns,dashboard);if(window.console&&console.log){console.log(response);}});};dashboard.enterFullscreen=function(element){for(var c in dashboard.columns){if(typeof dashboard.columns[c]=='object')dashboard.columns[c].element.hide();}
if(!dashboard.fullscreen){var markup='<a id="full-screen-header" class="full-screen-close-icon">'+opts.fullscreenHeaderInner+'</a>';dashboard.fullscreen={headerElement:$(markup).prependTo(dashboard.element).click(dashboard.exitFullscreen).hide()};}
dashboard.fullscreen.headerElement.slideDown();dashboard.fullscreen.currentElement=element.show();dashboard.fullscreen.displayed=true;invokeCallback(opts.callbacks.enterFullscreen,dashboard,dashboard.fullscreen.currentElement);};dashboard.exitFullscreen=function(){if(!dashboard.fullscreen.displayed){return;}
dashboard.fullscreen.headerElement.slideUp();dashboard.fullscreen.currentElement.hide();dashboard.fullscreen.displayed=false;for(var c in dashboard.columns){if(typeof dashboard.columns[c]=='object')dashboard.columns[c].element.show();}
invokeCallback(opts.callbacks.exitFullscreen,dashboard,dashboard.fullscreen.currentElement);};var asynchronousRequestCounter=0;var currentReSortEvent=null;var opts=$.extend({},$.fn.dashboard.defaults,options);var throbber=$(opts.throbberMarkup).appendTo(dashboard.element);$.getJSON(opts.ajaxCallbacks.getWidgetsByColumn.url,opts.ajaxCallbacks.getWidgetsByColumn.data,init);asynchronousRequestCounter++;return dashboard;function init(widgets,status){asynchronousRequestCounter--;throbber.remove();var markup='<li class="empty-placeholder">'+opts.emptyPlaceholderInner+'</li>';var emptyDashboard=true;for(var c=0;c<opts.columns;c++){var col=dashboard.columns[c]={initialWidgets:Array(),element:$('<ul id="column-'+c+'" class="column column-'+c+'"></ul>').appendTo(dashboard.element)};col.emptyPlaceholder=$(markup).appendTo(col.element).hide();for(var id in widgets[c]){col.initialWidgets[id]=dashboard.widgets[id]=widget({id:id,element:$('<li class="widget"></li>').appendTo(col.element),initialColumn:col,minimized:(widgets[c][id]>0?true:false)});emptyDashboard=false;}}
if(emptyDashboard){emptyDashboardCondition();}
invokeCallback(opts.callbacks.init,dashboard);}
function emptyDashboardCondition(){cj(".show-refresh").hide();cj("#empty-message").show();}
function completeInit(){if(asynchronousRequestCounter>0){return;}
dashboard.sortableElement=$('.column').sortable({connectWith:['.column'],handle:'.widget-header',placeholder:'placeholder',items:'> .widget',forcePlaceholderSize:true,update:resorted,start:hideEmptyPlaceholders});dashboard.saveColumns();dashboard.ready=true;invokeCallback(opts.callbacks.ready,dashboard);}
function resorted(e,ui){if(!currentReSortEvent||e.originalEvent!=currentReSortEvent){currentReSortEvent=e.originalEvent;dashboard.saveColumns();}}
function hideEmptyPlaceholders(e,ui){for(var c in dashboard.columns){if(typeof dashboard.columns[c]=='object ')dashboard.columns[c].emptyPlaceholder.hide();}}
function invokeCallback(callback,theThis,parameterOne){if(callback){callback.call(theThis,parameterOne);}}
function widget(widget){widget=$.extend({},$.fn.dashboard.widget.defaults,widget);widget.toggleMinimize=function(){if(widget.minimized){widget.maximize();}
else{widget.minimize();}
widget.hideSettings();dashboard.saveColumns();};widget.minimize=function(){$('.widget-content',widget.element).slideUp(opts.animationSpeed);$(widget.controls.minimize.element).addClass('maximize-icon');$(widget.controls.minimize.element).removeClass('minimize-icon');widget.minimized=true;};widget.maximize=function(){$('.widget-content',widget.element).slideDown(opts.animationSpeed);$(widget.controls.minimize.element).removeClass('maximize-icon');$(widget.controls.minimize.element).addClass('minimize-icon');widget.minimized=false;};widget.toggleSettings=function(){if(widget.settings.displayed){widget.maximize();widget.hideSettings();invokeCallback(opts.widgetCallbacks.hideSettings,widget);}
else{widget.minimize();widget.showSettings();invokeCallback(opts.widgetCallbacks.showSettings,widget);}};widget.showSettings=function(){if(widget.settings.element){widget.settings.element.show();if(widget.settings.ready){getJavascript(widget.settings.script);}}
else{initSettings();}
widget.settings.displayed=true;};widget.hideSettings=function(){if(widget.settings.element){widget.settings.element.hide();}
widget.settings.displayed=false;};widget.saveSettings=function(){var params={};var fields=widget.settings.element.serializeArray();for(var i in fields){var field=fields[i];params['settings['+field.name+']']=field.value;}
widget.toggleSettings();var settingsElement=widget.settings.element;widget.settings.innerElement.empty();initThrobber();widget.settings.element=widget.throbber.hide();widget.settings.ready=false;$.extend(params,opts.ajaxCallbacks.widgetSettings.data,{id:widget.id});$.post(opts.ajaxCallbacks.widgetSettings.url,params,function(response,status){$.extend(widget.settings,response);widget.settings.element=settingsElement;widget.settings.innerElement.empty().append(widget.settings.markup);widget.settings.ready=true;if(widget.settings.displayed){widget.throbber.hide();widget.showSettings();invokeCallback(opts.widgetCallbacks.saveSettings,dashboard);}},'json');return false;};widget.enterFullscreen=function(){if(!widget.fullscreen){return;}
if(!widget.fullscreen.element){var markup='<div id="widget-'+widget.id+'-full-screen">'+widget.fullscreen+'</div>';widget.fullscreen={initialMarkup:widget.fullscreen,element:$(markup).appendTo(dashboard.element)};getJavascript(widget.fullscreenInitScript);}
dashboard.enterFullscreen(widget.fullscreen.element);getJavascript(widget.fullscreenScript);widget.fullscreen.displayed=true;};widget.exitFullscreen=function(){dashboard.exitFullscreen();};widget.addControl=function(id,control){var markup='<a class="widget-icon '+id+'-icon" alt="'+control.description+'" title="'+control.description+'"></a>';control.element=$(markup).prependTo($('.widget-controls',widget.element)).click(control.callback);};widget.reloadContent=function(){getJavascript(widget.reloadContentScript);invokeCallback(opts.widgetCallbacks.reloadContent,widget);};widget.remove=function(){if(confirm('Are you sure you want to remove "'+widget.title+'"?')){invokeCallback(opts.widgetCallbacks.remove,widget);widget.element.fadeOut(opts.animationSpeed,function(){$(this).remove();dashboard.saveColumns();});}};widget.controls={settings:{description:'Configure this dashlet',callback:widget.toggleSettings},minimize:{description:'Collapse or expand this dashlet',callback:widget.toggleMinimize},fullscreen:{description:'View this dashlet in full screen mode',callback:widget.enterFullscreen},close:{description:'Remove this dashlet from your dashboard',callback:widget.remove}};var throbber=$(opts.throbberMarkup).appendTo(widget.element);var params=$.extend({},opts.ajaxCallbacks.getWidget.data,{id:widget.id});$.getJSON(opts.ajaxCallbacks.getWidget.url,params,init);asynchronousRequestCounter++;return widget;function init(data,status){asynchronousRequestCounter--;$.extend(widget,data);if(!widget.settings){delete widget.controls.settings;}
if(!widget.fullscreen){delete widget.controls.fullscreen;}
widget.element.attr('id','widget-'+widget.id).addClass(widget.classes);throbber.remove();$(widgetHTML()).appendTo(widget.element);widget.contentElement=$('.widget-content',widget.element);$.each(widget.controls,widget.addControl);widget.minimized=!widget.minimized;widget.toggleMinimize();getJavascript(widget.initScript);invokeCallback(opts.widgetCallbacks.get,widget);completeInit();}
function widgetHTML(){var html='';html+='<div class="widget-wrapper">';html+='  <div class="widget-controls"><h3 class="widget-header">'+widget.title+'</h3></div>';html+='  <div class="widget-content">'+widget.content+'</div>';html+='</div>';return html;}
function initSettings(){initThrobber();widget.settings={element:widget.throbber.show(),ready:false};var params=$.extend({},opts.ajaxCallbacks.widgetSettings.data,{id:widget.id});$.getJSON(opts.ajaxCallbacks.widgetSettings.url,params,function(response,status){$.extend(widget.settings,response);widget.settings.element=$(widgetSettingsHTML()).appendTo($('.widget-wrapper',widget.element)).submit(widget.saveSettings);widget.settings.cancelButton=$('.widget-settings-cancel',widget.settings.element).click(cancelEditSettings);widget.settings.innerElement=$('.widget-settings-inner',widget.settings.element).append(widget.settings.markup);widget.settings.ready=true;if(widget.settings.displayed){widget.throbber.hide();widget.showSettings();}
getJavascript(widget.settings.initScript);});}
function widgetSettingsHTML(){var html='';html+='<form class="widget-settings">';html+='  <div class="widget-settings-inner"></div>';html+='  <div class="widget-settings-buttons">';html+='    <input id="'+widget.id+'-settings-save" class="widget-settings-save" value="Save" type="submit" />';html+='    <input id="'+widget.id+'-settings-cancel" class="widget-settings-cancel" value="Cancel" type="submit" />';html+='  </div>';html+='</form>';return html;}
function initThrobber(){if(!widget.throbber){widget.throbber=$(opts.throbberMarkup).appendTo($('.widget-wrapper',widget.element));}};function cancelEditSettings(){widget.toggleSettings();return false;};function getJavascript(url){if(url){$.getScript(url);}}};};$.fn.dashboard.defaults={columns:2,emptyPlaceholderInner:'There are no dashlets in this column of your dashboard.',fullscreenHeaderInner:'Back to dashboard mode',throbberMarkup:'<div class="throbber"><p class="loadtext">Loading...</p></div>',animationSpeed:200,callbacks:{},widgetCallbacks:{}};$.fn.dashboard.widget={defaults:{minimized:false,settings:false,fullscreen:false}};})(jQuery);var global_formNavigate=true;(function($){$.fn.FormNavigate=function(message){window.onbeforeunload=confirmExit;function confirmExit(event){if(global_formNavigate==true){if(event!=null){event.cancelBubble=true;}}else{return message;}}
$(this+":input[type=text], :input[type='textarea'], :input[type='password'], :input[type='radio'], :input[type='checkbox'], :input[type='file'], select").change(function(){global_formNavigate=false;});$(this+":input[type='textarea']").keyup(function(){global_formNavigate=false;});$(this+":submit").click(function(){global_formNavigate=true;});$(".token-input-list-facebook").bind("DOMNodeRemoved DOMNodeInserted",function(){global_formNavigate=false;});}})(jQuery);