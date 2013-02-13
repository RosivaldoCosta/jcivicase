{if ($caseDetails.case_status eq 'Closed' && $closedBy && $caseDetails.case_closed_date) || $form.case_status_id.html eq 'Closed' }
<table>
        <tr>
                <td colspan="5">This case was audited by {$closedBy} ({$license}) on {$caseDetails.case_closed_date}</td>
        </tr>
</table>
{/if}
