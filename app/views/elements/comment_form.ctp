<?php
/*
    Element for Comment Form
        This outputs the js script that dynamically loads the comment form from
        a controller though ajax call
        
        Element arguments:
            form_id
                an id for this form.  This will be used to retrieve comments
                by the comment_list element so that they will be grouped
                together.
            dom_id
                id for the DOM element that will by dynamically updated by
                the element
            multiples_ok
                determines whether a button is show upon comment submission
                making it easier to submit multiples

    Requirements:
        Required jquery be loaded by the layout or view
        
    Usage:
        $html = $this->element( 'comment_form',
                                array( 'form_key' => 'foo',
                                       'dom_id'  => 'bar',
                                       'multiples_ok' => false ) );
*/

if ( !isset($form_key) )
    trigger_error('comment form element must be passed form_key', E_USER_WARNING);

if ( !isset($dom_id) )
    trigger_error('comment form element must be passed dom_id', E_USER_WARNING);

if ( !isset($meta_id) )
    $meta_id = null;
    
if ( !isset($multiples_ok) )
    $multiples_ok = 1;

// stylesheet (will add to $scripts_for_layout)
$html->css('cakewell.comment.css', null, array(), false);

?>

<script type="text/javascript">

$(document).ready( function() {
    
    // display a loading message or image
    $('#<?php print $dom_id; ?>').html('loading comment form');
    
    // this is a jquery ajax call
    $('#<?php print $dom_id; ?>').load(
        '/comment/form/',
        { 'form_key' : '<?php print urlencode($form_key); ?>',
          'dom_id' : '<?php print urlencode($dom_id); ?>',
          'meta_id' : '<?php print urlencode($meta_id); ?>',
          'multiples_ok' : '<?php print urlencode($multiples_ok); ?>' }
    );
});

</script>

<noscript>javascript required for comments</noscript>