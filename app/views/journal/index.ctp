<?php

# stylesheet (will add to $scripts_for_layout)
#$html->css('cakewell.demo', null, array(), false);

# this will update the head section with raw markup
#$this->addScript($html);

# timestamps
$created = ( is_null($created) ) ? '' :
    sprintf('<h5>created: %s</h5>', date($timestamp_f, strtotime($created)));
$updated = ( is_null($updated) ) ? '' :
    sprintf('<h5>last updated: %s</h5>', date($timestamp_f, strtotime($updated)));

?>
<div class="cakewell-journal" id="cakewell-journal-test">

    <h2>Journal Index</h2>
    <div class="timestamps">
        <?php print $created; ?>
        <?php print $updated; ?>
    </div>

    <br />
    <p>This journal demonstrates a very basic form of a journal or weblog. It
       does not involve a database but can only be edited by editing the
       code itself.  For a true full-feature blog or content management
       system, try <a href="http://wordpress.org/">WordPress</a> or
       <a href="http://drupal.org/">Drupal</a>.</p>

    <br />
    <div><strong>page count:</strong> <?php echo count($MethodList); ?></div>

</div>
