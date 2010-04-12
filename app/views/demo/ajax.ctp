<?php

if ( !isset($header) ) $header = 'cakewell ajax demonstration';
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

    <h4 id="response">push a button below to amke a simple ajax request to the
        services controller</h4>

    <input type="button" class="left" style="width:auto; margin:4px;"
        value="get random number"
        onclick="javascript:random_number();" />
    <input type="button" class="left" style="width:auto; margin:4px;"
        value="roll a die"
        onclick="javascript:roll_die();" />

    <script>
        function random_number() {
            var ajax_url = '/services/random';
            $('#response').html('retrieving random number...');

            setTimeout(function() {
                $.get(
                    ajax_url,
                    {},
                    function (data) {
                        if ( data.number ) {
                            $('#response').html('number: <strong>' +
                                                data.number + '</strong>');
                        }
                        else {
                            $('#response').text('request failed');
                        }
                    },
                    'json'
                );
            }, 500)
        }

        function roll_die() {
            var ajax_url = '/services/dice/6?callback=?';
            var base_die_code = 9855;
            $('#response').html('rolling a 6-sided dice...');

            setTimeout(function() {
                $.getJSON( ajax_url, function (json) {
                    if ( json.rolled ) {
                        var die_html = '';
                        var die_code = 0;
                        if ( json.rolled >= 1 && json.rolled <= 6 ) {
                            var die_code = base_die_code + json.rolled;
                        }
                        if ( die_code ) {
                            die_html = ' ( &#' + parseInt(die_code) + '; )';
                        }

                        $('#response').html('rolled a <strong>' +
                                            json.rolled + '</strong>' +
                                            die_html );
                    }
                    else {
                        $('#response').text('request failed');
                    }
                });
            }, 1000)
        }
    </script>

</div>
