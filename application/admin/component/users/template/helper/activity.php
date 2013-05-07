<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;
use Nooku\Component\Activities;

/**
 * Log Template Helper Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package    	Nooku_Server
 * @subpackage 	Users
 */

class UsersTemplateHelperActivity extends Activities\TemplateHelperActivity
{
    public function message($config = array())
	{
	    $config = new Library\ObjectConfig($config);
		$config->append(array(
			'row'      => ''
		));
		
		$row = $config->row;

        if($row->name == 'session')
        {
		    $item = $this->getTemplate()->getView()->getRoute('option='.$row->type.'_'.$row->package.'&view=user&id='.$row->created_by);
		    
		    $message   = '<a href="'.$item.'">'.$row->title.'</a>'; 
		    $message  .= ' <span class="action">'.$row->status.'</span>';
		}
		else $message = parent::message($config);
		
		return $message;
	}
}