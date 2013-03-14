<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Comments;

use Nooku\Framework;

/**
 * Dissusible Controller Behavior
 *
 * @author  Steven Rombauts <https://nooku.assembla.com/profile/stevenrombauts>
 * @package Nooku\Component\Comments
 */
class DatabaseBehaviorDiscussible extends Framework\DatabaseBehaviorAbstract
{
	/**
	 * Get a list of comments
	 *
	 * @return DatabaseRowsetComments
	 */
	public function getComments()
	{
		$comments = $this->getService('com://admin/comments.model.comments')
					->row($this->id)
					->table($this->getTable()->getName())
					->getRowset();

		return $comments;
	}
}