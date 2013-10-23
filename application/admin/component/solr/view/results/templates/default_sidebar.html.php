<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<h3><?= translate('Components')?></h3>
<ul class="navigation">
    <li>
        <a class="<?= $state->package == null ? 'active' : ''; ?>" href="<?= route('package=' ) ?>">
            <?= 'All Components' ?>
        </a>
    </li>
    <? foreach($packages as $package) : ?>
        <li>
            <a class="<?= $state->package == $package->field ? 'active' : ''; ?>" href="<?= route('package='.$package->field ) ?>">
                <?= escape($package->field) ?><span class="navigation__badge"><?=$package->count?></span>
            </a>
        </li>
    <? endforeach; ?>
</ul>