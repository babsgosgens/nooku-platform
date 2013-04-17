<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Attachments;

use Nooku\Library;

/**
 * Attachable Database Behavior
 *
 * @author  Steven Rombauts <https://nooku.assembla.com/profile/stevenrombauts>
 * @package Nooku\Component\Attachments
 */
class DatabaseBehaviorAttachable extends Library\DatabaseBehaviorAbstract
{
    /**
     * Get a list of attachments
     *
     * @return RowsetAttachments
     */
    public function getAttachments()
	{
        $model = $this->getService('com:attachments.model.attachments');

        if(!$this->isNew())
        {
            $attachements = $model->row($this->id)
                ->table($this->getTable()->getBase())
                ->getRowset();
        }
        else $attachements = $model->getRowset();

        return $attachements;
	}
}