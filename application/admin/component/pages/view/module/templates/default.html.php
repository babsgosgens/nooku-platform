<?
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<script src="media://js/koowa.js" />
<?= @helper('behavior.validator') ?>

<ktml:module position="toolbar">
    <?= @helper('toolbar.render', array('toolbar' => $toolbar))?>
</ktml:module>

<form action="<?= @route('id='.$module->id.'&application='.$state->application) ?>" method="post" class="-koowa-form">
	<input type="hidden" name="access" value="0" />
	<input type="hidden" name="published" value="0" />
	<input type="hidden" name="name" value="<?= $module->name ?>" />
	<input type="hidden" name="application" value="<?= $module->application ?>" />
	
	<div class="main">
		<div class="title">
			<input class="required" type="text" name="title" value="<?= @escape($module->title) ?>" />
		</div>

		<div class="scrollable">
		    <fieldset class="form-horizontal">
		    	<legend><?= @text( 'Details' ); ?></legend>
				<div class="control-group">
				    <label class="control-label"><?= @text('Type') ?></label>
				    <div class="controls">
				        <?= @text(ucfirst($module->identifier->package)).' &raquo; '. @text(ucfirst($module->identifier->path[1])); ?>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label"><?= @text('Description') ?></label>
				    <div class="controls">
				        <?= @text($module->description) ?>
				    </div>
				</div>
			</fieldset>

            <? if($params_rendered = $params->render('params')) : ?>
            <fieldset class="form-horizontal">
				<legend><?= @text( 'Default Parameters' ); ?></legend>
                <?= $params_rendered; ?>
			</fieldset>
            <? endif ?>

            <? if($params_rendered = $params->render('params', 'advanced')) : ?>
			<fieldset class="form-horizontal">
				<legend><?= @text( 'Advanced Parameters' ); ?></legend>
                <?= $params_rendered; ?>
			</fieldset>
			<? endif ?>

            <? if($params_rendered = $params->render('params', 'other')) : ?>
			<fieldset class="form-horizontal">
				<legend><?= @text( 'Other Parameters' ); ?></legend>
                <?= $params_rendered; ?>
			</fieldset>
			<? endif ?>

			<? if($module->name == 'mod_custom') : ?>
			<fieldset>
				<legend><?= @text('Custom Output') ?></legend>
				<?= @service('com:wysiwyg.controller.editor')->render(array('name' => 'content', 'text' => $module->content)) ?>
			</fieldset>
			<? endif ?>
		</div>
	</div>

	<div class="sidebar">
		<div class="scrollable">
			<fieldset class="form-horizontal">
				<legend><?= @text('Publish') ?></legend>
				<div class="control-group">
				    <label class="control-label" for="published"><?= @text('Published') ?></label>
				    <div class="controls">
				        <input type="checkbox" name="published" value="1" <?= $module->published ? 'checked="checked"' : '' ?> />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for=""><?= @text('Position') ?></label>
				    <div class="controls">
                        <?= @helper('listbox.positions', array('name' => 'position', 'selected' => $module->position, 'application' => $state->application, 'deselect' => false)) ?>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="access"><?= @text('Registered') ?></label>
				    <div class="controls">
				        <input type="checkbox" name="access" value="1" <?= $module->access ? 'checked="checked"' : '' ?> />
				    </div>
				</div>
			</fieldset>
		</div>
	</div>
</form>