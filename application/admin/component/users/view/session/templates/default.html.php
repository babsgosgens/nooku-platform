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

<h1><?= @text('Administration Login') ?></h1>

<!--
<script src="media://js/koowa.js" />
<style src="media://css/koowa.css" />
-->

<form action="" method="post" name="login" id="form-login">
    <div class="control-group">
        <label class="control-label" for=""><?= @text( 'Email' ); ?></label>
        <div class="controls">
        <input name="email" id="email" type="email" class="inputbox" autofocus="autofocus" placeholder="<?= @text('Email') ?>" />
        </div>
    </div>
   <div class="control-group">
       <label class="control-label" for=""><?= @text( 'Password' ); ?></label>
       <div class="controls">
            <input name="password" type="password" id="password" class="inputbox" placeholder="<?= @text('Password') ?>" />
       </div>
   </div>
   <input type="submit" class="btn btn-large btn-block" value="<?= @text('Login') ?>" />
</form>