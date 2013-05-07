<?
/**
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<h3><?= @text( 'Positions' ); ?></h3>
<nav class="scrollable">
	<a <? if(!$state->position && $state->application == 'site') echo 'class="active"' ?> href="<?= @route('position=&application=site') ?>">
	    <?= @text('All positions') ?>
	</a>
	<? foreach(array_unique(@object('com:pages.model.modules')->application('site')->getRowset()->position) as $position) : ?>
	<a <? if($state->position == $position && $state->application == 'site') echo 'class="active"' ?> href="<?= @route('sort=ordering&position='.$position.'&application=site') ?>">
	    <?= $position; ?>
	</a>
	<? endforeach ?>
</nav>