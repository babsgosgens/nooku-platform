<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesViewStatesHtml extends ComProfilesViewHtml
{
	public function display()
	{
		$this->assign('region', KRequest::get( 'get.region', 'string' ));
		parent::display();
	}

}
