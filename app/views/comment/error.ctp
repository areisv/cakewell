<?php

$message = ( !empty($message) ) ? sprintf(' : %s', $message) : '';

?>


<div class="error">
    comment form not available<?php echo $message; ?>
    <!-- error: <?php echo $error; ?> -->
</div>