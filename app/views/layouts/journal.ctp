<?php
/* SVN FILE: $Id: default.ctp 6311 2008-01-02 06:33:52Z phpnut $ */

$head_title = 'Cakewell';
$ga_element = $this->element('analytics', array('code' => GA_CODE));
$cc_element = $this->element('cclicense');
$demo_link = $html->link('cakewell demo index', '/demo/index');
$version = Configure::version();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>
		<?php echo $head_title; ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $html->charset();
		echo $html->meta('icon');

		echo $html->css('cakewell.generic');
		echo $html->css('cakewell.journal');
                echo $html->css('klenwell.basic');

		echo $scripts_for_layout;
	?>
</head>

<body>
<div id="container">

<div id="header">
<?php if ( isset($source_links) ) print $source_links; ?>
<h1><?php echo $html->link(__('cakewell home', true), '/'); ?></h1>
<h1><?php echo $demo_link; ?></h1>
<div style="clear:both;"></div>
</div>

<div id="content">
<?php
    if ($session->check('Message.flash')):
        $session->flash();
    endif;
?>

    <div class="right">
        <?php if ( isset($index_menu) ) print $index_menu; ?>
    </div>

<?php echo $content_for_layout; ?>
</div>


<div id="footer">
    <div style="float:left"><?php echo $cc_element; ?></div>
    <?php echo $html->link(
        $html->image('cake.power.gif', array('alt'=> __("CakePHP: the rapid development php framework", true), 'border'=>"0")),
        'http://www.cakephp.org/',
        array('target'=>'_new'), null, false
    );
    ?>
    <div style="font-size:10px;">cakephp version: <?php echo $version; ?></div>
</div>

</div>
        <?php echo $ga_element; ?>
	<?php echo $cakeDebug; ?>
</body>
</html>
