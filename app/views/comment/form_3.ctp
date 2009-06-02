<?php

    $form_message = ( !empty($form_message) ) ? "<h4>$form_message</h4>" : '';

?>

<script type="text/javascript">


function start_over_()
{
    var dom_id = '<?php print $dom_id; ?>';
    var FormData = { };

    $('#ajax_comment_form').find(':input').each( function(i) {
        if ( !$(this).attr('name') ) return;
        FormData[$(this).attr('name')] = $(this).val();
    });

    $('#'+dom_id).load( '/comment/form/', FormData );
}


</script>

<h1>Comment Received</h1>
Thanks for the comment.  Click button below to post another comment.

    <?php echo $form->create('Comment', array('url' => '/comment/form', 'id'=>'ajax_comment_form'));?>
        <?php echo $form->hidden('dom_id', array( 'id'=>'dom_id', 'value'=>$dom_id) );?>
        <?php echo $form->hidden('form_id', array( 'id'=>'form_id', 'value'=>$form_id) );?>
        <?php echo $form->hidden('submitted', array( 'id'=>'submitted', 'value'=>'reset_') );?>
        <?php #echo $form->submit('preview');?>
        <?php echo $form->button('new comment', 
            array('type'=>'button', 'onclick'=>'javascript:start_over_()'));?>
    <?php echo $form->end(); ?>