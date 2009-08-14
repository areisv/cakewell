<?php

    // set form unique id
    $fuid = $commentForm->fuid($form_key, $dom_id);

    $ajax_url = '/comment/form/';
    $form_message = ( !empty($form_message) ) ? sprintf('<h4>%s</h4>', $form_message) : '';
    $author = ( !empty($CommentData['author']) ) ? $CommentData['author'] : '';
    $author_email = ( !empty($CommentData['author_email']) ) ? $CommentData['author_email'] : '';
    $author_url = ( !empty($CommentData['author_url']) ) ? $CommentData['author_url'] : '';
    $comment_text = ( !empty($CommentData['text']) ) ? $CommentData['text'] : '';
    $taglist_ = '';

    if ( isset($TagList) )
        $taglist_ = sprintf('<h4 class="%s">the following tags are ok: <span class="tags">%s</span></h4>',
                            'taglist',
                            implode(', ', $TagList)
        );

    // testing
    if ( Configure::read('debug') > 1 )
    {
        if ( empty($comment_text) ) $comment_text = sprintf("test prefill: <code>%s</code>\n\nautomatically filled when debug is active\n\n<i>bad tag<i>", time());
        if ( empty($author_email) ) $author_email = 'test@example.com';
    }

    // generate necessary javascript functions for ajax submission using helper
    #echo $commentForm->get_javascript_functions($dom_id, $form_key, $ajax_url, $callback);
    echo $commentForm->get_submit_comment_js($form_key, $dom_id,$ajax_url, $callback);
    echo $commentForm->get_reset_comment_js($form_key, $dom_id, $ajax_url, $callback);
?>


<div class="ajax_comment_form">
<h2>Leave a Comment</h2>
<h5>e-mail address required, but will not be published</h5>
    <?php echo $form->create('Comment', array('url' => '/comment/form', 'id'=> $fuid ));?>

        <fieldset>
        <?php echo $form->input("Comment.$fuid.text", array('label' => 'comment' .  $taglist_,
                                              'value' => $comment_text,
                                              'error' => false,     # use custom
                                              'rows'  => '10', 'cols' => '30' ));?>
        <div class="error-message'><?php echo html_entity_decode($form->error('text'));?></div>
        </fieldset>

        <fieldset>
        <?php echo $form->input("Comment.$fuid.author", array('label' => 'your name', 'value'=>$author) );?>
        <?php echo $form->input("Comment.$fuid.author_email", array('label' => 'your email address*', 'value'=>$author_email) );?>
        <?php echo $form->input("Comment.$fuid.author_url", array('label' => 'your web address', 'value'=>$author_url) );?>
        <div class="honey">
            <?php echo $form->input("Comment.$fuid.$honeypot_field", array('id'=>$honeypot_field, 'label'=>'required') );?>
        </div>
        </fieldset>

        <fieldset>
        <?php echo $form->button('preview',
            array('type'=>'button', 'onclick'=>"javascript:cakewell_submit_comment_form('$fuid')"));?>
        <?php echo $form->button('reset',
            array('type'=>'button', 'onclick'=>"javascript:cakewell_reset_comment_form('$fuid')"));?>
        </fieldset>

    <?php echo $form->end(); ?>
</div>
