<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Versions;

use Nooku\Library;

/**
 * Revisable Controller Toolbar
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Versions
 */
class ControllerToolbarRevisable extends Library\ControllerToolbarAbstract
{
    protected function _initialize(Library\Config $config)
    {
        $config->append(array(
    		'priority'  => Library\CommandChain::PRIORITY_LOW
        ));

        parent::_initialize($config);
    }
    
    public function onAfterControllerBrowse(Library\Event $event)
    {     
        $state = $this->getController()->getModel()->getState();
        $name  = $this->getController()->getIdentifier()->name;
        
        if($state->trashed == true) 
        {    
            $toolbar = $this->getController()->getToolbar($name);
            
            $toolbar->reset();
                 
            if($this->getController()->canEdit()) {
                $toolbar->addCommand($this->getCommand('restore'));
            }
            
            if($this->getController()->canDelete()) {
                $toolbar->addCommand($this->getCommand('delete'));
            }
        } 
    }
    
    protected function _commandRestore(Library\ControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs'  => array(
                'data-action' => 'edit',
            )
        )); 
    }
    
    protected function _commandDelete(Library\ControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs'  => array(
                'label'       => 'Delete forever',
                'data-action' => 'delete'
            )
        )); 
    }
}