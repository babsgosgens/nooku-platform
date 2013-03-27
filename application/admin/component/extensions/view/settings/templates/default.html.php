<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
?>

<!--
<script src="media://js/koowa.js" />
<style src="media://css/koowa.css" />
-->

<ktml:module position="toolbar">
    <?= @helper('toolbar.render', array('toolbar' => $toolbar))?>
</ktml:module>

<form action="" method="post" class="-koowa-form" >
<?= @helper('tabs.startPane') ?>
<h3><?= @text('Settings')?></h3>
<?= @template('default_system.html', array('settings' => $settings->system)); ?>

<h3><?= @text('Extensions')?></h3>
<? foreach($settings as $name => $setting) : ?>
	<? if($setting->getType() == 'component' && $setting->getPath()) : ?>
	    <?= @template('default_extension.html', array('settings' => $setting)); ?>
	<? endif; ?>
<? endforeach; ?>
<?= @helper('tabs.endPane') ?>
</form>