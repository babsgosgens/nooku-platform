<?
/**
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<ul class="navigation">
	<li>
        <a class="<?= $state->identifier == null ? 'active' : ''; ?>" href="<?= @route('identifier=' ) ?>">
            <?= 'All Components' ?>
        </a>
	</li>
	<? foreach ($facets as $facet) : ?>
	<li>
        <a class="<?= $state->identifier == $facet->title ? 'active' : ''; ?>" href="<?= @route('identifier='.$facet->title ) ?>">
            <?= @escape(ucfirst($facet->title)) ?>
        </a>
	</li>
	<? endforeach ?>
</ul>