<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Files;

use Nooku\Framework;

/**
 * Containers Database Table
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class DatabaseTableContainers extends Framework\DatabaseTableDefault
{
	protected function _initialize(Framework\Config $config)
	{
		$config->append(array(
			'filters' => array(
				'slug' 	     => 'cmd',
				'path'       => 'com:files.filter.path',
				'parameters' => 'json'
			),
			'behaviors' => array(
			    'sluggable' => array('columns' => array('id', 'title'))
			)
		));

		parent::_initialize($config);
	}
}