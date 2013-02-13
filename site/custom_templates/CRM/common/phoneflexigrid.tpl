{literal}
<script language="javascript">
function resizePhoneGrid()
{
    var dataUrl = {/literal}"{crmURL p='civicrm/ajax/activity/phone' h=0 q='snippet=4&caseID='}{$caseID}"{literal};

        dataUrl = dataUrl + '&cid={/literal}{$contactID}{literal}';

        var gridw = 930;
        var diff = 0;

        if(cj(window).width() >= gridw)
        {
                diff = cj(window).width() - 100 - gridw;
                diff = parseInt(diff/8);
                gridw = gridw + (diff * 8);
        }

        cj("#phone-selector").flexigrid
        (
            {
                url: dataUrl,
                dataType: 'json',
                colModel : [
                {display: 'Notes', name : 'subject',     width : 250+diff, sortable : true, align: 'left'},
                {display: 'Contact', name : 'person_contacted',     width : 50+diff, sortable : true, align: 'left'},
                {display: 'Org', name : 'org',     width : 50+diff, sortable : true, align: 'left'},
                {display: 'Phone', name : 'phone',     width : 75+diff, sortable : true, align: 'left'},
                {display: 'Date/Time',    name : 'display_date', width : 110+diff,  sortable : true, align: 'left'},
                {display: 'Reporter',name : 'reporter',    width : 100+diff,  sortable : true, align: 'left'},
                {display: 'Type',name : 'call_type',    width : 75+diff,  sortable : true, align: 'left'},
                {display: 'Actions',        name : 'links',       width : 110+diff,  align: 'left'},
                {name : 'class', hide: true, width: 1}  // this col is use for applying css classes
                ],
                usepager: true,
                useRp: true,
                rpOptions: [10,20,40,80,100],
                rp: 80,
                showToggleBtn: false,
                width: gridw,
                height: 'auto',
                nowrap: false,
                onSuccess:setSelectorClass
            }
        );
	filterPhoneGrid();

}

function filterPhoneGrid(com)
{
    var activity_deleted = 0;
    //if ( cj("#activity_deleted:checked").val() == 1 ) {
     //   activity_deleted = 1;
    //} 
    cj('#phone-selector').flexOptions({
            newp:1,
                params:[ {name:'activity_type_id', value: '48'},
                        {name:'activity_deleted', value: activity_deleted }
                        ]
                });
        
    cj("#phone-selector").flexReload();
}  
</script>
{/literal}
