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

		echo $html->css('cakewell.home');

		echo $scripts_for_layout;
	?>
</head>

<body>
<table id="shell"><tr><td>

<!-- PAGE -->
<div class="page" id="page_scriptframe">


<div id="content">
<?php
    if ($session->check('Message.flash')):
        $session->flash();
    endif;
?>

<?php echo $content_for_layout; ?>
</div>


</div>
<!-- end PAGE -->

<div id="footer_panel">
    <div>
        cakephp version:
        <a class="version" href="https://trac.cakephp.org/browser/tags">
            <?php echo $version; ?>
        </a>
    </div>
</div>

</td></tr></table>

<!-- Google Analytics -->
<!-- end Google Analytics -->


</body>
</html>
