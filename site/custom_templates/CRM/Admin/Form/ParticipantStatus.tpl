<div class="form-item">
  <fieldset>
    <legend>
      {if $action eq 1}{ts}New Participant Status{/ts}{elseif $action eq 2}{ts}Edit Participant Status{/ts}{else}{ts}Delete Participant Status{/ts}{/if}
    </legend>

    {if $action eq 8}
      <div class="messages status">
        <dl>
          <dt><img src="{$config->resourceBase}Inform.gif" alt="{ts}status{/ts}" /></dt>
          <dd>{ts}WARNING: Deleting this Participant Status will remove all of its settings.{/ts} {ts}Do you want to continue?{/ts}</dd>
        </dl>
      </div>
      <dl>
        <dt></dt><dd>{$form.buttons.html}</dd>
      </dl>
    {else}
      <table class="form-layout-compressed">
        <tr><td class="label">{$form.name.label}</td><td>{$form.name.html}<br />
            <span class="description">{ts}Name of this status type, for use in the code.{/ts}</span></td>
        </tr>

        <tr><td class="label">{$form.label.label}</td><td>{$form.label.html}<br />
            <span class="description">{ts}Display label for this status.{/ts}</span></td>
        </tr>

        <tr><td class="label">{$form.class.label}</td><td>{$form.class.html}<br />
            <span class="description">{ts}The general class of this status. Participant counts are grouped by class on the CiviEvent Dashboard. Participants with a 'Pending' class status will be moved to 'Expired' status if Pending Participant Hours has elapsed (when the ParticipantProcessor.php background processing script is run).{/ts}</span></td>
        </tr>

        <tr><td class="label">{$form.is_reserved.label}</td><td>{$form.is_reserved.html}</td></tr>
        <tr><td class="label">{$form.is_active.label}  </td><td>{$form.is_active.html}  </td></tr>
        <tr><td class="label">{$form.is_counted.label} </td><td>{$form.is_counted.html}<br />
            <span class="description">{ts}Should a person with this status be counted as a participant for the purpose of controlling the Maximum Number of Participants?{/ts}</td>
        </tr>

        <tr><td class="label">{$form.weight.label}</td><td>{$form.weight.html}</td></tr>

        <tr><td class="label">{$form.visibility_id.label}</td><td>{$form.visibility_id.html}<br />
            <span class="description">{ts}If you allow users to select a Participant Status by including that field on a profile - only statuses with 'Public' visibility are listed.{/ts}</td>
        </tr>

        <tr><td class="label">&nbsp;</td><td>{$form.buttons.html}</td></tr>
      </table>
    {/if}
    <div class="spacer"></div>
  </fieldset>
</div>
