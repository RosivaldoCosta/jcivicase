{strip}
{if $elementIndex}
    {assign var='elementId'   value=$form.$elementName.$elementIndex.id}
    {assign var="timeElement" value=$elementName|cat:"_time.$elementIndex"}
    {$form.$elementName.$elementIndex.html|crmReplace:class:dateplugin}
{else}
    {assign var='elementId'   value=$form.$elementName.id}
    {assign var="timeElement" value=$elementName|cat:'_time'}
    {$form.$elementName.html|crmReplace:class:dateplugin}
{/if}
{assign var='dateFormated' value=$elementId|cat:"_hidden"}<input type="text" name="{$dateFormated}" id="{$dateFormated}" class="hiddenElement"/>
{if $timeElement}
    &nbsp;&nbsp;{$form.$timeElement.label}&nbsp;&nbsp;{$form.$timeElement.html|crmReplace:class:six}
{/if}
{if  $action neq 1028}
    {if !isset($isPrint) || !$isPrint}
        (<a href="javascript:clearDateTime( '{$elementId}' );">{ts}clear{/ts}</a>)&nbsp;
    {/if}
{/if}
<script type="text/javascript">
    {literal}
    cj( function() {
      {/literal}	
      var element_date   = "#{$elementId}"; 
      {if $timeElement}
          var element_time  = "#{$timeElement}";
          var time_format   = cj( element_time ).attr('timeFormat');
          {literal}
              cj(element_time).timeEntry({ show24Hours : time_format });
          {/literal}
      {/if}
      var currentYear = new Date().getFullYear();
      var date_format = cj( element_date ).attr('format');
      var alt_field   = 'input#{$dateFormated}';
      var yearRange   = currentYear - parseInt( cj( element_date ).attr('startOffset') ); 
          yearRange  += ':';
          yearRange  += currentYear + parseInt( cj( element_date ).attr('endOffset'  ) ); 
      {literal}
 
      cj(element_date).datepicker({
                                    closeAtTop        : true, 
                                    dateFormat        : date_format,
                                    changeMonth       : true,
                                    changeYear        : true,
                                    altField          : alt_field,
                                    altFormat         : 'mm/dd/yy',
                                    yearRange         : yearRange
                                });
    
      cj(element_date).click( function( ) {
          hideYear( this );
      });  
      cj('.ui-datepicker-trigger').click( function( ) {
          hideYear( cj(this).prev() );
      });  
    });
    
    function hideYear( element ) {
        var format = cj( element ).attr('format');
        if ( format == 'dd-mm' || format == 'mm/dd' ) {
            cj(".ui-datepicker-year").css( 'display', 'none' );
        }
    }
    
    function clearDateTime( element ) {
        cj('input#' + element + ',input#' + element + '_time').val('');
    }
    {/literal}
</script>
{/strip}
