<?php
/**
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Category Controller Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Categories
 */
abstract class ComCategoriesControllerCategory extends ComDefaultControllerModel
{ 
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'model' => 'com://admin/categories.model.categories'
        ));

        parent::_initialize($config);
    }
    
    protected function _actionRender(KCommandContext $context)
    {
        $view = $this->getView();

        //Set the layout
        if($view instanceof KViewTemplate)
        {
            $layout = clone $view->getIdentifier();
            $layout->name  = $view->getLayout();

            $alias = clone $layout;
            $alias->package = 'categories';

            $this->getService()->setAlias($layout, $alias);
        }

        return parent::_actionRender($context);
    }
    
    public function getRequest()
	{
		$request = parent::getRequest();

        $request->query->table     = $this->getIdentifier()->package;
        $request->query->access    = $this->getUser()->isAuthentic();
        $request->query->published = 1;

	    return $request;
	}
}