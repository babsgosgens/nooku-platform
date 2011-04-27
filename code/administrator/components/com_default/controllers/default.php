<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */


/**
 * Default Controller
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerDefault extends ComDefaultControllerForm
{
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        $this->registerCallback(array('after.save', 'after.delete'), array($this, 'setMessage'));
    }
       
    /**
     * Filter that creates a redirect message based on the action
     * 
     * This function takes the row(set) status into account. If the status is STATUS_FAILED the status message information 
     * us used to generate an appropriate redirect message and set the redirect to the referrer. Otherwise, we generate the 
     * message based on the action and identifier name.
     *
     * @param KCommandContext   The active command context
     * @return void
     */
    public function setMessage(KCommandContext $context)
    { 
        if($context->result instanceof KDatabaseRowsetInterface) {
            $row = $context->result->top();
        } else {
            $row = $context->result;
        }
        
        $action = KRequest::get('post.action', 'cmd');
        $name   = $this->_identifier->name;
        $status = $row->getStatus();

        if($status == KDatabase::STATUS_FAILED)
        {
            $this->_redirect        = KRequest::referrer();
            $this->_redirect_type   = 'error';
            
            if($row->getStatusMessage()) {
                $this->_redirect_message = $row->getStatusMessage();
            } else {
                $this->_redirect_message = JText::_(ucfirst(KInflector::singularize($name)) . ' ' . $action.' failed');
            }
        }
            
        if(!is_null($status) && ($status != KDatabase::STATUS_LOADED))
        {
           $suffix = ($action == 'add' || $action == 'edit') ? 'ed' : 'd';
           $this->_redirect_message = JText::_(ucfirst(KInflector::singularize($name)) . ' ' . $action.$suffix);
        }
    }
 
 	/**
     * Read action
     *
     * This functions implements an extra check to hide the main menu is the view name
     * is singular (item views)
     *
     *  @return KDatabaseRow    A row object containing the selected row
     */
    protected function _actionRead(KCommandContext $context)
    {
        //Perform the read action
        $row = parent::_actionRead($context);
        
        //Add the notice if the row is locked
        if(isset($row))
        {
            if(!isset($this->_request->layout) && $row->isLockable() && $row->locked()) {
                KFactory::get('lib.joomla.application')->enqueueMessage($row->lockMessage(), 'notice');
            }
        }

        return $row;
    }
    
    /**
     * Display action
     * 
     * This function will load the language files of the component if the controller was
     * not dispatched. 
     *
     * @param   KCommandContext A command context object
     * @return  KDatabaseRow(set)   A row(set) object containing the data to display
     */
    protected function _actionGet(KCommandContext $context)
    {
        //Load the language file for HMVC requests who are not routed through the dispatcher
        if(!$this->isDispatched()) {
            KFactory::get('lib.joomla.language')->load('com_'.$this->getIdentifier()->package); 
        }
        
        return parent::_actionGet($context);
    }
    
	/**
     * Set a request property
     * 
     *  This function translates 'limitstart' to 'offset' for compatibility with Joomla
     *
     * @param  	string 	The property name.
     * @param 	mixed 	The property value.
     */
 	public function __set($property, $value)
    {          
        if($property == 'limitstart') {
            $property = 'offset';
        } 
        	
        parent::__set($property, $value);     
  	}
}