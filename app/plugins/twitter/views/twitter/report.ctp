<?php

if ( empty($header) ) $header = 'Cakewell Twitter Plugin';
if ( empty($Data) ) $Data = array( 'no data' );


?>

<?php printf('<h2>%s</h2>', $header); ?>
<?php printf('<pre>%s</pre>', print_r($Data,1)); ?>

<h5>using <a href="http://bakery.cakephp.org/articles/view/twitter-datasource">iscandr twitter datasource</a></h5>
