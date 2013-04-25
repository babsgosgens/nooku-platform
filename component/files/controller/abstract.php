<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Node Controller Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */

abstract class ControllerAbstract extends \ApplicationControllerDefault
{
	protected function _initialize(Library\Config $config)
	{
		$config->append(array(
			'persistable'   => false,
			'limit'         => array('max' => 1000),
			'request' => $this->getService('lib:controller.request', array(
				'query' => array('container' => 'files-files')
			))
		));

		parent::_initialize($config);
	}

	public function getRequest()
	{
		$request = parent::getRequest();

		// "config" state is only used in HMVC requests and passed to the JS application
		if ($this->isDispatched()) {
			unset($request->query->config);
		}

		return $request;
	}

	protected function _actionCopy(Library\CommandContext $context)
	{
		$entity = $this->getModel()->getRow();

		if(!$entity->isNew())
		{
			$entity->setData(Library\Config::unbox($context->request->data->toArray()));

			//Only throw an error if the action explicitly failed.
			if($entity->copy() === false)
			{
				$error = $entity->getStatusMessage();
                throw new Library\ControllerExceptionActionFailed($error ? $error : 'Copy Action Failed');
			}
			else
            {
                $context->response->setStatus(
                    $entity->getStatus() === Library\Database::STATUS_CREATED ? self::STATUS_CREATED : self::STATUS_UNCHANGED
                );
            }
		}
		else throw new Library\ControllerExceptionNotFound('Resource Not Found');

		return $entity;
	}

	protected function _actionMove(Library\CommandContext $context)
	{
		$entity = $this->getModel()->getRow();

		if(!$entity->isNew())
		{
			$entity->setData(Library\Config::unbox($context->request->data->toArray()));

			//Only throw an error if the action explicitly failed.
			if($entity->move() === false)
			{
				$error = $entity->getStatusMessage();
                throw new Library\ControllerExceptionActionFailed($error ? $error : 'Move Action Failed');
			}
			else
            {
                $context->response->setStatus(
                    $entity->getStatus() === Library\Database::STATUS_CREATED ? self::STATUS_CREATED : self::STATUS_UNCHANGED
                );
            }
		}
		else throw new Library\ControllerExceptionNotFound('Resource Not Found');

		return $entity;
	}

	/**
	 * Overridden method to be able to use it with both resource and service controllers
	 */
	protected function _actionRender(Library\CommandContext $context)
	{
		if ($this->getIdentifier()->name == 'image' || ($this->getIdentifier()->name == 'file' && $context->request->getFormat() == 'html'))
		{
            \JFactory::getLanguage()->load($this->getIdentifier()->package);

			$view = $this->getView();

	        //Push the params in the view
	        foreach($context->param as $name => $value) {
	            $view->set($name, $value);
	        }
	
	        //Render the view
	        $content = $view->render();

	        //Set the data in the response
	        $context->response
	                ->setContent($content)
	                ->setContentType($view->mimetype);
	
		    return $content;
		}

		return parent::_actionRender($context);
	}
	
	/**
	 * Copied to allow 0 as a limit
	 * 
	 * @param Library\CommandContext $context
	 */
	protected function _actionBrowse(Library\CommandContext $context)
	{
	    if($this->isDispatched())
	    {
	        $limit = $this->getModel()->get('limit');

	        //If limit is empty use default
	        if(empty($limit) && $limit !== 0) {
	            $limit = $this->_limit->default;
	        }
	
	        //Force the maximum limit
	        if($limit > $this->_limit->max) {
	            $limit = $this->_limit->max;
	        }
	
	        $this->limit = $limit;
	    }
	
	    $entity = $this->getModel()->getRowset();
		return $entity;
	}
}
