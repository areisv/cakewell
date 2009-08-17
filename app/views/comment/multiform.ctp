<?php

if ( !isset($header) ) $header = 'Cakewell Sandbox';
$summary = ( isset($summary) ) ? '<h3>'.$summary.'</h3>' : '';
if ( !isset($output) ) $output = 'no output';
if ( is_array($output) ) $output = htmlspecialchars(print_r($output, 1));

// this will update the head section with raw markup
$head_content = <<<XHTML
    <!-- Google jQuery -->
    <script src="http://www.google.com/jsapi"></script>
    <script>
        google.load("jquery", "1.3.2");
        google.load("jqueryui", "1.7.1");
    </script>
    <script type="text/javascript" src="http://api.recaptcha.net/js/recaptcha_ajax.js"></script>
XHTML;
$this->addScript($head_content);

// stylesheet (will add to $scripts_for_layout)
$html->css('cakewell.comment.index', null, array(), false);

?>

<div class="cakewell-comment" id="cakewell-comment-multiform">

<h4 class="index-link"><a href="/comment">comment index</a></h4>

<table><tr>

<td id="form1">
    <h3>comment form 1</h3>
    <div id="<?php echo $dom_id1; ?>"></div>
    <div id="comment-list-parent-1">
        <h3>comments (form 1)</h3>
        <div id="<?php echo $list_dom1; ?>"></div>
    </div>
</td>

<td id="form2">
    <h3>comment form 2</h3>
    <div id="<?php echo $dom_id2; ?>"></div>
    <div id="comment-list-parent-2">
        <h3>comments (form 2)</h3>
        <div id="<?php echo $list_dom2; ?>"></div>
    </div>
</td>

</tr></table>

<?php
    // comment form 1
    echo $this->element( 'comment_form',
                         array( 'form_key' => $form_key1,
                                'dom_id'   => $dom_id1,
                                'meta_id'  => $meta_id1,
                                'callback' => $callback ) );
?>

<?php
    // comment list 1
    echo $this->element( 'comment_list',
                         array( 'list_dom' => $list_dom1,
                                'form_key' => $form_key1,
                                'dom_id'   => $dom_id1,
                                'meta_id'  => $meta_id1,
                                'limit' => 10) );
?>

<?php
    // comment form 2
    echo $this->element( 'comment_form',
                         array( 'form_key' => $form_key2,
                                'dom_id'   => $dom_id2,
                                'meta_id'  => $meta_id2,
                                'callback' => $callback ) );
?>

<?php
    // comment list 2
    echo $this->element( 'comment_list',
                         array( 'list_dom' => $list_dom2,
                                'form_key' => $form_key2,
                                'dom_id'   => $dom_id2,
                                'meta_id'  => $meta_id2,
                                'limit' => 10) );
?>

</div>
