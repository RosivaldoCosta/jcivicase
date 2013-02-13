{if isset($contactId) and $contactId} {* Display contact-related footer. *}
    {if isset($lastModified) and $lastModified}
        {ts}Profile Last Updated by:{/ts} <a href="{crmURL p='civicrm/contact/view' q="action=view&reset=1&cid=`$lastModified.id`"}">{$lastModified.name}</a> ({$lastModified.date|crmDate}) &nbsp;
        {if $changeLog != '0'}
            <a href="{crmURL p='civicrm/contact/view' q="reset=1&action=browse&selectedChild=log&cid=`$contactId`"}">&raquo; {ts}View Change Log{/ts}</a>
        {/if}
    {/if}
{/if}

