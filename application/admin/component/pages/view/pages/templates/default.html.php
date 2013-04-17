<?
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<!--
<script src="media://js/koowa.js" />
<style src="media://css/koowa.css" />
-->

<ktml:module position="toolbar">
    <?= @helper('toolbar.render', array('toolbar' => $toolbar))?>
</ktml:module>

<ktml:module position="sidebar">
    <?= @template('default_sidebar.html') ?>
</ktml:module>

<form id="pages-form" action="" method="get" class="-koowa-grid" >
    <?= @template('default_scopebar.html') ?>
    <table>
        <thead>
            <tr>
                <th width="1">
                    <?= @helper('grid.checkall'); ?>
                </th>
                <th width="1"></th>
                <th>
                    <?= @helper('grid.sort', array('column' => 'title')); ?>
                </th>
                <th width="1">
                    <?= @helper('grid.sort',  array('column' => 'custom' , 'title' => 'Ordering')); ?>
                </th>
                <th width="1">
                    <?= @helper('grid.sort',  array('column' => 'extensions_component_id' , 'title' => 'Type')); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="6">
                    <?= @helper('com:application.paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>

        <tbody>
        <? foreach($pages as $page) : ?>
            <tr class="sortable">
                <td align="center">
                    <?= @helper('grid.checkbox',array('row' => $page)); ?>
                </td>
                <td align="center">
                    <?= @helper('grid.enable', array('row' => $page, 'field' => 'published')) ?>
                </td>
                <td>
                    <?
                        $link = 'type[name]='.$page->type;
                        if($page->type == 'component')
                        {
                            $link .= '&type[option]='.$page->getLink()->query['option'].'&type[view]='.$page->getLink()->query['view'];
                            $link .= '&type[layout]='.(isset($page->getLink()->query['layout']) ? $page->getLink()->query['layout'] : 'default');
                        }

                        $link .= '&view=page&menu='.$state->menu.'&id='.$page->id;
                    ?>
                    <a href="<?= urldecode(@route($link)) ?>">
                        <? if($page->level > 1) : ?>
                            <?= str_repeat('.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $page->level - 1) ?><sup>|_</sup>&nbsp;
                        <? endif ?>
                        <?= @escape($page->title) ?>
                    </a>
                    <? if($page->home) : ?>
                        <i class="icon-star"></i>
                    <? endif ?>
                    <? if($page->access) : ?>
                        <span class="label label-important"><?= @text('Registered') ?></span>
                    <? endif; ?>
                    <? if($page->hidden) : ?>
                        <span class="label label-info"><?= @text('Hidden') ?></span>
                    <? endif; ?>
                </td>
                <td align="center">
                    <?= @helper('grid.order', array('row'=> $page, 'total' => $total)) ?>
                </td>
                <td>
                    <?= $page->getTypeDescription() ?>
                </td>
            </tr>
            <? endforeach; ?>
        </tbody>
    </table>
</form>
