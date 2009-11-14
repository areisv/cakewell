<?php

/*  Cakewell Error Layout

    Last Update: $date$
    Author: Tom at klenwell@gmail.com

    NOTES
    Will automatically redirect to home page after 10 s.
    Includes google analytics element
*/

$head_title = 'Cakewell';
$ga_element = $this->element('analytics', array('code' => GA_CODE));
#$cc_element = $this->element('cclicense');
$version = Configure::version();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>
            <?php echo $head_title; ?>
            <?php echo $title_for_layout; ?>
	</title>
        <meta http-equiv="refresh" content="10;url=/">
	<?php
            echo $html->charset();
            echo $html->meta('icon');
            echo $html->css('cakewell.error');

            echo $scripts_for_layout;
	?>
</head>

<body>

<!-- PAGE -->
<div class="page" id="layout-error">

<h4><a href="/">home page</a></h4>
(will automatically redirect after 10 seconds)


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

<?php echo $ga_element; ?>


</body>
</html>
