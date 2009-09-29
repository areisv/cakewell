<?php
/* SVN FILE: $Id: home.ctp 7690 2008-10-02 04:56:53Z nate $ */
#$html->css('klenwell.basic', null, array(), false);
#$html->css('cakewell.demo', null, array(), false);

?>

<div id="core_panel">
<h1><?php echo Configure::Read('App.domain'); ?></h1>

<div id="blurb">
<?php echo Configure::Read('App.motto'); ?>
</div>

<div class="navbar">
    <a href="http://code.google.com/p/cakewell/">code</a>
    <a href="/demo">demo</a>
    <a href="http://klenwell.com/press">blog</a>
</div>

<table class="content">
    <tr>
        <td class="left_cell">
            <div class="project_block"></div>
        </td>
        <td class="right_cell">
            <div class="rss_block"></div>
        </td>
    </tr>
</table>
</div>

</div>
