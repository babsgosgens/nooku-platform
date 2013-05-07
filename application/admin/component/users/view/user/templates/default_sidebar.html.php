<?
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<fieldset>
    <legend><?= @text('System Information') ?></legend>
    <div>
        <label for="enabled"><?= @text('Enable User') ?></label>
        <div>
            <input <?= @object('user')->getId() == $user->id ? 'disabled="disabled"' : ''?> type="checkbox" id="enabled" name="enabled" value="1" <?= $user->enabled ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div>
        <label for="send_email"><?= @text('Receive System E-mails') ?></label>
        <div>
            <input type="checkbox" id="send_email" name="send_email" value="1" <?= $user->send_email ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <? if (!$user->isNew()): ?>
        <div>
            <label><?= @text('Register Date') ?></label>
            <div>
                <?= @helper('date.format', array('date' => $user->created_on, 'format' => 'Y-m-d H:i:s')) ?>
            </div>
        </div>
        <div>
            <label><?= @text('Last signed in') ?></label>
            <div>
                <? if($user->last_visited_on == '0000-00-00 00:00:00') : ?>
                    <?= @text('Never') ?>
                <? else : ?>
                    <?= @helper('date.format', array('date' => $user->last_visited_on, 'format' => 'Y-m-d H:i:s')) ?>
                <? endif ?>
            </div>
        </div>
    <? endif; ?>
</fieldset>
<fieldset>
    <legend><?= @text('Role') ?></legend>
    <div>
        <div>
            <?= @helper('listbox.radiolist', array(
                'list'     => @object('com:users.model.roles')->sort('id')->getRowset(),
                'selected' => $user->role_id,
                'name'     => 'role_id',
                'text'     => 'name',
            ));
            ?>
        </div>
    </div>
</fieldset>