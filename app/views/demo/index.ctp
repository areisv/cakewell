<?php

if ( !isset($header) ) $header = 'cakewell demo';
if ( !isset($content) ) $content = '<div style="color:#eee;">no content</div>';
if ( !isset($menu) ) $menu = 'not found';

// this will update the head section with raw markup
#$this->addScript($html);

// stylesheet (will add to $scripts_for_layout)
$html->css('klenwell.basic', null, array(), false);
$html->css('cakewell.demo', null, array(), false);

?>
<div class="cakewell-demo" id="cakewell-demo-index">

<div class="controller_menu">
<h2>menu</h2>
<ul>
<?php echo $menu; ?>
</ul>
</div>

<h2><?php echo $header; ?></h2>

<?php echo $content; ?>

</div>