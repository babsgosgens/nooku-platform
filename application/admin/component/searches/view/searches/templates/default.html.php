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
<?= helper('behavior.sortable') ?>

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<ktml:module position="sidebar">
    <?= import('default_sidebar.html'); ?>
</ktml:module>

<form action="" method="get" class="-koowa-grid">
    <?= import('default_scopebar.html'); ?>
    <table>
        <thead>
        <tr>
            <th width="1">
                <?= helper('grid.checkall') ?>
            </th>
            <th width="1"></th>
            <th>
                <?= helper('grid.sort', array('column' => 'title')) ?>
            </th>
            <th>
                <?= helper('grid.sort', array('title'=>'Type','column' => 'identifier_package')) ?>
            </th>
            <th width="1">
                <?= helper('grid.sort', array('title' => 'Last modified', 'column' => 'last_activity_on')) ?>
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
                <td align="center">

                </td>
                <td></td>
                <td class="ellipsis">
                    <a href="<?= route('option=com_'.$search->identifier_package.'&view='.$search->identifier_name.$search->identifier_query);?>">
                        <?= escape($search->title); ?>
                    </a>
                </td>
                <td>
                    <?= escape($search->identifier_package);?>
                </td>
                <td>
                    <?= escape($search->modified_on); ?>
                </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
</form>