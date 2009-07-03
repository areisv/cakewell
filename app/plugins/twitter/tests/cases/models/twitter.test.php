<?php
/*
    Plugin Model Test Template

    NOTES
    I can't figure out a way to create a fixture for a model that doesn't
    have a table.  So many of the elements of this test are commented out to
    avoid errors.  Nevertheless, this can be used as a template.

    REFERENCES
    http://book.cakephp.org/view/485/Testing-plugins
*/

App::import('Model', 'TPlugin.TPlugin');

class TPluginTestCase extends CakeTestCase {

    var $TPlugin = null;
    var $fixtures = array(
            #'plugin.t_plugin.t_plugin',
        );

    function start()
    {
        parent::start();
        #$this->TPlugin = ClassRegistry::init('TPlugin');
    }

    function testSimple() {
        $this->assertTrue(1);
    }

    function testInstance() {
        #$this->assertTrue(is_a($this->TPlugin, 'TPlugin'));
    }
}
?>
