<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Modules Database Rowset
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class ApplicationModelEntityModules extends Library\ModelEntityComposite implements Library\ObjectMultiton
{
    public function __construct(Library\ObjectConfig $config )
    {
        parent::__construct($config);

        //TODO : Inject raw data using $config->data
        $page = $this->getObject('application.pages')->getActive();

        $modules = $this->getObject('com:pages.model.modules')
            ->application('site')
            ->published(true)
            ->access((int) $this->getObject('user')->isAuthentic())
            ->page($page->id)
            ->fetch();

        $this->merge($modules);
    }
}