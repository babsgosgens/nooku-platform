<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Component Loader
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Articles
 */

/*if (!JFactory::getUser()->authorize( 'com_articles', 'manage' )) {
	JFactory::getApplication()->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}*/

KLoader::loadIdentifier('com://admin/articles.aliases');

echo KService::get('com://admin/articles.dispatcher')->dispatch();