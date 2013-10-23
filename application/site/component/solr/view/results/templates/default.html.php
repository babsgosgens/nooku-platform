<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<script src="assets://application/js/jquery.js" />
<script src="assets://solr/js/autocomplete.js"/>

<script src="assets://solr/js/jquery.autocomplete.js"/>
<style src="assets://solr/css/jquery.autocomplete.css"/>

<form action="" method="get" class="form-search well">
    <div class="input-append">
        <input name="search" class="input-xxlarge js-search" type="text" id="autocomplete"
               value="<?= escape($state->search) ?>" placeholder="<?= translate('Search ...') ?>"/>
        <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i></button>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>
                <?= translate('Title') ?>
            </th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td>
                <?= helper('paginator.pagination', array('total' => $total, 'show_limit' => false, 'show_count' => false)); ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
        <? foreach($results as $result) : ?>
        <tr>
            <td>
                <a href="<?= helper('route.result', array('row' => $result)) ?>">
                    <?= escape($result->title); ?>
                </a>
            </td>
        </tr>
        <? endforeach ?>
    </tbody>
</table>