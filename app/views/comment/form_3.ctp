<?php

    $ajax_url = '/comment/form/';
    $form_message = ( !empty($form_message) ) ? sprintf('<h4>%s</h4>', $form_message) : '';
    
    $restart_t = '<div class="restart"><p>Click the button below to post another comment.</p>%s</div>';
    $restart_html = '';
    if ( $multiples_ok )
        $restart_html = sprintf($restart_t,
            $form->button('new comment', 
                array('type'=>'button', 'onclick'=>'javascript:start_over_()')
            )
        );


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

<div class="ajax_comment_complete">
<h2>Comment Successful</h2>
<p class="thanks">Thank you for your comment.</p>
<?php echo $restart_html?>
</div>