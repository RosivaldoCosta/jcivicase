{* include jscript to handle create new Lethality Rating Sheet *}
<div style="display: none;" id="lethality-dialog"></div>
<script type="text/javascript">
//<![CDATA[
{literal}
function newLethality() {
	allowAutoSave = false;
	// create case
    cj("#lethality-dialog").show( ).html( "Please wait..." ).dialog({
	    title: "Create Lethality Rating Sheet",
   		modal: true,
   		width: 800,
   		overlay: {
   			opacity: 0.5,
   			background: "black"
   		},

       beforeclose: function(event, ui) {
           cj(this).dialog("destroy");
       }
   });
   cj('input.form-submit[value=Save]').after('<input type="hidden" value="1" name="newlethality">').click();
}

//]]>
</script>
{/literal}
