<?php

/*  InputBaffle: Klenwell Input Validation

    An input validation class tailor to light XHTML input from textarea form
    fields

    Last Update: $date$
    Author: Tom at klenwell@gmail.com
    License: GNU GPL (http://www.opensource.org/licenses/gpl-license.html)

USAGE
    $BafflerConfig = array('debug'=>0, 'TagList'=>array('a', 'i', 'b'));
    $Baffler = new InputBaffle($BafflerConfig);
    $input = $Baffle->sanitize($input);
    if ( !$Baffle->validates($input) )
    {
        print $Baffle->getWarnings();
    }
    else
    {
        // save to database
    }

NOTES
    Uses Php Input Filter (http://www.phpclasses.org/browse/package/2189.html)
    XHTML validation based on:
        http://simon.incutio.com/code/php/SafeHtmlChecker.class.php.txt

______________________________________________________________________________*/

class InputBaffle
{
    public $Filter = null;              // InputFilter object
    public $HtmlChecker = null;         // SafeHtmlChecker object
    public $TagList = array();
    public $AttrList = array();
    public $TagList_as_blacklist = 0;   // default: whitelist
    public $AttrList_as_blacklist = 0;  // default: whitelist
    public $xss_autostrip = 1;    
    
    public $DefaultTagWhitelist = array(
        'div', 'p', 'blockquote', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'span', 'a', 'b', 'i', 'em', 'strong', 'code', 'sub', 'sup',
    );
    public $DefaultAttrWhitelist = array( 'href', 'title', 'cite' );
        
    
    public $debug = 0;
    public $DS = DIRECTORY_SEPARATOR;
    
    private $_filename = '';
    private $_dirpath = '';


    /* ** MAGIC METHODS ** */
    function __construct($ConfigArray)
    {
        if ( isset($ConfigArray['debug']) ) $this->debug;
        
        // config and load helper classes
        $this->TagList = ( isset($ConfigArray['TagList']) ) ? $ConfigArray['TagList'] : $this->DefaultTagWhitelist;
        $this->AttrList = (isset($ConfigArray['AttrList']) ) ? $ConfigArray['AttrList'] : $this->DefaultAttrWhitelist;        
        if ( isset($ConfigArray['TagList_as_blacklist']) ) $this->TagList_as_blacklist = $ConfigArray['TagList_as_blacklist'];
        if ( isset($ConfigArray['AttrList_as_blacklist']) ) $this->AttrList_as_blacklist = $ConfigArray['AttrList_as_blacklist'];
        if ( isset($ConfigArray['xss_autostrip']) ) $this->xss_autostrip = $ConfigArray['xss_autostrip'];
        $this->_load_helpers();
        
        $this->_set_filename();
        $this->_set_dirpath();
    }
        
    function __destruct()
    {
    }



    /* ** PUBLIC METHODS ** */
    function validates($input)
    {
        return $this->html_is_ok($input);
    }
    
    function sanitize($input)
    {
        $input = $this->prefilter($input);
        $input = $this->Filter->process($input);
        return $this->postfilter($input);
    }
    
    function html_is_ok($input)
    {
        $this->HtmlChecker->errors = array();
        $this->HtmlChecker->check('<all>'.$input.'</all>');
        return $this->HtmlChecker->isOK();
    }
    
    function getWarnings()
    {
        $Warnings = array();
        foreach ( $this->HtmlChecker->getErrors() as $m )
        {
            $m = strtolower($m);
            if ( $m == 'mismatched tag') $m = 'mismatched html tag: please correct';
            $Warnings[] = $m;
        }
        return $Warnings;
    }
    
    function prefilter($input)
    {
        // remove superfluous angle-brackets
        $input = preg_replace('%<{2,}%', '<', $input);
        $input = preg_replace('%>{2,}%', '>', $input);
        
        // more sophisticated pattern detectors (these are disabled currently
        // as they may have unintended side effects)
        #$input = preg_replace('%<[^>]*<%', '<', $input);
        #$input = preg_replace('%>\s*>%', '>', $input);
        
        // patterns like <<<>>> will create recursion overflows in InputFilter
        $input = preg_replace('%<\s*>%', '', $input);
        
        // case-specific xss exploits
        $input = str_replace('\";alert(\'XSS\');//', '', $input);
        
        return $input;
    }
    
    function postfilter($input)
    {
        /*
            The InputFilter class will turn unclosed tags (e.g. <i>oops!<i>)
            into closed tags (<i />oops!<i />).  Firefox, for one, displays this
            as on open i tag.  So let's just get rid of any of thse.
        */
        if ( ! $this->TagList ) return $input;
        foreach ( $this->TagList as $t_ )
        {
            $SearchList[] = "<{$t_} />";
            $ReplaceList[] = "<{$t_}>";
        }
        return str_replace($SearchList, $ReplaceList, $input);
    }
    
    function load_rsnake_test_file()    
    {
        /*
            xssAttacks.xml from http://ha.ckers.org/xssAttacks.xml
            parser taken from:
                http://github.com/ezyang/htmlpurifier/blob/bfe474042f191abc87c49a8a373c39fc3b449833/smoketests/xssAttacks.php
        */
        $AttackList = array();
        
        $rsnake_file = $this->_dirpath . 'test' . $this->DS . 'xssAttacks.xml';
        $xml = simplexml_load_file($rsnake_file);
        
        foreach ($xml->attack as $attack)
        {
            $code = (string)$attack->code;
            $name = (string)$attack->name;
 
            // custom code for null byte injection tests
            if (substr($code, 0, 7) == 'perl -e')
            {
                $code = substr($code, $i=strpos($code, '"')+1, strrpos($code, '"') - $i);
                $code = str_replace('\0', "\0", $code);
            }
            
            // disable vectors we cannot test in any meaningful way
            if ($code == 'See Below') continue; // event handlers, whitelist defeats
            if ($name == 'OBJECT w/Flash 2') continue; // requires ActionScript
            if ($name == 'IMG Embedded commands 2') continue; // is an HTTP response
 
            // custom code for US-ASCII, which couldn't be expressed in XML without encoding
            if ($name == 'US-ASCII encoding') $code = urldecode($code);

            $AttackList[$name] = $code;
        }
        
        return $AttackList;
    }
    
    function pass_rsnake_challenge()
    {
        $FailList = $this->rsnake_challenge_failures();
        return ( ! $FailList['count'] );
    }
    
    function rsnake_challenge_failures($print_failures=0)
    {
        $AttackList = $this->load_rsnake_test_file();
        $Failures = array();
        
        foreach ( $AttackList as $name => $attack )
        {
            if ( $this->sanitize($attack) == $attack )
            {
                if ( !$this->validates($attack) )
                {                    
                    continue;
                }
                else
                {
                    $Failures['failed'][$name] = $attack;
                    if ( $print_failures )
                    {
                        print "<h5>failed: $name</h5><textarea rows=4>$attack</textarea>";
                    }
                }
            }                
        }
        
        $Failures['count'] = count($Failures['failed']);
        $Failures['exploit_count'] = count($AttackList);
        $Failures['result'] = "failed {$Failures['count']} of {$Failures['exploit_count']} exploits";
        return $Failures;
    }


    // print methods
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



    /* ** PRIVATE METHODS ** */
    private function _load_helpers()
    {
        require_once($this->_dirpath . 'lib' . $this->DS . 'class.inputfilter_clean.php');
        require_once($this->_dirpath . 'lib' . $this->DS . 'SafeHtmlChecker.class.php');
        $this->Filter = new InputFilter($this->TagList,
                                        $this->AttrList,
                                        $this->TagList_as_blacklist,
                                        $this->AttrList_as_blacklist,
                                        $this->xss_autostrip);
        $this->HtmlChecker = new SafeHtmlChecker();
        return;
    }
    
    function _set_filename() { $this->_filename = basename(__FILE__); }
    function _set_dirpath() { $this->_dirpath = dirname(__FILE__) . $this->DS; }
}
?>
