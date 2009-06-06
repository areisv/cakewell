<?php
/*
    Basic Template for Elements

    Usage:
        $html = $this->element('t_', array( 'foo'   => 'sample var',
                                            'cache' => '+2 days' ) );
*/

// this will update the head section with raw markup
#$this->addScript($html_block);

// stylesheet (will add to $scripts_for_layout)
#$html->css('klenwell.basic.css', null, array(), false);

if ( !isset($foo) ) $foo = false;

?>

<?php if ( $foo ): ?>

    'Foo is set'

<?php else: ?>

    <!-- Foo Not Set -->

<?php endif; ?>