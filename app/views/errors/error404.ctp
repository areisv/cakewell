<?php
/**

    Based on cake_core/cake/libs/view/errors/error404.ctp

 */

if ( !isset($link) ) $link = '/';
if ( !isset($label) ) $label = 'return home';

?>
<h2><?php echo $name; ?></h2>
<p class="error">
	<strong><?php __('Page Not Found'); ?>: </strong>
	<?php echo sprintf(__("The requested address %s was not found or is unavailable.", true), "<strong>'{$message}'</strong>")?>
</p>

<?php
    if ( isset($text) )
        printf('<div class="%s">%s</div>', 'text', $text);
?>
<div class="link"><a href="<?php echo $link; ?>"><?php echo $label; ?></a>
