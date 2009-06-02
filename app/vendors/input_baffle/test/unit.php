<?php

/***  Documentation

    Simpletest Template
    
    Revision: $Rev$
    Last Update: $Date$
    Author: Tom at klenwell@gmail.com
  
NOTES

______________________________________________________________________________*/

// load simpletest
$dn1 = dirname;
$ds = DIRECTORY_SEPARATOR;
$libdir = $dn1($dn1($dn1($dn1(__FILE__)))) . $ds . 'oss' . $ds;
require_once($libdir . "simpletest{$ds}unit_tester.php");

// include classes to be tested
require_once($dn1($dn1(__FILE__)) . $ds . 'input_baffle.php');

class InputBaffleTestCase extends UnitTestCase {
    
    var $debug = 0;
    var $is_set_up_once = 0;
    
    function setUp() 
    {
        $this->setUpOnce();    
    }
    
    function tearDown() 
    {
    }
    
    function setUpOnce()
    {
        if ( $this->is_set_up_once ) return;                
        
        // set up
        $BafflerConfig = array('debug'=>0, 'TagList'=>array('a', 'i', 'b'));
        $this->Baffler = new InputBaffle($BafflerConfig);
        $utime = microtime();
        $this->start_timer = substr($utime,0,9) + substr($utime,-10);
        $this->is_set_up_once = 1;
    }
    
    

    /* TEST METHODS */  
    function TestInstance() 
    {
        $this->assertTrue(is_a($this->Baffler, 'InputBaffle'));
    }
    
    function TestFilterBasic()
    {
        $sample = 'test <strong>message</strong>';
        $expect = 'test message';
        $this->assertEqual($this->Baffler->sanitize($sample), $expect);
    }
    
    function TestHtmlCheckBasic()
    {
        $sample = 'This is <b>ok</b>.  This is <i>not<i>.';
        $expect = 'test message';
        $this->assertEqual($this->Baffler->html_is_ok($sample), false);
        $WarningList = $this->Baffler->getWarnings();
        $this->assertEqual($WarningList[0], 'mismatched tag');
    }
    
    function TestBasicValidation()
    {
        $sample = '<div>This is <strong>strong</strong>.  This is <i>i</i>.</div>';
        $expect = 'This is strong.  This is <i>i</i>.';
        $sample = $this->Baffler->sanitize($sample);
        $this->assertTrue($this->Baffler->html_is_ok($sample));
        $this->assertEqual($sample, $expect);
    }
    
    function TestBasicUsage()
    {
        $Test = array(
            # subject, expect
            array('This is <b>ok</b>.  This is, <i>too</i>.', 1),
            array('This is <b>ok</b>.  This is <i>not<i>.', 1),
            array('This is problematic: < < < > > > >', 1),
            array('This is ill-formed: <i>?</b>', 0),
        );
        
        foreach ( $Test as $T )
        {
            $text = $this->Baffler->sanitize($T[0]);         
            $this->assertEqual($this->Baffler->validates($text), $T[1]);
        }
    }
    
    function TestPreprocessor()
    {
        $Test = array(
            # subject, expect
            array('<<a>>', '<a>'),
            array('<< << a >> >>> >', '< < a > > >'),
            array('<<<>>>', ''),            
        );
        
        foreach ( $Test as $T )
        {
            $this->assertEqual($this->Baffler->preprocess($T[0]), $T[1]);
        }
    }
    
    function TestSnakeChallenge()
    {
        $this->assertTrue($this->Baffler->pass_rsnake_challenge());
        if ( !$this->Baffler->pass_rsnake_challenge() ) $this->pr($this->Baffler->rsnake_challenge_failures());
    }



    /* HELPER METHODS */
    function get_script_time()
    {
        $utime = microtime();
        return number_format((substr($utime,0,9) + substr($utime,-10)) - $this->start_timer, 4);
    }

    function kill($html, $message='dying')
    {
        $this->print_d(htmlspecialchars($html), '#600');
        print $html;
        die($message);
    }
    
    function echo_d($message, $color='#ccc')
    {
        $_D = debug_backtrace();
        $f = basename($_D[0]['file']);
        $l = $_D[0]['line'];
        $loc = "{$f}:{$l}";
        $out = "<div style='line-height:1.5em; font-family:monospace; color:$color;'>$message <span style='color:#666;'>[$loc]</span></div>";
        $this->_DBG[] = "$loc -> " . strip_tags($message);
        echo $out;
        return;
    }
    
    function print_d($message, $color='#c33')
    {
        if ( $this->debug ) $this->echo_d($message, $color);
        return;
    }
    
    function print_r($Mixed)
    {
        $return = htmlspecialchars(print_r($Mixed, 1));
        $return = "<pre>$return</pre>";
        return $return;
    }
    
    function pr($Mixed, $header='')
    {
        if ( !empty($header) ) $header = "<b>$header</b>";
        $return = htmlspecialchars(print_r($Mixed, 1));
        $return = "<pre style='border:1px solid #ccc; background:#f3f3f3; padding:4px;'>$header\n\n$return</pre>";
        $this->echo_d($return, '#333');
    }
    
    function dump()
    {
        echo $this->print_r($this);
        return;
    }
}


$Test = &new InputBaffleTestCase();
$Test->run(new HtmlReporter());

$Test->echo_d('test complete in '.$Test->get_script_time().' s', '#600');
?>
