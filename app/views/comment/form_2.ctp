<?php

?>

<script type="text/javascript">

function submit_preview_()
{
    var dom_id = '<?php print $dom_id; ?>';
    var FormData = { };

    $('#ajax_comment_form').find(':input').each( function(i) {
        if ( !$(this).attr('name') ) return;
        FormData[$(this).attr('name')] = $(this).val();
    });
    console.log(FormData);
    //alert('ready to submit?'); 
    $('#'+dom_id).load( '/comment/form/', FormData );
}

</script>

<h1>Comment Form</h1>
<h2>preview your comment</h2> 
    <div class="comment_preview">
    <div class="comment_text"><?php print $comment_text; ?></div>
    <div class="comment_user">user: <?php print $comment_user; ?></div>
    <div class="comment_url">url: <?php print $comment_url; ?></div>
    </div>
    <?php echo $form->create('Comment', array('url' => '/comment/form', 'id'=>'ajax_comment_form'));?>
        <?php echo $form->hidden('text', array( 'id'=>'comment_text', 'value'=>$comment_text) );?>
        <?php echo $form->hidden('author', array( 'id'=>'comment_user', 'value'=>$comment_user) );?>
        <?php echo $form->hidden('author_url', array( 'id'=>'comment_url', 'value'=>$comment_url) );?>
        <?php echo $form->hidden('dom_id', array( 'id'=>'dom_id', 'value'=>$dom_id) );?>
        <?php echo $form->hidden('form_id', array( 'id'=>'form_id', 'value'=>$form_id) );?>
        <?php echo $form->hidden('submitted', array( 'id'=>'submitted', 'value'=>'preview_') );?>
        <?php #echo $form->submit('preview');?>
        <?php echo $form->button('publish', 
            array('type'=>'button', 'onclick'=>'javascript:submit_preview_()'));?>
    <?php echo $form->end(); ?>