<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<?=helper('behavior.mootools')?>
<?=helper('behavior.keepalive')?>
<?=helper('behavior.validator')?>

<title content="replace"><?= translate('Login') ?></title>

<form action="<?= helper('route.session'); ?>" method="post" class="-koowa-form">
    <div class="form-content">
        <div class="page-header">
            <h1><?= escape($parameters->get('page_title')) ?></h1>
        </div>

        <? if($parameters->get('description_login_text')) : ?>
        <p><?= escape(translate($parameters->get('description_login_text'))) ?></p>
        <? endif ?>

        <fieldset>
            <input id="email" class="required validate-email form-control" name="email" type="email" alt="email" placeholder="Email address" />
            <input id="password" class="required form-control" type="password" name="password" alt="password" placeholder="Password"/>
        </fieldset>
        <small><a href="<?= helper('route.user', array('layout' => 'reset')); ?>"><?= translate('FORGOT_YOUR_PASSWORD'); ?></a></small>
    </div>

    <div class="form-actions">
        <button type="submit" class="validate btn btn-primary"><?= translate('Sign in') ?></button>
        <? if($parameters->get('registration')) : ?>
        	<?= translate('or') ?>
        	<a href="<?= helper('route.user', array('layout' => 'register')); ?>"><?= translate('Sign up'); ?></a>
        <?php endif; ?>
    </div>
</form>