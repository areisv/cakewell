<?php

if ( !isset($header) ) $header = 'cakewell demo';
if ( !isset($data) ) $data = 'none';
if ( !isset($menu) ) $menu = 'not found';
if ( is_array($data) ) $data = htmlspecialchars(print_r($data, 1));

// this will update the head section with raw markup
#$this->addScript();

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

<h3>data</h3>
<pre>
<?php echo $data; ?>
</pre>

</div>