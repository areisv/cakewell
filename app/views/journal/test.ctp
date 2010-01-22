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

    <h2>Journal</h2>
    <div class="timestamps">
        <?php print $created; ?>
        <?php print $updated; ?>
    </div>

    <h3>A demonstration of the Cakewell journal controller</h3>

    <p>The view for this url is entirely static and has no style sheets
       associated with it.</p>

    <p>It does require that the routes config file be updated to include:<br /><br />
       <tt>Router::connect('/journal/*', array('controller' => 'journal',
           'action' => 'index'));</tt></p>

</div>
