<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

return array(

    'priority' => Library\ObjectBootstrapper::PRIORITY_HIGH,

    'aliases'  => array(
        'application'                    => 'com:application.dispatcher.http',
        'lib:database.adapter.mysql'     => 'com:application.database.adapter.mysql',
        'lib:template.locator.component' => 'com:application.template.locator.component',
        'lib:dispatcher.router.route'    => 'com:application.dispatcher.router.route',
    )
);
