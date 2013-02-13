
////////////JS Script FOR ChatBar

var mychat=new Array();
var mychatuname=new Array();

var chatinfoid=new Array();
var chatinfouname=new Array();
var chatinfoname=new Array();
var counterb=0;

var activebuttons = new Array();
var fbchatbar = 1;
var tempurlic = '';
//For Slider
var negativemargin=0;
var calculateclicks=0;
var currentchatter;

jQuery(document).ready(function(){

	jQuery('.jfb_icons:not(.jfb_icons_mod)').hover(mouseinicons,mouseouticons);
	if(gsCookie("jbactive")!=null)
	var orderarr = gsCookie("jbactive").split(",");
	else
	var orderarr = new Array();
//		console.log(orderarr);
	for(var i=0;i<orderarr.length;i++)
	for(var k=0;k<currentonlineunames.length;k++)
	{

//		if(readCookie("jb"+currentonlineunames[k]))
		//{
	//	var cookieinfo = readCookie("jb"+currentonlineunames[k]);
			if(currentonlineid[k]==orderarr[i])
			{

			chatWith(currentonlineid[k],1);
			continue;
			}
		//}
	}
	if(slideroptions==2 || slideroptions==3 || slideroptions==4)
	{
		var slidercal=113.5*slideroptions;
		jQuery("#jfb_stage").css({width: slidercal+'px'});
	}
	
	});

function mouseinicons()
{
	var src = jQuery(this).find('img').attr("src");
	tempurlic=src;
	var src1 = src.split('/');
	var src2 = src1[src1.length-1].split('.'); 
	src2[0] += '-hover.';
	src1[src1.length-1] = src2[0]+src2[1];
	src = src1.join('/');
	jQuery(this).find('img').attr("src",src);
}
function mouseouticons()
{
	jQuery(this).find('img').attr("src",tempurlic);
}

/////Slider Functions
function slidern()
{	
	if(jQuery('#jfb_imgn'))
	{
		jQuery('#jfb_imgn').hide();
	}
	calculateclicks++;
	if(mychat.length-calculateclicks>slideroptions-1)
	{
		for(var i=0;i<mychat.length;i++)		
			{
				jQuery('#'+mychat[i]+'_span').hide();
			}
		/*jQuery("#jfb_next").click(function(){
		  jQuery(".animateclass").animate({"left": "+=112.5px"}, "slow");
		});*/
		
		
		activebuttons.splice(0,activebuttons.length);
		for(var k=0;k<slideroptions;k++)
		{
			if(mychat[calculateclicks+k]!=undefined && jQuery('#'+mychat[calculateclicks+k]+'_span'))
			{
				jQuery('#'+mychat[calculateclicks+k]+'_span').show();

				activebuttons[k]=(mychat[calculateclicks+k]);
			}
		}
		//////////////////////////////
		var activeno=0;
		for(var k=0;k<activebuttons.length;k++)
		{
			if(activebuttons[k]==currentchatter)
			{
				activeno=k;	
			}
		}
		activeno = activeno * 113.5;
		
		if(counterb>slideroptions)
		{
			var calcmargin=291+activeno;
		}
		else
		{
			var calcmargin=269+jfb_theme+activeno;
		}
		jQuery(".chatbox").css("margin-right",""+calcmargin+"px");
		/////////////////////////////
		
		var flag=0;
		for(var k=0;k<slideroptions;k++)
		{
			if(currentchatter==mychat[calculateclicks+k])
			{
				var flag=1;
			}
		}
		
		if(flag==1)
		{
			chatWith(currentchatter);
		}
		else
		{
			closeChatBox(currentchatter);
			currentchatter='';
		}

	}
	else
	{
		calculateclicks--;
	}	
}


function sliderp()
{
	if(jQuery('#jfb_imgp').length>0)
	{
		jQuery('#jfb_imgp').hide();
	}
	if(calculateclicks>0)
	{
		calculateclicks--;
	}

		for(var i=1;i<mychat.length;i++)		
		{
			jQuery('#'+mychat[i]+'_span').hide();
		}
		
		
		activebuttons.splice(0,activebuttons.length);	
		for(var k=0;k<slideroptions;k++)
		{
			if(mychat[calculateclicks+k]!=undefined &&jQuery('#'+mychat[calculateclicks+k]+'_span').length>0)
			{
				jQuery('#'+mychat[calculateclicks+k]+'_span').show();
				activebuttons[k]=(mychat[calculateclicks+k]);
			}	
		}
		
		//////////////////////////////
		var activeno=0;
		for(var k=0;k<activebuttons.length;k++)
		{
			if(activebuttons[k]==currentchatter)
			{
				activeno=k;	
			}
		}
		activeno = activeno * 113.5;
		
		if(counterb>slideroptions)
		{
			var calcmargin=291+activeno;
		}
		else
		{
			var calcmargin=269+jfb_theme+activeno;
		}
		jQuery(".chatbox").css("margin-right",""+calcmargin+"px");
		/////////////////////////////
		
		var flag=0;
		for(var k=0;k<slideroptions;k++)
		{
			if(currentchatter==mychat[calculateclicks+k])
			{
				var flag=1;
			}
		}
		
		if(flag==1)
		{
			chatWith(currentchatter);
		}
		else
		{
			hideChatBox(currentchatter);
			currentchatter='';
		}

}


//Cookie Functions
function createCookie(name,value,days) 
{
	if (days)
	{
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";

	document.cookie = name+"="+value+expires+"; path=/";
}



function readCookie(name) 
{
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) 
	{
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) 
{
	createCookie(name,"",-1);
}

//To Hide the Buttons
function hidediv(id)
{

	var value;
	if(value==null)
	{
		for(var k=0;k<chatinfoid.length;k++)
		{
			if(chatinfoid[k]==id)
			{
				value = chatinfouname[k];
			}
		}
	}
	jQuery('#jfb_myList').children('#'+id+'_span').remove();

	unamearr = value.split(" ");
	if(unamearr[1])
	{
		value = unamearr[0] + unamearr[1];
	}
	
	for(var i=0;i<mychat.length;i++)
	{
		if(mychat[i]==id)
		{
			var indexno=i;	
		}
	}	
	for(var k=0;k<activebuttons.length;k++)
	{
		if(activebuttons[k]==id)
		{
			var activeno=k;	
		}
	}
	activebuttons.splice(activeno,1);
	mychat.splice(indexno,1); //Remove button-user id from array
	mychatuname.splice(indexno,1);
	
	//////////////////////////////
	var activeno=0;
	for(var k=0;k<activebuttons.length;k++)
	{
		if(activebuttons[k]==currentchatter)
		{
			activeno=k;	
		}
	}
	activeno = activeno * 113.5;
	
	if(counterb>slideroptions)
	{
		var calcmargin=291+activeno;
	}
	else
	{
		var calcmargin=269+jfb_theme+activeno;
	}
	jQuery(".chatbox").css("margin-right",""+calcmargin+"px");
	/////////////////////////////
	if(id==currentchatter)
	{
		closeChatBox(id);
		currentchatter='';
	}
	eraseCookie(value);
	counterb--;
	
	if(counterb>slideroptions)
	{
		jQuery('#jfb_previous').show();
		jQuery('#jfb_next').show();
	//	sliderp();
	}
	else
	{
		//sliderp();
		jQuery('#jfb_previous').hide();
		jQuery('#jfb_next').hide();
	}

}

//Calling this function from JBolo to change the color of the button when user closes the window
function callFromChat(id)
{
	var idfordiv=id;
	idfordiv+='_div';
	currentchatter='';
	if(jQuery('#'+idfordiv))
	{
		jQuery('#'+idfordiv).attr('class','jfb_chat fbar1');
	}
}

//When User clicks on the Button
function chatclosehandler(id)
{

	for(i=0;i<mychat.length;i++)
	{
		if(mychat[i]==id)
		{
			continue;	
		}
		else
		{
			jQuery('#'+'chatbox_'+mychat[i]).hide();	
		}
	}

	var idfordiv=id;
	idfordiv+='_div';

	for(i=0;i<mychat.length;i++)
	{
		if(jQuery(mychat[i])+'_div')
		{
			jQuery('#'+mychat[i]+'_div').attr('class','jfb_chat fbar1');
		}
	}
	jQuery('#'+idfordiv).attr('class','jfb_chat fbar1');
//To restructure chat boxes
	
	var activeno=0;
	for(var k=0;k<activebuttons.length;k++)
	{
		if(activebuttons[k]==id)
		{
			activeno=k;	
		}
	}
	activeno = activeno * 113.5;
	
	if(counterb>slideroptions)
	{
		var calcmargin=291+activeno;
	}
	else
	{
		var calcmargin=269+jfb_theme+activeno;
	}
	jQuery(".chatbox").css("margin-right",""+calcmargin+"px");
//Conditions to check the current status of Chat Box for that particular ID
	var flag=0;
	var id1=jQuery('#chatbox_'+id).css("display");

	if(id1=='none')
	{
		flag=0;
	}
	else
	{
		flag=1;
	}

//Action according to the above conditions
	if(flag==0)
	{
		jQuery('#'+id+'_img').hide();
		jQuery('#'+id+'_img').children().children().html(1);
		chatWith(id);
		currentchatter=id;
		jQuery("#chatbox_"+id+" .chatboxcontent").scrollTop(jQuery("#chatbox_"+id+" .chatboxcontent")[0].scrollHeight);
	}
	else
	{
		currentchatter='';
		hideChatBox(id);
	}
}


function jbolohandler(id,silent)
{
	var value,uname;
	var flag = 0;
	if(chatinfoid.length>0)
	{
		for(var k=0;k<chatinfoid.length;k++)
		{
			if(chatinfoid[k]==id)
			{
				uname = chatinfouname[k];
				realname = chatinfoname[k];
				flag = 1;
			}
		}
	}
	
	if(flag==0)
	{
		jQuery.ajax({
			  url: jb_abs_link+"index.php?option=com_jbolo&action=getinfo&uid="+id,
				
			  cache: false,
			  dataType: "json",
			  success: function(udetails) { 
			   	uname = udetails.username;
			   	realname = udetails.name;

			   	chatinfoid.push(id);
			   	chatinfouname.push(uname); 
			   	chatinfoname.push(realname);
				jbolocall(id,uname,realname,silent);
				}
			});
	}
	else
	{
		jbolocall(id,uname,realname,silent);
	}
}

//Chat initiated by other user
function jbolocall(id,uname,realname,silent)
{
	var checkerforscroll=counterb;
	var i=0;
	var j=0;
	for(i=0;i<mychat.length;i++)
	{
		if(mychat[i] == id)
		{
			j = 1;
		}
	}
	if(silent===undefined)
	silent = false;
	unamearr = uname.split(" ");
	if(unamearr[1])
	{
		uname = unamearr[0] + unamearr[1];
	}
	
	var idfordiv=id;
	idfordiv+='_div';

	var spanid=id;
	spanid+='_span';
	
	var imgid=id;
	imgid+='_img';

	if(j==0)
	{
		var oldHTML = document.getElementById('jfb_myList').innerHTML;
		var newHTML = oldHTML + "<span id='"+spanid+"'><div class='jfb_chat' id='"+idfordiv+"'><div class='jfb_insidechat'><a href=javascript:void(0) onclick=javascript:chatclosehandler('"+id+"')>" + realname + "</a><div id='"+imgid+"' class='jfb_imgcss' ><div class='poptext_wrap'><div class='poptext'>1</div></div></div></div><div class=jfb_chatclose><a href=javascript:void(0) onclick=javascript:hidediv('"+id+"')>&nbsp;</a></div></div></span>";
		document.getElementById('jfb_myList').innerHTML = newHTML;
		mychat.push(id);
		mychatuname.push(uname);
		activebuttons.push(id);
		counterb++;
	}

	//Cookie
	for(var k=0;k<mychatuname.length;k++)
	{
		createCookie("jb"+mychatuname[k],'chatboxbuttonsstatus' + k);
	
	}

	if(currentchatter==id)
	{
		jQuery('#'+idfordiv).attr("class",'jfb_chat fbar3');
		jQuery('#'+id+'_img').hide();
		currentchatter=id;
	}
	else
	{
		closeChatBox(id);

		currentchatter='';
		var idinactive = 0;
		for(var k=0;k<activebuttons.length;k++)
		{		
			if(activebuttons[k] == id)
			{
				idinactive = 1;
			}
	}

	if(uname != null)
	{	
		for(var k=0;k<mychat.length;k++)
		{
			if(idinactive==0)
			{
				if(mychat[k]==id)
				{	
					if(k>activebuttons.length-1)
					{
						jQuery('#jfb_imgn').show();
					}
					else
					{
					jQuery('#jfb_imgp').show();
					}
				}
			}
		}
		
		if(!silent)
		{
		if(jQuery('#'+id+'_img').css('display')=='block')
		{
		var poptext = jQuery('#'+id+'_img').children().children();
		poptext.html(parseInt(poptext.html())+1);
		}
		jQuery('#'+id+'_img').show();
		}
	}
}


	if(counterb>slideroptions)
		{
	
			if(counterb>checkerforscroll)
			{
				slidern();
			}
			jQuery('#jfb_previous').show();
			jQuery('#jfb_next').show();
		}
	else
		{
			jQuery('jfb_previous').hide();
			jQuery('jfb_next').hide();
		}
}


//When chatbox users are clicked
function chatWith(id,instance)
{

	var realname,uname;
	var flag = 0;
	if(chatinfoid.length>0)
	{
		for(var k=0;k<chatinfoid.length;k++)
		{
			if(chatinfoid[k]==id)
			{
				uname = chatinfouname[k];
				realname = chatinfoname[k];
				flag = 1;
			}
		}
	}
	
	if(flag==0)
	{
		jQuery.ajax({
			  url: jb_abs_link+"index.php?option=com_jbolo&action=getinfo&uid="+id,
			  cache: false,
			  dataType: "json",
			  success: function(udetails) { 
	
			   	uname = udetails.username;
			   	realname = udetails.name;
			   	chatinfoid.push(id);
			   	chatinfouname.push(uname); 
			   	chatinfoname.push(realname);
			   	chatuserhandler(id,uname,realname,instance);
					createActiveCookie();
				}
			});
	}
	else
	{
		chatuserhandler(id,uname,realname,instance);
		createActiveCookie();
	}
	  
}
	
function chatuserhandler(id,uname,realname,instance)
{
	var checkerforscroll=counterb;

	if(mychat.length>0)
	{
		for(var k=0;k<mychat.length;k++)
		{
			if(mychat[k]==id)
			{
				continue;
			}
			else
			{
				closeChatBox(mychat[k]);
			}
		}
	}
	currentchatter=id;
	chatWithjs(id);

	jQuery("#chatbox_"+id+" .chatboxcontent").scrollTop(jQuery("#chatbox_"+id+" .chatboxcontent")[0].scrollHeight);
	unamearr = uname.split(" ");
	if(unamearr[1])
	{
		uname = unamearr[0] + unamearr[1];
	}
	
	var i=0;
	var j=0;
	for(i=0;i<mychat.length;i++)
	{
		if(mychat[i] == id)
		{
			j = 1;
		}
	}

	var idfordiv=id;
	idfordiv+='_div';

	var spanid=id;
	spanid+='_span';
	
	var imgid=id;
	imgid+='_img';

	if(j==0)
	{
		var oldHTML = document.getElementById('jfb_myList').innerHTML;
		var newHTML = oldHTML + "<span id='"+spanid+"'><div class='jfb_chat' id='"+idfordiv+"'><div class='jfb_insidechat'><a href=javascript:void(0) onclick=javascript:chatclosehandler('"+id+"')>" + realname + "</a><div class='jfb_imgcss' id='"+imgid+"'><div class='poptext_wrap'><div class='poptext'>1</div></div></div></div><div class=jfb_chatclose><a href=javascript:void(0) onclick=javascript:hidediv('"+id+"')>&nbsp;</a></div></div></span>";
		document.getElementById('jfb_myList').innerHTML = newHTML;
		mychat.push(id);
		mychatuname.push(uname);
		activebuttons.push(id);
		counterb++;
	}

//Cookie
for(var k=0;k<mychatuname.length;k++)
{
	createCookie("jb"+mychatuname[k],'chatboxbuttonsstatus' + k);
}

//CSS
for(i=0;i<mychat.length;i++)
{
	if(jQuery('#'+mychat[i]+'_div'))
	{
		jQuery('#'+mychat[i]+'_div').attr('class','jfb_chat fbar1');
	}
}

	jQuery('#'+idfordiv).attr('class','jfb_chat fbar2');	


	for(var k=0;k<currentonlineid[k];k++)
	{
		if(currentonlineid[k]==id)
		{
			var indexn1=k;
		}
	}

	if(instance==1)
	{
		chatclosehandler(id);
	}
	
	if(counterb>slideroptions)
		{
	
			if(counterb>checkerforscroll)
			{
				slidern();
			}
			
			var activeno=0;
			for(var k=0;k<activebuttons.length;k++)
			{
				if(activebuttons[k]==id)
				{
					activeno=k;	
				}
			}
			activeno = activeno * 113.5;
			var calcmargin=291+activeno;
			jQuery(".chatbox").css("margin-right",""+calcmargin+"px");
			jQuery('#jfb_previous').show();
			jQuery('#jfb_next').show()

			//Display buttons on chatuser handler			
			var counterbnew=counterb;
			var buttonstat = jQuery('#'+id+'_span').css("display");
			while(buttonstat=='none')
			{
				buttonstat = jQuery('#'+id+'_span').css("display");
				if(counterbnew>0)
				{
					slidern();
					counterbnew--;
				}
				if(counterbnew==0)
				{
					sliderp();
				}
			}
		}
	else
		{
			var activeno=0;
			for(var k=0;k<activebuttons.length;k++)
			{
				if(activebuttons[k]==id)
				{
					activeno=k;	
				}
			}
				activeno = activeno * 113.5;
				var calcmargin=269+jfb_theme+activeno;
				jQuery(".chatbox").css("margin-right",""+calcmargin+"px");
				jQuery('#jfb_previous').hide();
				jQuery('#jfb_next').hide();
		}
	if(isChatMin(id)==0)
		{
		jQuery("#2_div").addClass("fbar2");
		showChatbox(id);
		}
}

//For Chat and Activities Box
function handler(szDivID,iState)
{

	var caState=boxState('jfb_actvty');
	var ccState=boxState('jfb_chatbx');

	if(caState==undefined)
	{
		caState=0;
	}
	if(ccState==undefined)
	{
		ccState=0;
	}
	
	if(szDivID=='jfb_chatbx'&& iState==1 && caState==1)
	{
		toggleBox('jfb_actvty', 0);
		toggleBox('jfb_chatbx', 1);
		jQuery('#jfb_chatactive').attr('class','jfb_normal fbar4');
		jQuery('#jfb_actactive').attr('class','jfb_normal fbar5');
	}
	
	if(szDivID=='jfb_chatbx'&& iState==1 && caState==0)
	{
		toggleBox('jfb_chatbx', 1);
		jQuery('#jfb_chatactive').attr('class','jfb_normal fbar4');
	}

	if(szDivID=='jfb_actvty'&& iState==1 && caState==0)
	{
		toggleBox('jfb_actvty', 1);
		jQuery('#jfb_actactive').attr('class','jfb_normal fbar6');
		jQuery('#jfb_actvty').show();
	}
	
	if(szDivID=='jfb_actvty'&& iState==1 && caState==1)
	{
		toggleBox('jfb_actvty', 0);
		jQuery('#jfb_actactive').attr('class','jfb_normal fbar5');
	}

	if(szDivID=='jfb_actvty'&& iState==0 && caState==1)
	{
		toggleBox('jfb_actvty', 0);
		jQuery('#jfb_actactive').attr('class','jfb_normal fbar5');
	}

	if(szDivID=='jfb_chatbx'&& iState==1 && ccState==1)
	{
		toggleBox('jfb_chatbx', 0);
		jQuery('#jfb_chatactive').attr('class','jfb_normal fbar5');
	}

	if(szDivID=='jfb_chatbx'&& iState==0 && ccState==1)
	{
		toggleBox('jfb_chatbx', 0);
		jQuery('#jfb_chatactive').attr('class','jfb_normal fbar5');
	}
	
	if(szDivID=='jfb_chatbx'&& iState==1 && ccState==0)
	{
		toggleBox('jfb_chatbx', 1);
		jQuery('#jfb_chatactive').attr('class','jfb_normal fbar4');
	}

	if(szDivID=='jfb_actvty'&& iState==1 && ccState==1)
	{
		toggleBox('jfb_chatbx', 0);
		toggleBox('jfb_actvty', 1);
		jQuery('#jfb_actvty').show();
		jQuery('#jfb_chatactive').attr('class','jfb_normal fbar5');
		jQuery('#jfb_actactive').attr('class','jfb_normal fbar4');
	}
	
}

//Toggle the box according to passed variables i.e. 1 or 0
function toggleBox(szDivID, iState) // 1 visible, 0 hidden
{
		
		if(iState)
		jQuery("#"+szDivID).css("visibility","visible");
		else
		jQuery("#"+szDivID).css("visibility","hidden");
		
}


//Get current box State
function boxState(szDivID) // 1 visible, 0 hidden
{
		var iState = jQuery("#"+szDivID).css("visibility");
		return iState=='visible' ? 1 : 0;
}

function createActiveCookie()
{
	var spanlist = jQuery("#jfb_myList");
	var spanlistchild = spanlist.children();
	var cookiestr = '';
	spanlistchild.each(function(i){
															cookiestr += jQuery(this).attr('id').replace(/(\d*)_span/gi, '$1');
															
															if(i+1!=spanlistchild.length)
																cookiestr +=',';
															});
	gsCookie("jbactive",cookiestr);
}

function readActiveCookie()
{
/*	var cookiestr = gsCookie("jbactive");
	var cookiearr = cookiestr.split(","); 
	var cookiearrfin = array();
	for(var i =0;i<cookiearr.length;i++)
		{
			if(cookiearr!='')
			cookiearrfin.push(cookiearr[i]);
		}
	 return cookiearrfin.length>0 ? cookiearrfin : false;*/
}
