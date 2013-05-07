<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Component Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ApplicationDatabaseRowComponent extends Library\DatabaseRowAbstract
{
    public function isTranslatable()
    {
        $result = false;
        $tables = $this->getObject('com:languages.model.tables')
            ->reset()
            ->enabled(true)
            ->getRowset();
        
        if(count($tables->find(array('extensions_component_id' => $this->id)))) {
            $result = true;
        }
        
        return $result;
    }
    
    public function __get($name)
    {
        if($name == 'params' && !($this->_data['params']) instanceof JParameter)
        {
            $path = Library\ClassLoader::getInstance()->getApplication('admin');
            $file = $path.'/component/'.$this->option.'/resources/config/settings.xml';

            $this->_data['params'] = new JParameter( $this->_data['params'], $file, 'component' );
        }
        
        return parent::__get($name);
    }
}