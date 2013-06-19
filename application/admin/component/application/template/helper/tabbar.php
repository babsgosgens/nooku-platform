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
 * Template Tabbar Helper
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ApplicationTemplateHelperTabbar extends Library\TemplateHelperAbstract
{
 	/**
     * Render the menubar
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function render($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
        	'tabbar'  => null,
            'attribs' => array(),
        ));

        $html = '';
        if(isset($config->tabbar))
        {
            $commands = $config->tabbar->getCommands();

            if(count($commands)) {
                $html = '<div id="panel-tabbar">';
                foreach ($commands as $command)
                {
                    $name = $command->getName();

                    if(method_exists($this, $name)) {
                        $html .= $this->$name(array('command' => $command));
                    } else {
                        $html .= $this->command(array('command' => $command));
                    }
                }
                $html .= '</div>';
            }
        }

		return $html;
    }

    /**
     * Render a menubar command
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function command($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
        	'command' => null
        ));

        $command = $config->command;

        //Add a nolink class if the command is disabled
        if($command->disabled) {
            $command->attribs->class->append(array('nolink'));
        }

        if($command->active) {
             $command->attribs->class->append(array('active'));
        }

        //Create the href
        if(!empty($command->href) && !$command->disabled) {
            $command->attribs['href'] = $this->getTemplate()->getView()->getRoute($command->href);
        }

        if ($command->disabled) {
			$html = '<span '.$this->_buildAttributes($command->attribs).'>'.JText::_($command->label).'</span>';
		} else {
			$html = '<a '.$this->_buildAttributes($command->attribs).'>'.JText::_($command->label).'</a>';
		}

    	return $html;
    }
}