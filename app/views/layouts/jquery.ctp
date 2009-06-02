<?php
/* SVN FILE: $Id: default.ctp 6311 2008-01-02 06:33:52Z phpnut $

    Cakewell jQuery Layout
    
    Uses Google's AJAX API

*/

$head_title = 'Cakewell';
$default_pageid = 'page-jquery-template';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>
        <?php echo $head_title; ?>
        <?php echo $title_for_layout; ?>
    </title>
        
    <!-- Meta Tags -->
    <?php
        echo $html->charset();
        echo $html->meta('icon');
    ?>
    
    <!-- Google jQuery -->
    <script src="http://www.google.com/jsapi"></script>
    <script>
        google.load("jquery", "1.3.2");
        google.load("jqueryui", "1.7.1");
    </script>    
    
    <!-- CSS / Settings for View -->
    <?php
        echo $html->css('cakewell.jquery_layout');
        echo $scripts_for_layout;
    ?>

</head>
<!-- NECK -->
<body>

<div class="page" id="<?php echo ( isset($pageid_for_layout) ) ? $pageid_for_layout : $default_pageid; ?>">

<div id="masthead">
    <h1><?php echo $html->link('Cakewell Demo: home', '/demo'); ?></h1>
</div>
              
<div id="content">
    <div id="session-flash">
        <?php if ($session->check('Message.flash')) echo $session->flash(); ?>
    </div>
    
    <?php echo $content_for_layout; ?>
    
</div>

<?php echo $this->element('footer'); ?>
        
</div>

    <?php echo $cakeDebug; ?>

</body>
</html>
