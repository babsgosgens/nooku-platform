<?php
/**
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * Contacts Html View
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Contacts
 */
class ContactsViewContactsHtml extends Library\ViewHtml
{
    /**
     * Display the view
     *
     * @return	string	The output of the view
     */
    public function render()
    {
        //Get the parameters
        $params = $this->getObject('application')->getParams();

        //Get the category
        $category = $this->getCategory();

        //Get the parameters of the active menu item
        if ($page = $this->getObject('application.pages')->getActive())
        {
            $menu_params = new JParameter( $page->params );
            if (!$menu_params->get( 'page_title')) {
                $params->set('page_title',	$category->title);
            }
        }
        else $params->set('page_title',	$category->title);

        //Set the page title
        //JFactory::getDocument()->setTitle( $params->get( 'page_title' ) );

        //Set the pathway
        if($page->getLink()->query['view'] == 'categories' ) {
            $this->getObject('application')->getPathway()->addItem($category->title, '');
        }

        //Set the breadcrumbs
        $this->params   = $params;
        $this->category = $category;
        
        return parent::render();
    }

    public function getCategory()
    {
        //Get the category
        $category = $this->getObject('com:contacts.model.categories')
                         ->table('contacts')
                         ->id($this->getModel()->getState()->category)
                         ->getRow();

        return $category;
    }
}