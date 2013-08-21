<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Searches;

use Nooku\Library;

/**
 * Facets Database Rowset
 *
 * @author  Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package Nooku\Component\Searches
 */
class DatabaseRowsetFacets extends Library\DatabaseRowsetAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'identity_column'   => 'title'
        ));

        parent::_initialize($config);
    }
}
