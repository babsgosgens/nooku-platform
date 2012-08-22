<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('listbox.radiolist', array(
	'list'      => array((object) array('title' => 'Uncategorized', 'id' => 0)),
	'name'      => 'categories_category_id',
    'text'      => 'title',
	'selected'  => $article->categories_category_id,
    'translate' => true));
?>

<? foreach($categories as $category) : ?>
	<span class="section"><?= @escape($category->title); ?></span><br />
	<? if($category->hasChildren()) : ?>
		<?= @helper('listbox.radiolist', array(
				'list'     => $category->getChildren(),
				'selected' => $article->categories_category_id,
				'name'     => 'categories_category_id',
		        'text'     => 'title',
			));
		?>
	<? endif; ?>
<? endforeach ?>