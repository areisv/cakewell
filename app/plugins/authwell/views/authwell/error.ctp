<?php

if ( !isset($name) ) $name = 'Page Unavailable';

?>

<h2><?php echo $name; ?></h2>
<p class="error">
    <?php echo $message; ?>
</p>
