<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Languages;

use Nooku\Framework;

/**
 * Translation Database Row
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Languages
 */
class DatabaseRowTranslation extends Framework\DatabaseRowTable
{
    /**
     * Status = completed
     */
    const STATUS_COMPLETED = 1;

    /**
     * Status = missing
     */
    const STATUS_MISSING = 2;

    /**
     * Status = outdated
     */
    const STATUS_OUTDATED = 3;
}