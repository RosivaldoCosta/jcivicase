{* include jscript to handle auto calculating points *}
<script type="text/javascript">
//<![CDATA[
   var caseTypeId = '{$caseTypeId}';
   var isErrorValidation = cj('ul#errorList').length > 0;
{literal}
  jQuery(document).ready(function($)
  {
	// Dispatch Date/Time
	//cj('input:regex(name,^custom_650_\\d_time$)').live('change',function () {
	cj('input[name^=custom_650_].hasTimeEntry').live('change',function () {
		// Calculate Total Travel Time
		var t =	  autoCalcDiff(this,'input[name^=custom_648_].hasTimeEntry' );
       		cj('input[name^=custom_1658_]').attr('value',t);

		// Calculate Total Time
		var tt = autoCalcTotal();
       		cj('input[name^=custom_653_]').attr('value',tt);

      	});

	// Arrival Date/Time
	//cj('input:regex(name,^custom_648_\\d_time$).hasTimeEntry').live('change',function () {
	cj('input[name^=custom_648_].hasTimeEntry').live('change',function () {
		// Calculate Total Travel Time
		var t =	  autoCalcDiff('input[name^=custom_650_].hasTimeEntry',this);
       		cj('input[name^=custom_1658_]').attr('value',t);

		// Calculate Total Call Time
		var ct =  autoCalcDiff(this,'input[name^=custom_649_].hasTimeEntry');
       		cj('input[name^=custom_1659_]').attr('value',ct);

		// Calculate Total Time
		var tt = autoCalcTotal();
       		cj('input[name^=custom_653_]').attr('value',tt);
      	});

	// End Date/Time
	//cj('input:regex(name,^custom_649_\\d_time$).hasTimeEntry').live('change',function () {
	cj('input[name^=custom_649_].hasTimeEntry').live('change',function () {
		// Calculate Total Call Time
		var t = autoCalcDiff('input[name^=custom_650_].hasTimeEntry',this);
       		cj('input[name^=custom_1659_]').attr('value',t);

		// Calculate Total Time
//		var tt = autoCalcTotal();
        var tt = autoCalcDiff('input[name^=custom_650_].hasTimeEntry',this);
       		cj('input[name^=custom_653_]').attr('value',tt);
      	});

  });
 
 function autoCalcTotal()
 {
	var t1 = cj('input[name^=custom_1658_]').attr('value');
	var t2 = cj('input[name^=custom_1659_]').attr('value');
	if(t1 != null && t2 != null && t1 != '' && t2 != '')
	{
    		t = t1.split(":");
    		c = t2.split(":");
    		if( c.length == 3 && t.length == 3)
		{
			var h1 = parseInt(t[0],10);
			var m1 = parseInt(t[1],10);
			var h2 = parseInt(c[0],10);
			var m2 = parseInt(c[1],10);

			var totalH = h1 + h2;
			var totalM = m1 + m2;
			if( totalM > 59)
			{
				totalH += 1;
				totalM = totalM - 60;
			}

			if( totalM < 10 ) totalM = "0"+totalM;
			if( totalH < 10 ) totalH = "0"+totalH;

			return totalH +":"+ totalM;
		}
	}
 }

  function autoCalcDiff(earlyTime,laterTime){
    var l  = cj(laterTime);
    var e = cj(earlyTime);
    var lt = l.attr('value');
    var et = e.attr('value');
	if(et != null && lt != null && et != '' && lt != '')
	{
        // get date value
        var pd = cj(laterTime).attr("name").split("_");
        var dlt = cj("input[name^="+pd[0]+"_"+pd[1]+"_"+pd[2]+"]").val();
        pd = cj(earlyTime).attr("name").split("_");
        var det = cj("input[name^="+pd[0]+"_"+pd[1]+"_"+pd[2]+"]").val();
        var lDate;
        var eDate;
        lDate = dlt.split("/");
        eDate = det.split("/");
        // end get date value
		var lTime;
		var eTime;
    		x = lt.split(":");
    		if( x.length == 2)
    		{
    	   		lTime = new Date(parseInt(lDate[2]),parseInt(lDate[0]),parseInt(lDate[1]),x[0],x[1]);
		}

    		x = et.split(":");
    		if( x.length == 2)
    		{
    	   		eTime = new Date(parseInt(eDate[2]),parseInt(eDate[0]),parseInt(eDate[1]),x[0],x[1]);
		}
		var total_time = get_time_difference(eTime,lTime);
       		return (total_time.days*24+parseInt(total_time.hours)) +":"+total_time.minutes+":"+total_time.seconds;

	}
  }

  function get_time_difference(earlierDate,laterDate)
  {
	var nTotalDiff = laterDate.getTime() - earlierDate.getTime();
	var oDiff = new Object();
	
	oDiff.days = Math.floor(nTotalDiff/1000/60/60/24);
	
	if( oDiff.days < 10) oDiff.days = "0"+oDiff.days;
	nTotalDiff -= oDiff.days*1000*60*60*24;

	oDiff.hours = Math.floor(nTotalDiff/1000/60/60);
	if( oDiff.hours < 10) oDiff.hours = "0"+oDiff.hours;
	nTotalDiff -= oDiff.hours*1000*60*60;

	oDiff.minutes = Math.floor(nTotalDiff/1000/60);
	if( oDiff.minutes < 10) oDiff.minutes = "0"+oDiff.minutes;
	nTotalDiff -= oDiff.minutes*1000*60;

	oDiff.seconds = Math.floor(nTotalDiff/1000);
	if( oDiff.seconds< 10) oDiff.seconds = "0"+oDiff.seconds;

	return oDiff;
  }

  function alterClearDateTime( element ) {
	  clearDateTime( element );
	  autoCalcAge('input[name^='+element+'].dateplugin');
  }

//]]>
</script>
{/literal}
