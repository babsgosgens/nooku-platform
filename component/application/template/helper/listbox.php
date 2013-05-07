<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Listbox Template Helper
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Application
 */
class TemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function applications($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'     => 'application',
            'deselect' => true,
            'prompt'   => '- Select -',
        ));
        
        $options = array();
        if($config->deselect) {
            $options[] = $this->option(array('text' => \JText::_($config->prompt)));
        }
        
        foreach(Library\ClassLoader::getInstance()->getApplications() as $application => $path) {
            $options[] = $this->option(array('text' => $application, 'value' => $application));
        }
        
        $config->options = $options;
        
        return $this->optionlist($config);
    }
}