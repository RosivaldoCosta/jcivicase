{* template for adding form elements for selecting existing or creating new contact*}
{if $context ne 'search'}
    <tr id="contact-success" style="display:none;">
	<td></td>
	<td><span class="success-status">{ts}New client has been created.{/ts}</span></td>
    </tr>
    <tr>
	<td class="label">Client Profile<!--{$form.contact.label}--></td>
	<td>
	    {if $form.profiles}
		<!--&nbsp;&nbsp;{ts}OR{/ts}&nbsp;&nbsp;-->{$form.profiles.html}<div id="contact-dialog" style="display:none;"></div>
	    {/if}
	    <div id="contact-read" {*style="display:none;"*} class="view-value font-size12pt bold">{$form.contact.html}</div>
	</td>
    </tr>
{/if}
{literal}
<script type="text/javascript">
  cj( function( ) {
      var contactUrl = {/literal}"{crmURL p='civicrm/ajax/contactlist' q='context=newcontact' h=0 }"{literal};

      cj('#contact').autocomplete( contactUrl, {
          selectFirst : false, matchContains: true, minChars: 2
      }).result( function(event, data, formatted) {
          cj("input[type=hidden][name=contact_select_id]").attr('value',data[1]);
          var dataUrl = {/literal}"{crmURL p='civicrm/ajax/contactlist' q='context=contactinfo' h=0 }{literal}" +'&cid=' + data[1];
        	  cj.ajax({
        	            url     : dataUrl,
        	            dataType: "json",
        	            timeout : 5000, //Time in milliseconds
        	            success : function( dataInfo ){
            	            for(var i in dataInfo)
            	            {
            	            	if (i == 'custom_72_-1')
            	            	{
            	            		cj('input[value='+dataInfo[i]+'][name=custom_72_-1]' ).attr('checked','checked');
            	            	}
            	            	else if (i == 'custom_65_-1')
            	            	{
            	            		cj('select[name=custom_65_-1] > option[value='+dataInfo[i]+']' ).attr('selected','selected');
            	            	}
            	            	else
        	                    	cj( '#' + i ).val( dataInfo[i] );
            	            }
        	                      },
        	            error   : function( XMLHttpRequest, textStatus, errorThrown ) {
        	                              console.error( 'Error: '+ textStatus );
        	                    }
        	         });
      }).focus( );

      cj("#contact").click( function( ) {
          cj("input[name=contact_select_id]").val('');
      });

      cj("#contact").bind("keypress keyup", function(e) {
          if ( e.keyCode == 13 ) {
              return false;
          }
      });
  });

  function newContact( gid ) {
      var dataURL = {/literal}"{crmURL p='civicrm/profile/create' q='reset=1&snippet=5&context=dialog' h=0 }"{literal};
      dataURL = dataURL + '&gid=' + gid;
      cj.ajax({
         url: dataURL,
         success: function( content ) {
             cj("#contact-dialog").show( ).html( content ).dialog({
         	    	title: "Create New Contact",
             		modal: true,
             		width: 680,
             		overlay: {
             			opacity: 0.5,
             			background: "black"
             		},

                 beforeclose: function(event, ui) {
                     cj(this).dialog("destroy");
                 }
             });
         }
      });
  }

</script>
{/literal}
