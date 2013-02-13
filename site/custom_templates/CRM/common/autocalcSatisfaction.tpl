{* include jscript to handle auto calculating points *}
<script type="text/javascript">
//<![CDATA[
{literal}
  jQuery(document).ready(function($)
  {


	  autoCalcSatisfaction();
	  cj('#Overall_Satisfaction_Average_1').live('click',function () {
		  autoCalcSatisfaction();
        });
  });
  function autoCalc(s){
	  var sum=0;
	  cj('#'+s+' input:checked').each(function () {
		  sum+= parseInt( cj(this).attr('value'));
        });
	  return sum/4;
  }
  function autoCalcSatisfaction()
  {
	  cj('input[name^=custom_379_]').attr('value',autoCalc('Overall_Satisfaction_Average_1'));
  }
//]]>
</script>
{/literal}
