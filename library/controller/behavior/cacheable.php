<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Default Controller Cacheable Behavior
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ControllerBehaviorCacheable extends Library\ControllerBehaviorAbstract
{
	/**
	 * The cached state of the resource
	 * 
	 * @var boolean
	 */
	protected $_output = ''; 
	
	/**
	 * Fetch the unrendered view data from the cache
	 *
	 * @param   Library\CommandContext	$context A command context object
	 * @return 	void	
	 */
	protected function _beforeControllerRender(Library\CommandContext $context)
	{ 
	    $view   = $this->getView();
	    $cache  = JFactory::getCache($this->_getGroup(), 'output');
        $key    = $this->_getKey();
            
        if($data = $cache->get($key))
        {
            $data = unserialize($data);
            
            //Render the view output
            if($view instanceof Library\ViewTemplate)
            {
                $context->result = $view->getTemplate()
                               ->loadString($data['component'], array(), false)
                               ->render();
            } 
            else $context->result = $data['component'];
            
            $this->_output = $context->result;
	    }
	}
	
	/**
	 * Store the unrendered view data in the cache
	 *
	 * @param   Library\CommandContext	$context A command context object
	 * @return 	void
	 */
	protected function _afterControllerRender(Library\CommandContext $context)
	{
	    if(empty($this->_output))
	    {
	        $view   = $this->getView();
	        $cache  = JFactory::getCache($this->_getGroup(), 'output');
	        $key    = $this->_getKey();
	  
	        $data  = array();
	   
	        //Store the un rendered view output
	        if($view instanceof Library\ViewTemplate) {
	            $data['component'] = (string) $view->getTemplate();
	        } else {
	            $data['component'] = $context->result;
	        }
	        
	        $cache->store(serialize($data), $key);
	    }
	}
	
	/**
	 * Return the cached data after read
	 * 
	 * Only if cached data was found return it but allow the chain to continue to allow
	 * processing all the read commands
	 *
	 * @param   Library\CommandContext	A command context object
	 * @return 	void
	 */
	protected function _afterControllerRead(Library\CommandContext $context)
	{ 
	    if(!empty($this->_output)) {
	        $context->result = $this->_output;
	    }
	}
	
	/**
	 * Return the cached data before browse
	 * 
	 * Only if cached data was fetch return it and break the chain to dissallow any
	 * further processing to take place
	 * 
	 * @param   Library\CommandContext	A command context object
	 * @return 	void
	 */
    protected function _beforeControllerBrowse(Library\CommandContext $context)
	{
	    if(!empty($this->_output)) 
	    {
	        $context->result = $this->_output;
	        return false;
	    }
	}
	
	/**
	 * Clean the cache
	 *
	 * @param   Library\CommandContext	A command context object
	 * @return 	boolean
	 */
	protected function _afterControllerAdd(Library\CommandContext $context)
	{
	    $status = $context->result->getStatus();
	    
	    if($status == Library\Database::STATUS_CREATED) {
	         JFactory::getCache()->clean($this->_getGroup());
	    }
	      
	    return true;
	}
	
	/**
	 * Clean the cache
	 *
	 * @param   Library\CommandContext	A command context object
	 * @return 	boolean
	 */
	protected function _afterControllerDelete(Library\CommandContext $context)
	{
	    $status = $context->result->getStatus();
	    
	    if($status == Library\Database::STATUS_DELETED) {
	        JFactory::getCache()->clean($this->_getGroup());
	    }
	      
	    return true;
	}
	
	/**
	 * Clean the cache
	 *
	 * @param   Library\CommandContext	A command context object
	 * @return 	boolean
	 */
	protected function _afterControllerEdit(Library\CommandContext $context)
	{
	    $status = $context->result->getStatus();
	    
	    if($status == Library\Database::STATUS_UPDATED) {
	        JFactory::getCache()->clean($this->_getGroup());
	    }
	      
	    return true;
	}
	
	/**
	 * Generate a cache key
	 * 
	 * The key is based on the layout, format and model state
	 *
	 * @return 	string 
	 */
	protected function _getKey()
	{
	    $view  = $this->getView();
	    $state = $this->getModel()->getState()->toArray();
	    
	    $key = $view->getLayout().'-'.$view->getFormat().':'.md5(http_build_query($state));
	    return $key;
	}
	
	/**
	 * Generate a cache group
	 * 
	 * The group is based on the component identifier
	 *
	 * @return 	string 
	 */
	protected function _getGroup()
	{ 
	    $group = $this->_mixer->getIdentifier();
	    return $group;
	}
}