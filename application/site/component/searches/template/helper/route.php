<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Route Template Helper
 *
 * @author  Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package Component\Searches
 */
class SearchesTemplateHelperRoute extends PagesTemplateHelperRoute
{
    public function result($config = array())
    {
        $config   = new Library\ObjectConfig($config);
        $result = $config->row;

        $url = "?option=com_".$result->identifier_package."&view=".$result->identifier_name.$result->identifier_query;
        return $url;
    }

}