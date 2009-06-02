<?php
/*
    Footer Element

    Usage:
        $html = $this->element('footer');
*/

?>

<div id="foot">
    <div class="left col">
        <?php echo $this->element('cclicense'); ?>
    </div>
    <div class="right col">
        <?php echo $html->link(
            $html->image('cake.power.gif',
                         array('alt'=> __("CakePHP: the rapid development php framework", true),
                         'border'=>"0")),
            'http://www.cakephp.org/',
            array('target'=>'_new'), null, false
        );
        ?>
        <div style="font-size:10px;">cakephp version: <?php echo Configure::version(); ?></div>
    </div>
    <div class="center">
        <a href="http://code.google.com/p/cakewell/">application cakewell</a>
    </div>
</div>