{literal}
function resizeFlexiGrid()
{
    var dataUrl = {/literal}"{crmURL p='civicrm/ajax/activity' h=0 q='snippet=4&caseID='}{$caseID}"{literal};

        dataUrl = dataUrl + '&cid={/literal}{$contactID}{literal}';

        var gridw = 930;
        var diff = 0;

        if(cj(window).width() >= gridw)
        {
                diff = cj(window).width() - 100 - gridw;
                diff = parseInt(diff/8);
                gridw = gridw + (diff * 8);
        }

        cj("#activities-selector").flexigrid
        (
            {
                url: dataUrl,
                dataType: 'json',
                colModel : [

                {display: 'Assigned Date',    name : 'display_date', width : 100+diff,  sortable : true, align: 'left'},
                /*{display: 'Type',    name : 'type',        width : 100+diff,  sortable : true, align: 'left'},*/
                {display: 'Title', name : 'subject',     width : 105+diff, sortable : true, align: 'left'},
                {display: 'Date Due',    name : 'display_date', width : 100+diff,  sortable : true, align: 'left'},
                {display: 'Reporter / Assignee',name : 'reporter',    width : 100+diff,  sortable : true, align: 'left'},
                {display: 'Completed?',  name : 'status',      width : 65+diff,  sortable : true, align: 'left'},
                {display: 'Completed By',  name : 'completed_contact',      width : 65+diff,  sortable : true, align: 'left'},
                {display: 'Completed Date',    name : 'display_date', width : 100+diff,  sortable : true, align: 'left'},
                {display: '',        name : 'links',       width : 110+diff,  align: 'left'},
                {name : 'class', hide: true, width: 1}  // this col is use for applying css classes
                ],
                usepager: true,
                useRp: true,
                rpOptions: [10,20,40,80,100],
                rp: 20,
                showToggleBtn: false,
                width: gridw,
                height: 'auto',
                nowrap: false,
                onSuccess:setSelectorClass
            }
        );
}

{/literal}
