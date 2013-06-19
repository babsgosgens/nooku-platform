<?
/**
 * @category  Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?=@helper('behavior.mootools');?>
<?=@helper('behavior.validator');?>

<script src="media://js/koowa.js"/>
<script src="media://users/js/users.js" />

<script type="text/javascript">
    window.addEvent('domready', function () {
        ComUsers.Form.addValidators(['passwordLength','passwordVerify']);
    });
</script>

<form action="" method="post" autocomplete="off" class="-koowa-form form-horizontal">
    <div class="control-group">
        <label class="control-label" for="name"><?= @text('Your Name') ?></label>
        <div class="controls">
            <input class="inputbox required" type="text" id="name" name="name" value="<?= @escape($user->name) ?>" size="100" />
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="email"><?= @text('Email') ?></label>
        <div class="controls">
            <input class="inputbox required validate-email" type="email" id="email" name="email" value="<?= @escape($user->email) ?>" size="100" />
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Password') ?></label>
        <div class="controls">
            <input class="inputbox <?=!$user->isNew()?:'required'?> passwordLength:<?=$parameters->get('password_length', 6);?>" type="password" id="password" name="password" value="" size="40" />
            <?=@helper('com:users.form.password');?>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="password_verify"><?= @text('Verify Password') ?></label>
        <div class="controls">
            <input class="inputbox <?=!$user->isNew()?:'required'?> passwordVerify matchInput:'password' matchName:'password'" type="password" id="password_verify" size="40" />
        </div>
    </div>

    <? if(!$user->isNew()): ?>
    <div class="control-group">
        <label class="control-label"><?=@text('Timezone');?></label>
        <div class="controls">
            <?= @helper('com:extensions.listbox.timezones',
            array('name' => 'params[timezone]', 'selected' => $user->params->get('timezone'), 'deselect' => true));?>
        </div>
    </div>
    <? endif;?>

    <input type="hidden" name="action" value="save" />

    <div class="form-actions">
        <button class="btn btn-primary validate" type="submit"><?= @text('Save') ?></button>
    </div>
</form>