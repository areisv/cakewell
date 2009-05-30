<?php
/* SVN FILE: $Id: home.ctp 7690 2008-10-02 04:56:53Z nate $ */
$html->css('klenwell.basic', null, array(), false);
$html->css('cakewell.demo', null, array(), false);

?>
<h2>Welcome to Cakewell CakePhp Demo Application</h2>
<p>
This is a CakePhp application set up to help you set up new CakePhp applications
and get familiar with CakePhp features and conventions.
</p>

<ul>
    <li><a href="/demo/">Demo Controller</a></li>
    <!-- <li><a href="/sandbox/">Sandbox Controller</a></li> -->
    <li><a href="http://code.google.com/p/klenwell/">Klenwell Google Code Home</a></li>
</ul>

<p>
If you wish to edit this page, edit the view file in <tt>/app/views/pages/home.ctp</tt>
</p>


<hr/>

<?php
if(Configure::read() > 0):
	Debugger::checkSessionKey();
endif;
?>
<p>
	<?php
		if (is_writable(TMP)):
			echo '<span class="notice success">';
				__('Your tmp directory is writable.');
			echo '</span>';
		else:
			echo '<span class="notice">';
				__('Your tmp directory is NOT writable.');
			echo '</span>';
		endif;
	?>
</p>
<p>
	<?php
		$settings = Cache::settings();
		if (!empty($settings)):
			echo '<span class="notice success">';
					echo sprintf(__('The %s is being used for caching. To change the config edit APP/config/core.php ', true), '<em>'. $settings['engine'] . 'Engine</em>');
			echo '</span>';
		else:
			echo '<span class="notice">';
					__('Your cache is NOT working. Please check the settings in APP/config/core.php');
			echo '</span>';
		endif;
	?>
</p>
<p>
	<?php
		$filePresent = null;
		if (file_exists(CONFIGS.'database.php')):
			echo '<span class="notice success">';
				__('Your database configuration file is present.');
				$filePresent = true;
			echo '</span>';
		else:
			echo '<span class="notice">';
				__('Your database configuration file is NOT present.');
				echo '<br/>';
				__('Rename config/database.php.default to config/database.php');
			echo '</span>';
		endif;
	?>
</p>
<?php
if (isset($filePresent)):
	uses('model' . DS . 'connection_manager');
	$db = ConnectionManager::getInstance();
	@$connected = $db->getDataSource('default');
?>
<p>
	<?php
		if ($connected->isConnected()):
			echo '<span class="notice success">';
	 			__('Cake is able to connect to the database.');
			echo '</span>';
		else:
			echo '<span class="notice">';
				__('Cake is NOT able to connect to the database.');
			echo '</span>';
		endif;
	?>
</p>
<?php endif;?>

<h3><?php __('Editing this Page'); ?></h3>
<p>
<?php
__('To change the content of this page, edit: APP/views/pages/home.ctp.<br />
To change its layout, edit: APP/views/layouts/default.ctp.<br />
You can also add some CSS styles for your pages at: APP/webroot/css.');
?>
</p>

<h3><?php echo sprintf(__('Release Notes for CakePHP %s.', true), Configure::version()); ?></h3>
<a href="https://trac.cakephp.org/wiki/notes/1.2.x.x"><?php __('Read the release notes and get the latest version'); ?> </a>
