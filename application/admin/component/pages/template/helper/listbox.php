<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Listbox Template Helper Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class PagesTemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function menus($config = array())
    {
        $config = new Library\ObjectConfig($config);
		$config->append(array(
			'model'		=> 'menus',
			'name' 		=> 'pages_menu_id',
			'value'		=> 'id',
			'text'		=> 'title',
		));

		return $this->_listbox($config);
    }
    
    public function pages($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'deselect' => true,
            'prompt' => '- Select -',
            'disable' => array()
        ));

        $options = array();
        if($config->deselect) {
            $options[] = $this->option(array('text' => JText::_($config->prompt)));
        }

        $menus = $this->getObject('com:pages.model.menus')->getRowset();
        $pages = $this->getObject('com:pages.model.pages')->published(true)->getRowset();

        foreach($menus as $menu)
        {
            $options[] = $this->option(array('text' => $menu->title, 'value' => '', 'disable' => true));
            foreach($pages->find(array('pages_menu_id' => $menu->id)) as $page)
            {
                $options[] = $this->option(array(
                    'text' => str_repeat(str_repeat('&nbsp;', 4), $page->level).$page->title,
                    'value' => $page->id,
                    'disable' => in_array($page->type, Library\ObjectConfig::unbox($config->disable))
                ));
            }
        }

        $config->options = $options;

        return $this->optionlist($config);
    }

    public function parents($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name' => 'parent_id',
            'page' => null,
            'menu' => null
        ));

        $pages = $this->getObject('com:pages.model.pages')
            ->published(true)
            ->menu($config->menu)
            ->limit(0)
            ->getRowset();

        if($config->page)
        {
            $path = $config->page->path;
            foreach(clone $pages as $page) {
                if(strpos($page->path, $config->page->path) === 0) {
                    $pages->extract($page);
                }
            }
        }

        $html     = array();
        $selected = $config->selected == 0 ? 'checked="checked"' : '';

        $html[] = '<label class="radio" for="'.$config->name.'0">';
        $html[] = '<input type="radio" name="'.$config->name.'" id="'.$config->name.'0" value="0" '.$selected.' />';
        $html[] = JText::_('Top').'</label>';

        foreach($pages as $page)
        {
            $selected = $config->selected == $page->id ? 'checked="checked"' : '';

            $html[] = '<label class="radio level'.$page->level.'" for="'.$config->name.$page->id.'">';
            $html[] = '<input type="radio" name="'.$config->name.'" id="'.$config->name.$page->id.'" value="'.$page->id.'" '.$selected.' />';
            $html[] = $page->title.'</label>';
        }

        return implode(PHP_EOL, $html);
    }

    public function positions($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name' => 'position',
        ));

        $options = array();

        $path = Library\ClassLoader::getInstance()->getApplication('site');
        $path = $path.'/public/theme/'.$this->getObject('application')->getCfg('theme').'/config.xml';

        if (file_exists($path))
        {
            $xml = simplexml_load_file($path);
            if (isset($xml->positions))
            {
                foreach ($xml->positions->children() as $position) {
                    $options[] = $this->option(array('text' => (string) $position, 'value' =>  (string) $position));
                }
            }
        }

        $config->options = $options;

        return $this->optionlist($config);
    }
}
