<?php
/*
    Cakewell Component Test Template
    Author: Tom at klenwell@gmail.com
    Last Update: $Date$
*/
App::import('Component', 'authwell.Auth');
App::import('Controller', 'authwell.Authwell');
App::import('Component', 'Session');

class AuthwellCtrl {
    var $name = 'AuthwellCtrl';
    var $Session = null;

    function __construct() {
        $this->Session = new SessionComponent();
    }
}

class AuthComponentTest extends CakeTestCase {

    function setUp()
    {
        $this->AuthComponent = new AuthComponent();
        $Ctrl = new AuthwellCtrl();
        $this->AuthComponent->initialize($Ctrl);
    }

    function teardown()
    {
        unset($this->AuthComponent);
    }

    function testInstance() {
        $this->assertTrue(is_a($this->AuthComponent, 'AuthComponent'));
        $this->assertTrue(is_a($this->AuthComponent->Ctrl, 'AuthwellCtrl'));
    }

    function testDiffDotpaths() {
        $keypath = 'a.b.c';
        $LockList = array(
            'a' => array(1=>'b',2=>'c'),    # 0
            'a.b' => array(2=>'c'),         # 0
            'a.b.c' => array(),             # 1
            'a.b.c.x' => array(),           # 1
            'a.b.x' => array(2=>'c'),       # 0
            'a.x' => array(1=>'b',2=>'c'),  # 0
            'x' => array('a','b','c'),      # 0
            'b' => array('a','b','c'),      # 0
            'b.c' =>array('a','b','c'),     # 0
            '*' => array(),                 # 1
            '*.b.c' => array(),             # 1
            '*.x' => array(1=>'b',2=>'c'),  # 0
        );

        foreach ( $LockList as $lockpath => $ExpectSet )
        {
            $DiffSet = $this->AuthComponent->_diff_dotpaths($keypath, $lockpath);
            #debug($DiffSet);
            $this->assertEqual($DiffSet, $ExpectSet);
        }
    }
}

?>
