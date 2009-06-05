<?php

    $ajax_url = '/comment/form/';
    $form_message = ( !empty($form_message) ) ? sprintf('<h4>%s</h4>', $form_message) : '';

?>

<script type="text/javascript">

function start_over_()
{
    var dom_id = '<?php print $dom_id; ?>';
    var FormData = {
        'subaction': 'reset',
        'form_key': '<?php echo $form_key; ?>'
    };
    $('#'+dom_id).load( '<?php print $ajax_url; ?>', FormData );
}

</script>

<h1>Comment Received</h1>
Thanks for the comment.  Click button below to post another comment.

    <?php echo $form->button('new comment', 
        array('type'=>'button', 'onclick'=>'javascript:start_over_()'));?>