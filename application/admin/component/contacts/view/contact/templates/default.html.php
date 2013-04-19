<?
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('behavior.validator') ?>

<!--
<script src="media://js/koowa.js" />
<style src="media://css/koowa.css" />
-->

<ktml:module position="toolbar">
    <?= @helper('toolbar.render', array('toolbar' => $toolbar))?>
</ktml:module>

<form action="" method="post" id="contact-form" class="-koowa-form">
	<input type="hidden" name="id" value="<?= $contact->id; ?>" />
	<input type="hidden" name="access" value="0" />
	<input type="hidden" name="published" value="0" />
	
	<div class="main">
		<div class="title">
		    <input class="required" type="text" name="name" maxlength="255" value="<?= $contact->name ?>" placeholder="<?= @text('Name') ?>" />
		    <div class="slug">
		        <span class="add-on"><?= @text('Slug'); ?></span>
		        <input type="text" name="slug" maxlength="255" value="<?= $contact->slug ?>" />
		    </div>
		</div>

		<div class="scrollable">
			<fieldset class="form-horizontal">
				<legend><?= @text('Information'); ?></legend>
				<div class="control-group">
				    <label class="control-label" for="position"><?= @text( 'Position' ); ?></label>
				    <div class="controls">
				        <input type="text" name="position" maxlength="255" value="<?= $contact->position; ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="email_to"><?= @text( 'E-mail' ); ?></label>
				    <div class="controls">
				        <input type="text" name="email_to" maxlength="255" value="<?= $contact->email_to; ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="address"><?= @text( 'Street Address' ); ?></label>
				    <div class="controls">
				        <textarea name="address" rows="5"><?= $contact->address; ?></textarea>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="suburb"><?= @text( 'Town/Suburb' ); ?></label>
				    <div class="controls">
				        <input type="text" name="suburb" maxlength="100" value="<?= $contact->suburb;?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="state"><?= @text( 'State/County' ); ?></label>
				    <div class="controls">
				        <input type="text" name="state" maxlength="100" value="<?= $contact->state;?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="postcode"><?= @text( 'Postal Code/ZIP' ); ?></label>
				    <div class="controls">
				        <input type="text" name="postcode" maxlength="100" value="<?= $contact->postcode; ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="country"><?= @text( 'Country' ); ?></label>
				    <div class="controls">
				        <input type="text" name="country" maxlength="100" value="<?= $contact->country;?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="telephone"><?= @text( 'Telephone' ); ?></label>
				    <div class="controls">
				        <input type="text" name="telephone" maxlength="255" value="<?= $contact->telephone; ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="mobile"><?= @text( 'Mobile' ); ?></label>
				    <div class="controls">
				        <input type="text" name="mobile" maxlength="255" value="<?= $contact->mobile; ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="fax"><?= @text( 'Fax' ); ?></label>
				    <div class="controls">
				        <input type="text" name="fax" maxlength="255" value="<?= $contact->fax; ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="webpage"><?= @text( 'Webpage' ); ?></label>
				    <div class="controls">
				        <input type="text" name="webpage" maxlength="255" value="<?= $contact->webpage; ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="misc"><?= @text( 'Miscellaneous Info' ); ?></label>
				    <div class="controls">
				        <textarea name="misc" rows="5"><?= $contact->misc; ?></textarea>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="image"><?= @text( 'Image' ); ?></label>
				    <div class="controls">
				        <?= @helper('image.listbox', array('name' => 'image', 'attribs' => array('class' => 'select-image', 'style' => 'width:220px'))); ?>
				    </div>
				</div>
			</fieldset>
		</div>
	</div>

	<div class="sidebar">
		<div class="scrollable">
            <?= @template('default_sidebar.html'); ?>
		</div>
	</div>
	
</form>

<script data-inline> $jQuery(".select-image").select2(); </script>