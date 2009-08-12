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
$html->css('klenwell.basic', null, array(), false);
#$html->css('cakewell.comment', null, array(), false);

?>

<div class="cakewell-sandbox" id="cakewell-sandbox-commentform">

<div id="comment-list-parent">
     <h3>comments</h3>
    <div id="<?php echo $list_dom; ?>">
        <!-- Will be dynamically replaced by jquery via the comment_list element -->
    </div>
</div>

<div id="<?php echo $dom_id; ?>">
    <!-- Will be dynamically replaced by jquery via the comment_form element -->
</div>

<?php
    echo $this->element( 'comment_form',
                         array( 'form_key' => $form_key,
                                'dom_id'   => $dom_id,
                                'meta_id'  => $meta_id,
                                'callback' => $callback ) );
?>

<?php
    echo $this->element( 'comment_list',
                         array( 'list_dom' => $list_dom,
                                'form_key' => $form_key,
                                'dom_id'   => $dom_id,
                                'meta_id'  => $meta_id,
                                'limit' => 'null') );
?>

</div>
