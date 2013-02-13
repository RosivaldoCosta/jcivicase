(function($){var lastChecked=null;$(document).ready(function(){$('.form-checkbox').click(function(event){var isSelector=$(this).parent().parent().parent().parent().attr('class');if(isSelector!='selector'){return;}
if(!lastChecked){lastChecked=this;return;}
if(event.shiftKey){var start=$('.form-checkbox').index(this);var end=$('.form-checkbox').index(lastChecked);if(start==end){return;}
var validLastcheck=$(lastChecked).parent().parent().attr('class');var validthischeck=$(this).parent().parent().attr('class');var params=new Array("listing-box","columnheader","sticky","");for(i=0;i<params.length;i++){if(params[i]==validLastcheck||params[i]==validthischeck){return;}}
var min=Math.min(start,end);var max=Math.max(start,end);if(lastChecked.checked&&this.checked){lastChecked.checked=true;}else if(lastChecked.checked&&!this.checked){lastChecked.checked=false;}else if(!lastChecked.checked&&this.checked){lastChecked.checked=true;}else if(!lastChecked.checked&&!this.checked){lastChecked.checked=false;}
for(i=min;i<=max;i++){$('.form-checkbox')[i].checked=lastChecked.checked;}
$('.selector tbody tr td:first-child input:checkbox').each(function(){var oldClass=$(this).parent().parent().attr('class');if(this.checked){$(this).parent().parent().removeClass().addClass('row-selected '+oldClass);}else{var lastClass=$(this).parent().parent().attr('class');var str=lastClass.toString().substring(12);if(lastClass.substring(0,12)=="row-selected"){$(this).parent().parent().removeClass().addClass(str);}}});}
lastChecked=this;});});})(jQuery);
