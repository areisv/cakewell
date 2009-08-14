<?php
    // set form unique id
    $fuid = $commentForm->fuid($form_key, $dom_id);

    $ajax_url = '/comment/form/';
    $form_message = ( !empty($form_message) ) ? sprintf('<h4>%s</h4>', $form_message) : '';

    $restart_t = '<div class="restart"><p>Click the button below to post another comment.</p>%s</div>';
    $restart_html = '';
    if ( $multiples_ok )
        $restart_html = sprintf($restart_t,
            $form->button('new comment',
                array('type'=>'button', 'onclick'=>"javascript:cakewell_reset_comment_form('$fuid')")
            )
        );

    // adds all javascript function needed for ajax submission
    echo $commentForm->get_reset_comment_js($form_key, $dom_id, $ajax_url, $callback);

    // adds callback
    echo $commentForm->get_callback($callback);
?>


<div class="ajax_comment_complete">
<h2>Comment Successful</h2>
<p class="thanks">Thank you for your comment.</p>
<?php echo $restart_html?>
</div>
