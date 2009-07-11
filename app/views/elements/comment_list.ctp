<?php
/*
    Element for Comment List
        This outputs the js script that dynamically loads the comments from
        a controller though ajax call

        Element arguments:
            list_dom
                id for the DOM element that will by dynamically updated by
                the element
            form_key
                an key for the comments table. This will be used to retrieve
                comments by one or the other.
            meta_id (optional)
                The meta_id represents the foreign key to another table with
                which the comments are associated (e.g. posts or articles)
            dom_id (optional)
                dom id for comment form (saved in db)
            limit (optional)
                number of comments to show (defaults to all)

    Requirements:
        Required jquery be loaded by the layout or view

    Usage:
        $html = $this->element( 'comment_list',
                                array( 'list_dom' => 'comments',
                                       'form_key' => 'foo',
                                       'dom_id'  => 'bar',
                                       'meta_id'  => '33',
                                       'limit' => 25 ) );
*/

if ( !isset($list_dom) )
    trigger_error('comment form element must be passed dom_parent', E_USER_WARNING);

if ( !isset($form_key) )
    trigger_error('comment form element must be passed form_key', E_USER_WARNING);

if ( !isset($dom_id) )
    $dom_id = 'null';

if ( !isset($meta_id) )
    $meta_id = 'null';

// stylesheet (will add to $scripts_for_layout)
$html->css('cakewell.comment.css', null, array(), false);

?>

<script type="text/javascript">

function load_comment_list()
{
    // display a loading message or image
    $('#<?php print $list_dom; ?>').html('loading comments');

    // this is a jquery ajax call
    $('#<?php print $list_dom; ?>').load(
        '/comment/show/',
        { 'list_dom' : '<?php print urlencode($list_dom); ?>',
          'form_key' : '<?php print urlencode($form_key); ?>',
          'dom_id' : '<?php print urlencode($dom_id); ?>',
          'meta_id' : '<?php print urlencode($meta_id); ?>',
          'limit' : '<?php print urlencode($limit); ?>' }
    );
}

$(document).ready( function() {
    load_comment_list();
});

</script>

<noscript>javascript required for comments</noscript>
