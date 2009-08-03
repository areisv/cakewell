<?php
/*
    A simple element displaying google analytics code

    PHP could be included, but this one is just a basic example used in the
    default template to demonstrate how the template can use elements.
*/

$display = ( $code == 'UA-CODE-HERE' || empty($code) ) ? false : true;

?>

<?php if ( $display ): ?>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("<?php echo $code; ?>");
pageTracker._initData();
pageTracker._trackPageview();
</script>

<?php else: ?>

<!-- Google Analytics Disabled (no code provided) -->

<?php endif; ?>
