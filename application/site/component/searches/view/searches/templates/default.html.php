<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<!--
<script src="media://js/koowa.js" />
<style src="media://css/koowa.css" />
-->

<form action="" method="get" class="-koowa-grid">
    <table>
        <thead>
            <tr>
                <th>
                    <?= helper('grid.sort', array('column' => 'title')) ?>
                </th>
                <th>
                    <?= helper('grid.sort', array('column' => 'identifier_package', 'title' => 'Type')) ?>
                </th>
                <th width="1">
                    <?= helper('grid.sort', array('column' => 'modified_on', 'title' => 'Last modified')) ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="7">
                    <?= helper('com:application.paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <? foreach($searches as $search) : ?>
            <tr data-readonly="0">
                <td class="ellipsis">
                    <a href="<?= helper('route.result', array('row' => $search)) ?>">
                        <?= escape($search->title); ?>
                    </a>
                </td>
                <td>
                    <?= escape($search->identifier_package); ?>
                </td>
                <td>
                    <?= helper('date.humanize', array('date' => $search->modified_on)) ?>
                </td>
            </tr>
            <? endforeach ?>
        </tbody>
    </table>
</form>