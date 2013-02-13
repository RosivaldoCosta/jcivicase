{literal}
<script language="javascript">
function resizeAppointmentGrid()
{
    var dataUrl = {/literal}"{crmURL p='civicrm/ajax/activity/appointment' h=0 q='snippet=4&caseID='}{$caseID}"{literal};

        dataUrl = dataUrl + '&cid={/literal}{$contactID}{literal}';

        var gridw = 800;
        var diff = 0;
	var columns = 5;

        if(cj(window).width() >= gridw)
        {
                diff = cj(window).width() - 100 - gridw;
                diff = parseInt(diff/columns);
                gridw = gridw + (diff * columns);
        }

        cj("#appointment-selector").flexigrid
        (
            {
                url: dataUrl,
                dataType: 'json',
                colModel : [
                {display: 'Title', name : 'subject',     width : 50+diff, sortable : true, align: 'left'},
                {display: 'Type', name : 'activity_type',     width : 50+diff, sortable : true, align: 'left'},
                {display: 'Date/Time',    name : 'display_date', width : 110+diff,  sortable : true, align: 'left'},
                {display: 'Status',    name : 'status', width : 110+diff,  sortable : true, align: 'left'},
                {display: 'Completed Date',    name : 'completed_date', width : 110+diff,  sortable : true, align: 'left'},
                ],
                usepager: true,
                useRp: true,
                rpOptions: [10,20,40,80,100],
                rp: 80,
                showToggleBtn: false,
                width: '100%',
                height: 'auto',
                nowrap: false,
                onSuccess:setSelectorClass
            }
        );

}

function filterAppointmentGrid(com)
{
    var activity_deleted = 0;
    //if ( cj("#activity_deleted:checked").val() == 1 ) {
     //   activity_deleted = 1;
    //} 
    cj('#appointment-selector').flexOptions({
            newp:1,
                params:[ {name:'activity_deleted', value: activity_deleted } ]
                });
        
    cj("#appointment-selector").flexReload();
}  
</script>
{/literal}
