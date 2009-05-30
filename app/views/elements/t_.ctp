<?php
/*
    Basic Template for Elements

    Usage:
        $html = $this->element('t_', array( 'foo'   => 'sample var',
                                            'cache' => '+2 days' ) );
*/

if ( !isset($foo) ) $foo = false;

?>

<?php if ( $foo ): ?>

    'Foo is set'

<?php else: ?>

    <!-- Foo Not Set -->

<?php endif; ?>