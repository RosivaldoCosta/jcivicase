{literal}
<script type="text/php">
if ( isset($pdf) ) {
//  $font = Font_Metrics::get_font("verdana", "bold");
  $font = Font_Metrics::get_font("arial", "bold");
  $fontSmall = Font_Metrics::get_font("arial");
  $size = 12;
  $sizeSmall = 8;
  $color = array(0,0,0);
  $colorBox = array("hex"=>"#E0E0E0");
//  $colorBox = array(76,76,76);
  $text_heightBig = Font_Metrics::get_font_height($font, $size);
  $text_heightSmall = Font_Metrics::get_font_height($fontSmall, $sizeSmall);
  $w = $pdf->get_width();
  $h = $pdf->get_height();
  $marginLeft = 40;
  $marginTop = 16;
  $paddingX = 16;
  $paddingY = 4;
  $hBox = $text_heightBig + ($paddingY * 2);

  $header = $pdf->open_object();
  // start header
   $pdf->rectangle($marginLeft, $marginTop ,$w - ($marginLeft * 2), $hBox, $colorBox, 1.5);
  // $pdf->filled_rectangle($marginLeft, $marginTop ,$w - ($marginLeft * 2), $text_height + ($paddingY * 2) , $colorBox);

  {/literal}
  $textLeftSmall = '{$userinfo_groupTree.Client_Profile.fields.0.0.label}:';
  $textRightSmall = '{$userinfo_groupTree.Client_Profile.fields.3.0.label}:';
  $textLeft = ' {$userinfo_groupTree.Client_Profile.fields.0.0.value}';
  $textRight = ' {$userinfo_groupTree.Client_Profile.fields.3.0.value}';
  {literal}
  $width = Font_Metrics::get_text_width($textRight, $font, $size);
  $widthLeftSmall = Font_Metrics::get_text_width($textLeftSmall, $fontSmall, $sizeSmall);
  $widthRightSmall = Font_Metrics::get_text_width($textRightSmall, $fontSmall, $sizeSmall);

  $pdf->text($marginLeft + $paddingX , $marginTop + ($hBox + $text_heightSmall)/2, $textLeftSmall, $fontSmall, $sizeSmall, $color);
  $pdf->text($marginLeft + $paddingX + $widthLeftSmall, $marginTop + ($hBox - $text_heightBig)/2 + $text_heightBig - 2, $textLeft, $font, $size, $color);

  $pdf->text($w - $width - $marginLeft - $paddingX , $marginTop + ($hBox - $text_heightBig)/2 + $text_heightBig - 2, $textRight, $font, $size, $color);
  $pdf->text($w - $width - $marginLeft - $paddingX -$widthRightSmall, $marginTop + ($hBox + $text_heightSmall)/2  , $textRightSmall, $fontSmall, $sizeSmall, $color);

  // end header
  $pdf->close_object();
  $pdf->add_object($header, "next");
}
</script>
{/literal}
