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

    Requirements:
        Required jquery be loaded by the layout or view
        
    Usage:
        $html = $this->element( 'comment',
                                array( 'form_id'  => 'sample var',
                                       'dom_id' => '+6 hours' ) );
*/

if ( !isset($dom_id) )
    trigger_error('comment form element must be passed dom_id', E_USER_WARNING);
    
if ( !isset($form_id) )
    trigger_error('comment form element must be passed form_id', E_USER_WARNING);

?>

<script type="text/javascript">

// display a loading message or image
$('#<?php print $dom_id; ?>').html('loading comment form');

// this is a jquery ajax call
$('#<?php print $dom_id; ?>').load(
    '/comment/form/',
    { 'form_id' : '<?php print urlencode($form_id); ?>',
      'dom_id' : '<?php print urlencode($dom_id); ?>' }
);

</script>

<noscript>javascript required for comments</noscript>