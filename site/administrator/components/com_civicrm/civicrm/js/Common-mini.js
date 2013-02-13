function clearFldVal(fld){if(fld.value==fld.defaultValue){fld.value="";}}
function on_load_init_blocks(showBlocks,hideBlocks,elementType)
{if(elementType==null){var elementType='block';}
for(var i=0;i<showBlocks.length;i++){var myElement=document.getElementById(showBlocks[i]);if(myElement!=null){myElement.style.display=elementType;}else{alert('showBlocks array item not in .tpl = '+showBlocks[i]);}}
for(var i=0;i<hideBlocks.length;i++){var myElement=document.getElementById(hideBlocks[i]);if(myElement!=null){myElement.style.display='none';}else{alert('showBlocks array item not in .tpl = '+hideBlocks[i]);}}}
function showHideByValue(trigger_field_id,trigger_value,target_element_id,target_element_type,field_type,invert){if(target_element_type==null){var target_element_type='block';}else if(target_element_type=='table-row'){var target_element_type='';}
if(field_type=='select'){var trigger=trigger_value.split("|");var selectedOptionValue=document.getElementById(trigger_field_id).options[document.getElementById(trigger_field_id).selectedIndex].value;var target=target_element_id.split("|");for(var j=0;j<target.length;j++){if(invert){show(target[j],target_element_type);}else{hide(target[j],target_element_type);}
for(var i=0;i<trigger.length;i++){if(selectedOptionValue==trigger[i]){if(invert){hide(target[j],target_element_type);}else{show(target[j],target_element_type);}}}}}else if(field_type=='radio'){var target=target_element_id.split("|");for(var j=0;j<target.length;j++){if(document.getElementsByName(trigger_field_id)[0].checked){if(invert){hide(target[j],target_element_type);}else{show(target[j],target_element_type);}}else{if(invert){show(target[j],target_element_type);}else{hide(target[j],target_element_type);}}}}}
function enableDisableByValue(trigger_field_id,trigger_value,target_element_id,target_element_type,field_type,invert){if(target_element_type==null){var target_element_type='block';}else if(target_element_type=='table-row'){var target_element_type='';}
if(field_type=='select'){var trigger=trigger_value.split("|");var selectedOptionValue=document.getElementById(trigger_field_id).options[document.getElementById(trigger_field_id).selectedIndex].value;var target=target_element_id.split("|");for(var j=0;j<target.length;j++){if(document.getElementById(target[j])){if(invert){document.getElementById(target[j]).disabled=false;}else{document.getElementById(target[j]).disabled=true;}}
for(var i=0;i<trigger.length;i++){if(selectedOptionValue==trigger[i]){if(document.getElementById(target[j])){if(invert){document.getElementById(target[j]).disabled=true;}else{document.getElementById(target[j]).disabled=false;}}}}}}else if(field_type=='radio'){var target=target_element_id.split("|");for(var j=0;j<target.length;j++){if(document.getElementsByName(trigger_field_id)[0].checked){if(document.getElementById(target[j])){if(invert){document.getElementById(target[j]).disabled=true;}else{document.getElementById(target[j]).disabled=false;}}}else{if(document.getElementById(target[j])){if(invert){document.getElementById(target[j]).disabled=false;}else{document.getElementById(target[j]).disabled=true;}}}}}}
function resetByValue(trigger_field_id,trigger_value,target_element_id,target_field_type,field_type,invert){if(field_type=='select'){var trigger=trigger_value.split("|");var selectedOptionValue=document.getElementById(trigger_field_id).options[document.getElementById(trigger_field_id).selectedIndex].value;var target=target_element_id.split("|");for(var j=0;j<target.length;j++){for(var i=0;i<trigger.length;i++){if(invert){if(selectedOptionValue==trigger[i]){if(target_field_type=='radio'){if(document.getElementsByName(target[j])){for(var i=0;i<document.getElementsByName(target[j]).length;i++){if(document.getElementsByName(target[j])[i].checked){document.getElementsByName(target[j])[i].checked=null;}}}}else{if(document.getElementById(target[j])){document.getElementById(target[j]).value="";}}}}else{if(selectedOptionValue!=trigger[i]){if(target_field_type=='radio'){if(document.getElementsByName(target[j])){for(var i=0;i<document.getElementsByName(target[j]).length;i++){if(document.getElementsByName(target[j])[i].checked){document.getElementsByName(target[j])[i].checked=null;}}}}else{if(document.getElementById(target[j])){document.getElementById(target[j]).value="";}}}}}}}else if(field_type=='radio'){var target=target_element_id.split("|");for(var j=0;j<target.length;j++){if(invert){if(document.getElementsByName(trigger_field_id)[0].checked){if(target_field_type=='radio'){if(document.getElementsByName(target[j])){for(var i=0;i<document.getElementsByName(target[j]).length;i++){if(document.getElementsByName(target[j])[i].checked){document.getElementsByName(target[j])[i].checked=null;}}}}else{if(document.getElementById(target[j])){document.getElementById(target[j]).value="";}}}}else{if(!document.getElementsByName(trigger_field_id)[0].checked){if(target_field_type=='radio'){if(document.getElementsByName(target[j])){for(var i=0;i<document.getElementsByName(target[j]).length;i++){if(document.getElementsByName(target[j])[i].checked){document.getElementsByName(target[j])[i].checked=null;}}}}else{if(document.getElementById(target[j])){document.getElementById(target[j]).value="";}}}}}}}
function show(block_id,elementType)
{if(elementType==null){var elementType='block';}else if(elementType=="table-row"&&navigator.appName=='Microsoft Internet Explorer'){var elementType="block";}
var myElement=document.getElementById(block_id);if(myElement!=null){myElement.style.display=elementType;}else{alert('Request to show() function failed. Element id undefined = '+block_id);}}
function hide(block_id)
{var myElement=document.getElementById(block_id);if(myElement!=null){myElement.style.display='none';}else{alert('Request to hide() function failed. Element id undefined = '+block_id);}}
function toggleCheckboxVals(fldPrefix,object){if(object.id=='toggleSelect'&&cj(object).is(':checked')){cj('Input[id*="'+fldPrefix+'"],Input[id*="toggleSelect"]').attr('checked',true);}else{cj('Input[id*="'+fldPrefix+'"],Input[id*="toggleSelect"]').attr('checked',false);}
on_load_init_checkboxes(object.form.name);}
function countSelectedCheckboxes(fldPrefix,form){fieldCount=0;for(i=0;i<form.elements.length;i++){fpLen=fldPrefix.length;if(form.elements[i].type=='checkbox'&&form.elements[i].name.slice(0,fpLen)==fldPrefix&&form.elements[i].checked==true){fieldCount++;}}
return fieldCount;}
function toggleTaskAction(status){var radio_ts=document.getElementsByName('radio_ts');if(!radio_ts[1]){radio_ts[0].checked=true;}
if(radio_ts[0].checked||radio_ts[1].checked){status=true;}
var formElements=['task','Go','Print'];for(var i=0;i<formElements.length;i++){var element=document.getElementById(formElements[i]);if(element){if(status){element.disabled=false;}else{element.disabled=true;}}}}
function checkPerformAction(fldPrefix,form,taskButton){var cnt;var gotTask=0;if(taskButton==1){gotTask=1;}else if(document.forms[form].task.selectedIndex){if(document.forms[form].task.value==13||document.forms[form].task.value==14||document.forms[form].task.value==20){var toggleSelect=document.getElementsByName('toggleSelect');if(toggleSelect[0].checked||document.forms[form].radio_ts[0].checked){return true;}else{alert("Please select all contacts for this action.\n\nTo use the entire set of search results, click the 'all records' radio button.");return false;}}
gotTask=1;}
if(gotTask==1){if(document.forms[form].radio_ts[0].checked){return true;}
cnt=countSelectedCheckboxes(fldPrefix,document.forms[form]);if(!cnt){alert("Please select one or more contacts for this action.\n\nTo use the entire set of search results, click the 'all records' radio button.");return false;}}else{alert("Please select an action from the drop-down menu.");return false;}}
function checkSelectedBox(chkName,form)
{var ss=document.forms[form].elements[chkName].name.substring(7,document.forms[form].elements[chkName].name.length);var row='rowid'+ss;if(document.forms[form].elements[chkName].checked==true){document.forms[form].radio_ts[1].checked=true;if(document.getElementById(row).className=='even-row'){document.getElementById(row).className='row-selected even-row';}else{document.getElementById(row).className='row-selected odd-row';}}else{if(document.getElementById(row).className=='row-selected even-row'){document.getElementById(row).className='even-row';}else if(document.getElementById(row).className=='row-selected odd-row'){document.getElementById(row).className='odd-row';}}}
function on_load_init_checkboxes(form)
{var formName=form;var fldPrefix='mark_x';for(i=0;i<document.forms[formName].elements.length;i++){fpLen=fldPrefix.length;if(document.forms[formName].elements[i].type=='checkbox'&&document.forms[formName].elements[i].name.slice(0,fpLen)==fldPrefix){checkSelectedBox(document.forms[formName].elements[i].name,formName);}}}
function changeRowColor(rowid,form){switch(document.getElementById(rowid).className){case'even-row':document.getElementById(rowid).className='selected even-row';break;case'odd-row':document.getElementById(rowid).className='selected odd-row';break;case'selected even-row':document.getElementById(rowid).className='even-row';break;case'selected odd-row':document.getElementById(rowid).className='odd-row';break;case'form-item':document.getElementById(rowid).className='selected';break;case'selected':document.getElementById(rowid).className='form-item';}}
function on_load_init_check(form)
{for(i=0;i<document.forms[form].elements.length;i++){if((document.forms[form].elements[i].type=='checkbox'&&document.forms[form].elements[i].checked==true)||(document.forms[form].elements[i].type=='hidden'&&document.forms[form].elements[i].value==1)){var ss=document.forms[form].elements[i].id;var row='rowid'+ss;changeRowColor(row,form);}}}
function unselectRadio(fieldName,form)
{var elems=document.forms[form].elements;if(typeof elems==='undefined')
{elems=document.forms[form][1].elements}
if(typeof elems!='undefined')
{for(i=0;i<elems.length;i++){if(elems[i].name==fieldName){elems[i].checked=false;}}}
return;}
var submitcount=0;function submitOnce(obj,formId,procText){if(obj.value!=null){obj.value=procText+" ...";}
if(document.getElementById){obj.disabled=true;document.getElementById(formId).submit();return true;}
else{if(submitcount==0){submitcount++;return true;}else{alert("Your request is currently being processed ... Please wait.");return false;}}}
function submitCurrentForm(formId,targetPage){alert(formId+' '+targetPage);document.getElementById(formId).targetPage.value=targetPage;document.getElementById(formId).submit();}
function countit(essay_id,wc){var text_area=document.getElementById("essay_"+essay_id);var count_element=document.getElementById("word_count_"+essay_id);var count=0;var text_area_value=text_area.value;var regex=/\n/g;var essay=text_area_value.replace(regex," ");var words=essay.split(' ');for(z=0;z<words.length;z++){if(words[z].length>0){count++;}}
count_element.value=count;if(count>=wc){var dataString='';for(z=0;z<wc;z++){if(words[z].length>0){dataString=dataString+words[z]+' ';}}
text_area.value=dataString;text_area.blur();count=wc;count_element.value=count;alert("You have reached the "+wc+" word limit.");}}
function popUp(URL){day=new Date();id=day.getTime();eval("page"+id+" = window.open(URL, '"+id+"', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=640,height=420,left = 202,top = 184');");}
function executeInnerHTML(elementName)
{var element=document.getElementById(elementName);var content=element.getElementsByTagName('script');var tagLength=content.length;for(var x=0;x<tagLength;x++){var newScript=document.createElement('script');newScript.type="text/javascript";newScript.text=content[x].text;element.appendChild(newScript);}
for(var y=0;y<tagLength-1;y++){element.removeChild(element.getElementsByTagName('script')[y]);}}
function imagePopUp(path)
{window.open(path,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,screenX=150,screenY=150,top=150,left=150');}
function showHideRow(index)
{if(index){cj('tr#optionField_'+index).hide();if(cj('table#optionField tr:hidden:first').length)cj('div#optionFieldLink').show();}else{cj('table#optionField tr:hidden:first').show();if(!cj('table#optionField tr:hidden:last').length)cj('div#optionFieldLink').hide();}
return false;}
function activityStatus(message)
{var d=new Date(),time=[],i;var currentDateTime=d.getTime()
var activityTime=cj("input#activity_date_time_time").val().replace(":","");for(i=0;i<activityTime.length;i+=2){time.push(activityTime.slice(i,i+2));}
var activityDate=new Date(cj("input#activity_date_time_hidden").val());d.setFullYear(activityDate.getFullYear());d.setMonth(activityDate.getMonth());d.setDate(activityDate.getDate());var hours=time['0'];var ampm=time['2'];if(ampm=="PM"&&hours!=0&&hours!=12){hours=hours*1+12;}else if(ampm=="AM"&&hours==12){hours=0;}
d.setHours(hours);d.setMinutes(time['1']);var activity_date_time=d.getTime();var activityStatusId=cj('#status_id').val();if(activityStatusId==2&&currentDateTime<activity_date_time){if(!confirm(message.completed)){return false;}}else if(activity_date_time&&activityStatusId==1&&currentDateTime>=activity_date_time){if(!confirm(message.scheduled)){return false;}}}
