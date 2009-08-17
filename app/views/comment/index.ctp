<?php

// stylesheet (will add to $scripts_for_layout)
$html->css('klenwell.basic', null, array(), false);
$html->css('cakewell.comment.index', null, array(), false);

?>

<div class="cakewell-sandbox" id="cakewell-sandbox-index">

<h2>Cakewell Comment Form Element</h2>

<div class="examples">
<h3>examples</h3>
<ul>
    <li><a href="/comment/demo">simple form example</a></li>
    <li><a href="/comment/multiform">two forms on one page</a></li>
</ul>
</div>

<h3>overview</h3>
<div class="overview">
    <p>The Cakewell Comment controller demonstrates a wizard-like
    ajax-driven comment form that frustrates spammers and bots by integrating
    the <a href="http://recaptcha.net/" onclick="window.open(this.href,'_blank');return false;">
    reCAPTCHA</a> service.</p>

    <p>Comment forms are made available to any page within the site through a simple
    <a href="http://code.google.com/p/cakewell/source/browse/app/views/elements/comment_form.ctp"
    onclick="window.open(this.href,'_blank');return false;">element</a> that
    neatly interfaces with the <a href="http://code.google.com/p/cakewell/source/browse/app/controllers/comment_controller.php"
    onclick="window.open(this.href,'_blank');return false;">comment controller</a>.</p>

    <p>The source code</a> for the demo forms below, along with all the other code used
    in this site, can be found on the <a href="http://code.google.com/p/cakewell/"
    onclick="window.open(this.href,'_blank');return false;">Cakewell Google Code</a> site.</p>
</div>


</div>
