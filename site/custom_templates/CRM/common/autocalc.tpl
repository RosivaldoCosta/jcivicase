{* include jscript to handle auto calculating points *}
<script type="text/javascript">
//<![CDATA[
{literal}
	cj(document).ready(function($)
  {

	  autoCalcOne();
	  cj('#Section_One_1').live('click',function () {
		  autoCalcOne();
		  autoTreatment();
		  autoUpdateSubject();
        });

	  autoCalcTwo();
	  cj('#Section_Two_1').live('click',function () {
		  autoCalcTwo();
		  autoTreatment();
		  autoUpdateSubject();
        });
	  autoTreatment();
  });

  function autoUpdateSubject(){
  	var currentSubject = cj('#lethality-dialog textarea[name^=subject]').attr('value').split("|");
	var combined = parseInt(cj('input[name^=custom_770_]').attr('value'));

	cj('input[name^=subject]').attr('value', currentSubject[0] + "| " + combined);
  }

  function autoCalc(s){
	  var sum=0;
	  cj('#'+s+' input:checked').each(function () {
		  sum+= parseInt( cj(this).attr('value'));
        });
	  return sum;
  }
  function autoCalcOne()
  {

	  cj('input[name^=custom_716_]').attr('value',autoCalc('Section_One_1'));
	  cj('input[name^=custom_768_]').attr('value', parseInt(cj('input[name^=custom_716_]').attr('value'))+parseInt(cj('input[name^=custom_767_]').attr('value')));
	  cj('input[name^=custom_769_]').attr('value', parseInt(cj('input[name^=custom_716_]').attr('value')));
	  cj('input[name^=custom_770_]').attr('value', parseInt(cj('input[name^=custom_768_]').attr('value')));
  }
  function autoCalcTwo()
  {

	  cj('input[name^=custom_767_]').attr('value',autoCalc('Section_Two_1'));
	  cj('input[name^=custom_768_]').attr('value', parseInt(cj('input[name^=custom_716_]').attr('value'))+parseInt(cj('input[name^=custom_767_]').attr('value')));
	  cj('input[name^=custom_770_]').attr('value', parseInt(cj('input[name^=custom_768_]').attr('value')));
  }
  function autoTreatment()
  {

	  var combined=parseInt(cj('input[name^=custom_770_]').attr('value'));
	  var one=parseInt(cj('input[name^=custom_769_]').attr('value'));
	if (one <= 6 && combined <= 10)
	{
		cj('select[name^=custom_719_] option[value=mild]').attr('selected','selected');
	}else if (one <= 8 && combined <= 18)
	{
		cj('select[name^=custom_719_] option[value=moderate]').attr('selected','selected');
	}else if (one <= 18 && combined <= 39)
	{
		cj('select[name^=custom_719_] option[value=high]').attr('selected','selected');
	}else
	{
		cj('select[name^=custom_719_] option[value=immediate]').attr('selected','selected');
	}
  }
//]]>
</script>
{/literal}
