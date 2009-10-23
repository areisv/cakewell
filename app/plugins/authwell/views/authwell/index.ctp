<?php

// this will update the head section with raw markup
#$this->addScript($html);

// stylesheet (will add to $scripts_for_layout)
$html->css('klenwell.basic', null, array(), false);
$html->css('authwell.view', null, array(), false);

?>
<div class="cakewell-authwell" id="cakewell-authwell-index">

<h2>Cakewell Authwell Plugin</h2>

<p>The Authwell plugin is (yet another) user authentication system.  CakePhp
provides its own <a href="http://book.cakephp.org/view/172/Authentication"
onclick="window.open(this.href,'_blank');return false;">CakePhp authentication
component</a>.  The basic concept is the same but the implementation is a little
different.</p>

<p>The Authwell system involves three different objects: users, roles, and
privileges.  A role is a set of privileges.  A user can have one or more (or no)
roles.  Access to actions can be restricted on the basis of roles or
privileges.  The details are captured in the <a href="http://code.google.com/p/cakewell/source/browse/app/plugins/authwell/mysql/authwell.sql"
onclick="window.open(this.href,'_blank');return false;">database schema</a> packaged with
the plugin.</p>

<p>Privileges allow a special dot-notation that tree-like in behavior.  Thus, a
user with privilege 'a.b' can access a page/action restricted to 'a.b' or 'a.b.c',
but not 'a' or 'x'.  See the <a href="http://code.google.com/p/cakewell/source/browse/app/plugins/authwell/tests/cases/components/auth.test.php#122"
onclick="window.open(this.href,'_blank');return false;">unit tests</a> for a full
set of examples.</p>

<p>The Authwell plugin includes a component for simple usage by controllers.  A
simple use case:</p>

<div class="code">
class DemoController extends AppController
{
    var $name = 'Demo';
    var $components = array('Authwell.Auth');

    ...

    function restricted_action()
    {
        // restrict access to users with demo.demo privilege
        $this->Auth->require_privilege('demo.demo');

        // normal action code
        ...
    }
}
</div>

<p>Although development continues on the nicer points of a full authentication
module, the basic authentication functionality of the plugin is complete.  Its
primary purpose at this point is as a detailed example of a full-fledged CakePhp
plugin.  But the ultimate goal is to create a fully functional, easy-to-use
authentication plugin.</p>

<p>For a demonstration, see the examples in the
<a href="/demo/auth_demo/">demo controller</a> or the
<a href="/authwell/demo/">demo page</a> included in the authwell plugin
controller.  The login for both examples is <strong>demo@klenwell.com</strong>
/ <strong>cakewell</strong>.</p>

</div>
