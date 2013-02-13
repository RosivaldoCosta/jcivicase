{literal}
<script type="text/javascript">
function addActivity( activityTypeID, contactID ) {
    cj("#add-activity").show( );

    cj("#add-activity").dialog({
        title: "Add Activity",
        modal: true,
        width : "680px", // don't remove px
        height: "560",
        resizable: true,
        bgiframe: true,
        overlay: {
            opacity: 0.5,
            background: "black"
        },

        beforeclose: function(event, ui) {
            cj(this).dialog("destroy");
        },

        open:function() {
            cj("#add-activity-content").html("");
            var addUrl = "/staging/administrator/index.php?option=com_emr&view=addactivity&task=add&format=raw";
            cj("#activity-content").load( viewUrl + "&cid="+contactID + "&activityTypeId=" + activityID);

        },

        buttons: {
            "Done": function() {
                cj(this).dialog("close");
                cj(this).dialog("destroy");
            }
        }
    });
}
</script>
{/literal}
