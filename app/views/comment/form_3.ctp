<?php

    $ajax_url = '/comment/form/';
    $form_message = ( !empty($form_message) ) ? sprintf('<h4>%s</h4>', $form_message) : '';
    
    $restart_t = '<div class="restart"><p>Click the button below to post another comment.</p>%s</div>';
    $restart_html = '';
    if ( $multiples_ok )
        $restart_html = sprintf($restart_t,
            $form->button('new comment', 
                array('type'=>'button', 'onclick'=>'javascript:reset_comment_form_()')
            )
        );

    // adds all javascript function needed for ajax submission
    echo $commentForm->get_javascript_functions($dom_id, $form_key, $ajax_url);
?>


<div class="ajax_comment_complete">
<h2>Comment Successful</h2>
<p class="thanks">Thank you for your comment.</p>
<?php echo $restart_html?>
</div>