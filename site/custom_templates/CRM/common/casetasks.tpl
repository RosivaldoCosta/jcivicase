<!--<a href="index2.php?option=com_civicrm&task=civicrm/contact/view/activity&action=add&reset=1&cid={$contactID}&selectedChild=activity&atype=2">Phone Call</a><br/>-->
{if $site eq 'aacrs'}<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=124">30 Day Follow-up</a><br/>{/if}
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=68"> Assess Lethality</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=80">Attach Document</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=56"> Confirm Appointment</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=67"> Call Referrals</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=76"> Client's Providers</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=104"> Call to Schedule UCC Dr. Appt</a><br/>
{if $site eq 'aacrs'}
	<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=126"> Call to Schedule UCC Therapist Appt</a><br/>
{else}
	<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=111"> Call to Schedule UCC Therapist Appt</a><br/>
{/if}

<!--<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=104"> Call To Schedule UCC Appointment</a><br/>-->
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=108">Close If No Call Back</a><br/>
{if $site eq 'aacrs'} <a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=120">Coordination of Care Release</a><br/> {/if}
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=44">Dispatch MCT</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=75"> Give Referrals to Alt</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=34"> How Are You</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=42">Hospital Diversion Follow-Up</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=72"> If No Call Back </a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=105"> {$ihit} Referral </a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=45">Other Task</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=73"> Offer Services  </a><br/>
{if $site eq 'aacrs'}<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=123">Provider Follow-up</a><br/>{/if}
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=74"> Relative </a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=47">Recommend for Closure</a> <br/>
{if $site eq 'aacrs'}
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=121">Schedule Care Coordination Appointment</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=122">Schedule Substance Abuse Tx Appointment</a><br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=125">Schedule Primary Care Appointment</a><br/>
{/if}
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=77">Send Out PAC Evaluation</a> <br/>
<a href="index2.php?option=com_civicrm&task=civicrm/case/activity&action=add&reset=1&cid={$contactID}&caseid={$caseID}&atype=78">Supervisor To Review</a><br/>
