<?php

# this will update the head section with raw markup
#$this->addScript();

# stylesheet (will add to $scripts_for_layout)
$html->css('klenwell.basic', null, array(), false);
$html->css('cakewell.demo', null, array(), false);
$html->css('cakewell.backend', null, array(), false);

# prepare log data
function parse_log_lines($LogLines)
{
    $FormattedLines = array();
    $ParsedLines = array();
    $n = -1;
    for ( $i=0; $i<count($LogLines); $i++ )
    {
        $line = $LogLines[$i];
        $ExplodedLine = explode(' ', $line);
        if ( preg_match('%\d{4}-\d{2}-\d{2}%', $ExplodedLine[0], $Match) )
        {
            $n++;
            $date = $ExplodedLine[0];
            $time = $ExplodedLine[1];
            $message = implode(' ', array_splice($ExplodedLine, 2));
            $ParsedLines[$n] = array($date, $time, $message);
        }
        else
        {
            $ParsedLines[$n][2] .= "\n\t{$line}";
        }
    }

    $odd = 0;
    foreach ( $ParsedLines as $Line )
    {
        $odd++;
        $c_ = ( $odd % 2 == 1 ) ? 'odd' : 'even';
        $FormattedLines[] = sprintf('<div class="%s line">[<b>%s %s</b>] %s</div>',
                                    $c_, $Line[0], $Line[1], $Line[2]);

    }
    return implode("\n", $FormattedLines);
}

$error_lines = parse_log_lines($ErrorLines);
$debug_lines = parse_log_lines($DebugLines);

# alert html
$alert_html = '';
if ( !empty($alert) )
    $alert_html = sprintf('<h4>%s</h4>', $alert);

# reset button confirm message
$confirm_message = 'Are you sure you want to do this?\n' .
    '(This will delete all current error and debug logs.)';

?>
<div class="cakewell-backend" id="cakewell-backend-log">

    <?php echo $alert_html; ?>
    <h4>To add a new entry to the debug log, <a href="/backend/logs/test">click here</a></h4>

    <h3>Error Log (last <?php echo $limit; ?> lines)</h3>
    <?php printf('<div class="log-list">%s</div>', $error_lines); ?>
    <br />

    <h3>Debug Log (last <?php echo $limit; ?> lines)</h3>
    <?php printf('<div class="log-list">%s</div>', $debug_lines); ?>
    <br />

    <!-- Reset Form Here -->
    <h3>Reset Logs</h3>
    <form method="POST" action="<?php echo $this->here; ?>">
        <input type="submit" name="reset" value="reset now"
            onclick="javascript:return confirm('<?php echo $confirm_message; ?>');" />
    </form>
    </div>

</div>
