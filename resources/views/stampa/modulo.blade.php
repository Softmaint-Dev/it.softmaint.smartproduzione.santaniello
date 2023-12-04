<?php $left = array(); $top = array();$width = array();$height = array();$font_sizes = array(); $font_weight = array(); $text_aligns = array();$valore = array(); ?>

<?php
$campi = json_decode($report->json);

foreach ($campi->objects as $textbox) {
    $var = ($textbox->type == 'i-text') ? $textbox->text : $textbox->src;
    $var = \App\Http\Controllers\StampaController::compile_string($var, $query);
    array_push($left, $textbox->left);
    array_push($top, $textbox->top);
    array_push($width, $textbox->width * $textbox->scaleX);
    array_push($height, $textbox->height * $textbox->scaleY);
    ($textbox->type == 'i-text') ? array_push($font_sizes, $textbox->fontSize) : '';
    ($textbox->type == 'i-text') ? array_push($text_aligns, $textbox->textAlign) : '';
    ($textbox->type == 'i-text') ? array_push($font_weight, $textbox->fontWeight) : '';
    if ($textbox->type == 'image') {
        $var = '<img src="' . str_replace('https://46.231.34.161/', '', $var) . '" alt="Logo Cliente">';
    }
    array_push($valore, $var);
}
?>
<?php $len = sizeof($left); ?>

<?php for ($j = 0;
           $j < $len;
           $j++){ ?>
<div
        style="position:absolute;font-weight:<?php echo $font_weight[$j]; ?>;top:<?php echo $top[$j]; ?>px;left:<?php echo $left[$j] ?>px;width:<?php echo $width[$j] ?>px;height:<?php echo $height[$j] ?>px;<?php echo (isset($font_sizes[$j]))?'font-size:'.$font_sizes[$j].'px;':''; ?><?php echo (isset($text_aligns[$j]))?'text-align:'.$text_aligns[$j].';':''; ?>;font-weight: bold;"><?php echo $valore[$j] ?></div>
<?php } ?>
