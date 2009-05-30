<?php
/*
    A simple element displaing creative commons license
    
    PHP could be included, but this one is just a basic example used in the
    default template to demonstrate how the template can use elements.
*/

$display_year = ( isset($year) ) ? $year : 'no year specified';

/* Unfortunately, this does not work
$this->helpers[] = 'Time';
$ago = $time->relativeTime('2008-09-15', array(
                    'format' => 'j M Y',
                    'end' => '+20 years'
                    ));
*/

?>

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/us/">
<img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/us/88x31.png" />
</a>
<!-- this site was created $ago -->
<!-- <?php echo $display_year; ?> -->