<?php

if ( !isset($header) ) $header = 'cakewell demo';
if ( !isset($content) ) $content = '<div style="color:#eee;">no content</div>';
if ( !isset($menu) ) $menu = 'not found';

// this will update the head section with raw markup
$jquery_import = <<<XHEAD
    <script src="http://www.google.com/jsapi"></script>
    <script>
        google.load("jquery", "1.3.2");
        google.load("jqueryui", "1.7.1");
    </script>
XHEAD;
$this->addScript($jquery_import);

// stylesheet (will add to $scripts_for_layout)
$html->css('klenwell.basic', null, array(), false);
$html->css('cakewell.demo', null, array(), false);

?>
<div class="cakewell-demo" id="cakewell-demo-ajax">

    <div class="controller_menu">
        <h2>menu</h2>
        <ul>
            <?php echo $menu; ?>
        </ul>
    </div>

    <h2><?php echo $header; ?></h2>

    <h4 id="result">push button for random number</h4>
    <input style="width:180px;" type="button" value="get random number"
        onclick="javascript:ajax_call();" />

    <script>
        function ajax_call() {
            $.get(
                '/services/random',
                {},
                function (data) {
                    if ( data.number ) {
                        $('#result').text(data.number);
                    }
                },
                'json'
            );
        }
    </script>

</div>
