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
<div class="cakewell-journal" id="cakewell-journal-changelog">

    <h2>Changelog</h2>
    <div class="timestamps">
        <?php print $created; ?>
        <?php print $updated; ?>
    </div>

    <div class="log">
        <h4>version v1s8-201004</h4>
        <h5>released: TBA</h4>
        <ul>
            <li>Added journal controller with changelog</li>
        </ul>
    </div>
</div>
