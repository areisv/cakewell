<?php
/* SVN FILE: $Id: default.ctp 6311 2008-01-02 06:33:52Z phpnut $ */

$head_title = 'Cakewell';
$cc_element = $this->element('cclicense');
$tests_link = $html->link('demo controller', '/demo/index');
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

		echo $html->css('cake.generic');

		echo $scripts_for_layout;
	?>
</head>
<body>
<div id="container">

<div id="header">
<h1 style="float:right;"><?php echo $tests_link; ?></h1>
<h1><?php echo $html->link(__('Cakewell Demo: home', true), '/'); ?></h1>
</div>
        
        
<div id="content">   
<?php
    if ($session->check('Message.flash')):
        $session->flash();
    endif;
?>

<?php echo $content_for_layout; ?>
</div>
        
        
<div id="footer">
<div style="float:left">
<?php echo $cc_element; ?>
</div>
<?php echo $html->link(
        $html->image('cake.power.gif', array('alt'=> __("CakePHP: the rapid development php framework", true), 'border'=>"0")),
        'http://www.cakephp.org/',
        array('target'=>'_new'), null, false
);
?>
<div style="font-size:10px;">cakephp version: <?php echo $version; ?></div>
</div>
	</div>
	<?php echo $cakeDebug; ?>
</body>
</html>
