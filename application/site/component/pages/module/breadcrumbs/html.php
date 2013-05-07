<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Breadcrumbs Module Html View Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
 
class PagesModuleBreadcrumbsHtml extends PagesModuleDefaultHtml
{
    public function render()
    {
        $list   = (array) $this->getObject('application')->getPathway()->items;
        $params = $this->module->params;

        if($params->get('homeText'))
        {
            $item = new \stdClass();
            $item->name = $params->get('homeText', JText::_('Home'));

            $home = $this->getObject('application.pages')->getHome();
            $item->link = $this->getRoute($home->getLink()->getQuery().'&Itemid='.$home->id);

            array_unshift($list, $item);
        }

        $this->list = $list;

        return parent::render();
    }
} 