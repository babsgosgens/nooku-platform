<?
/**
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<h3><?= @text('Components')?></h3>

<?= @template('com:searches.view.facets.list.html', array('facets' => @object('com:searches.model.facets')->field('identifier')->getRowset())); ?>