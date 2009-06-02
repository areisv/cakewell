<?php

if ( !isset($header) ) $header = 'Cakewell Sandbox';
$summary = ( isset($summary) ) ? '<h3>'.$summary.'</h3>' : '';
if ( !isset($output) ) $output = 'no output';
if ( is_array($output) ) $output = htmlspecialchars(print_r($output, 1));

// this will update the head section with raw markup
#$this->addScript();

// stylesheet (will add to $scripts_for_layout)
$html->css('klenwell.basic', null, array(), false);
$html->css('cakewell.demo', null, array(), false);

?>
<div class="cakewell-sandbox" id="cakewell-sandbox-commentform">

<div id="<?php echo $dom_id; ?>">
    <!-- Will be dynamically replaced by jquery -->
</div>

<?php
    echo $this->element( 'comment_form',
                         array( 'form_id'  => $form_id,
                                'dom_id'   => $dom_id ) );
?>

</div>