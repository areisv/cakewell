<?php

# stylesheet (will add to $scripts_for_layout)
#$html->css('cakewell.demo', null, array(), false);

# this will update the head section with raw markup
#$this->addScript($html);

# timestamps
$created = ( is_null($created) ) ? '' :
    sprintf('<h5>created: %s</h5>', date($timestamp_f, strtotime($created)));
$updated_at = '2010-04-12 15:41:00';


function _released($hg_stamp) {
    $release_f = 'j M Y';
    print date($release_f, strtotime($hg_stamp));
}

?>
<div class="cakewell-journal" id="cakewell-journal-changelog">

    <h2>Changelog</h2>
    <div class="timestamps">
        <?php print $created; ?>
        <?php printf('<h5>last updated: %s</h5>',
                     date($timestamp_f, strtotime($updated_at))); ?>
    </div>

    <div class="log">
        <h4>version v1s9-201004</h4>
        <h5>released: <?php _released('2010-04-12'); ?></h4>
        <ul>
            <li>Added functional cron task to backend controller</li>
            <li>Added <a href="/demo/ajax">AJAX examples</a></li>
            <li>Added project page to
                <a href="http://klenwell.com/is/ProjectCakewell">klenwell wiki</a>
            </li>
        </ul>
    </div>

    <div class="log">
        <h4>version v1s8-201001</h4>
        <h5>released: <?php _released('Thu Jan 28 22:01:23 2010'); ?></h4>
        <ul>
            <li>Added journal controller with changelog</li>
            <li>Added <a href="/demo/sitemap">sitemap example</a></li>
            <li>Updated
                <a href="http://code.google.com/p/cakewell/source/browse/dev/bin/nfs_update.sh">
                auto-update script</a></li>
        </ul>
    </div>
</div>
