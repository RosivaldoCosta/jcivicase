<table>
<tr>
              <td colspan="2">
                <div id="follow-up_show" class="section-hidden section-hidden-border">
                 <a href="#" onclick="hide('follow-up_show'); show('follow-up'); return false;"><img border="0" src="{$config->resourceBase}TreePlus.gif" class="action-icon" alt="open section"/></a><label>{ts}Schedule Follow-up{/ts}</label><br />
                </div>

                <div id="follow-up" class="section-shown">
                <fieldset><legend><a href="#" onclick="hide('follow-up'); show('follow-up_show'); return false;"><img border="0" src="{$config->resourceBase}TreeMinus.gif" class="action-icon" alt="close section"/></a>{ts}Schedule Follow-up{/ts}</legend>
                    <table class="form-layout-compressed">
                        <tr><td class="label">{ts}Schedule Follow-up Activity{/ts}</td>
                            <td>{$form.followup_activity_type_id.html}&nbsp;{$form.interval.label}&nbsp;{$form.interval.html}&nbsp;{$form.interval_unit.html}</td>
                        </tr>
                        <tr>
                           <td class="label">{$form.followup_activity_subject.label}</td>
                           <td>{$form.followup_activity_subject.html}</td>
                        </tr>
                    </table>
                </fieldset>
                </div>
              </td>
           </tr>
</table>
