<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Listbox Template Helper
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 */
class ComContactsTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
    public function contacts($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'model' 	=> 'contacts',
            'value'		=> 'id',
            'text'		=> 'name'
        ));

        return parent::_render($config);
    }
}