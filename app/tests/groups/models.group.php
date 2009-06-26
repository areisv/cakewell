<?php

class CakewellModelGroupTest extends GroupTest {

    var $label = 'Cakewell model tests';

    function CakewellModelGroupTest() {
        
        $model_dir = dirname(dirname(__FILE__)) . DS . 'cases' . DS . 'models';
        
        TestManager::addTestCasesFromDirectory($this, $model_dir);
        #TestManager::addTestFile($this, $model_dir . 'comment_fixture.test.php');
    }
}

?>