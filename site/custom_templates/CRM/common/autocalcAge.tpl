{* include jscript to handle auto calculating points *}
<script type="text/javascript">
//<![CDATA[
   var caseTypeId = '{$caseTypeId}';
   var isErrorValidation = cj('ul#errorList').length > 0;
{literal}
  jQuery(document).ready(function($)
  {
	  cj('input[name^=custom_70].dateplugin').live('change',function () {
		  autoCalcAge(this);
      });
	  cj('input[name^=custom_70].dateplugin').parent().find('a').attr('onclick','alterClearDateTime(cj("input[name^=custom_70].dateplugin").attr("name"));return false;');
	  if(typeof(cj('input#case_type_id').attr('value'))!='undefined')
      {
          var typeCase = parseInt(cj('input#case_type_id').attr('value'));
    	  showHideCustom(typeCase);
    	  setCaseType(typeCase);
      }
      else if(typeof(cj('#case_type_id option:selected').attr('value'))!='undefined')
      {
    	  var typeCase = parseInt(cj('#case_type_id option:selected').attr('value'));
    	  showHideCustom(typeCase);
    	  setCaseType(typeCase);
    	  cj('#case_type_id').change(function () {
    		  var typeCaseChange = parseInt(cj('#case_type_id option:selected').attr('value'));
    		  showHideCustom(typeCaseChange);
    		  setCaseType(typeCaseChange);
	      });
      }
      else if(caseTypeId != '')
      {
    	  var typeCase = parseInt(caseTypeId);
    	  showHideCustom(typeCase);
    	  setCaseType(typeCase);
      }

  });

  function autoCalcAge(s){
    var dob = cj(s);
    var now = new Date();
    var bDay = dob.attr('value');
    bD = bDay.split('/');
    if (bD.length == 3)
    {
    	var born;
    	if (dob.attr('format')=='mm/dd/yy')
    	{
    	   born = new Date(bD[2], bD[0] * 1 - 1, bD[1]);
    	}
    	else if (dob.attr('format')=='dd/mm/yy')
    	{
    		born = new Date(bD[2], bD[1] * 1 - 1, bD[0]);
    	}
    	if(typeof(born)== 'object')
    	{
    		years = Math.floor((now.getTime() - born.getTime()) / (365.25 * 24 * 60 * 60 * 1000));
    		if(years > 0)
    		{
       			cj('input[name^=custom_71]').attr('value',years);
       			cj('[name^=custom_1495_][value=0]').click();
    		}
    		else if (years == 0)
    		{
    			mBorn = born.getMonth();
    			mNow = now.getMonth();
    			if (mBorn > mNow)
    				mNow += 12;
    			month = mNow - mBorn;
    			cj('input[name^=custom_71]').attr('value');
       			cj('[name^=custom_1495_][value=0]').click();
    		}
    		else
    		{
    			cj('input[name^=custom_71]').attr('value','');
       			cj('[name^=custom_1495_][value=1]').click();
    		}

    	}
    }
    else
    {
		cj('input[name^=custom_71]').attr('value','');
		cj('[name^=custom_1495_][value=1]').click();
    }
  }

  function showHideCustom(typeCase)
  {
      if( typeCase == 1 )
      {
          cj('#Additional_Info_1').show();
          cj('#Crisis_Plan_911_Follow_Up_1').show();
          cj('#Linkage_and_Referrals_1').show();
      }
      else if( typeCase == 2)
      {
          cj('#Additional_Info_1').hide();
          cj('#Crisis_Plan_911_Follow_Up_1').hide();
          cj('#Linkage_and_Referrals_1').hide();
      }
      else
      {
          cj('#Additional_Info_1').show();
          cj('#Crisis_Plan_911_Follow_Up_1').show();
          cj('#Linkage_and_Referrals_1').show();
      }
  }

  function setCaseType(typeCase)
  {
	   var fields = [['custom_62_', // Veteran
//                'contact', // PFirstName
                'custom_67_', // Client Information: Phone
                'custom_1448_', // Client Information: Address
                'custom_51_', // Caller Name
                'custom_57_', // Relationship
                'activity_details', // Reason For Contact create Case
                'details', // Reason For Contact edit Case
//                'custom_117_', // Insurance Company
                'custom_121_', // Insurance Type
                'custom_155_' // County
	    		],
 				[
//  				'contact', // Client First Name, Client Last Name
                'custom_1448_', // Client Address
                'custom_67_', // Client Phone
                'custom_51_' // Caller Name
 				]
	    ];
	  if(typeof(cj('[name^=custom_1495_]:checked'))!='undefined' && cj('[name^=custom_1495_]:checked').attr('value')!='1')
	  {
		  fields[0][fields[0].length] = 'custom_70_'; // DOB
	  }
      if( typeCase == 1 )
      {
    	  cj('#activity_subject').attr('value','Open OPS Case');
    	  cj('#subject').attr('value','Open OPS Case');
          rmMark();
          cj.each(fields[typeCase-1],function(index, value) {
        	  addMark(value);
          });

          if(cj('[name^=custom_4_]:checked').attr('value')=='Yes' || cj('[name^=custom_116_]:checked').attr('value')=='Yes')
          {
        	  addMark('custom_79_');
          }
      }
      else
          if(typeCase == 2)
          {
        	  cj('#activity_subject').attr('value','Open MCT Case');
        	  cj('#subject').attr('value','Open MCT Case');
              rmMark();
              cj.each(fields[typeCase-1],function(index, value) {
            	  addMark(value);
              });
          }
          else
      {
      }

  function rmMark()
  {
           cj.each(fields,function(index, value) {
              cj.each(fields[index],function(index, value) {
                  element = cj('[name^='+value+']').parents('td.view-value , td.html-adjust').parent().find('td.label').find('label').find('span.marker');
                  if(element.length >0)
                  {
                    element.remove();
                  }
              });
           });
//	  cj('span.marker').remove();
  }
  }


  function addMark(name)
  {
	  if(name != '')
	  {
		  element = cj('[name^='+name+']').parents('td.view-value , td.html-adjust').parent().find('td.label').find('label');
		  if(element.length >0 && isErrorValidation && trim(cj('[id^='+name+']').attr('value')) == '')
		  {
			  element.addClass('error upper').css('color','red');
		  }
		  cj('<span title="This field is required." class="marker">*</span>').appendTo( element );
	  }
  }

  function alterClearDateTime( element ) {
	  clearDateTime( element );
	  autoCalcAge('input[name^='+element+'].dateplugin');
  }

//]]>
</script>
{/literal}
