;(function($){jQuery.fn.chainSelect=function(target,url,settings)
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
{$.get(url,settings.parameters,ajaxCallback);}});});};})(jQuery);
