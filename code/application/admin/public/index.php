<?php
/**
 * @package     Nooku_Server
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_APPLICATION'  , JPATH_ROOT.'/application/admin');
define('JPATH_BASE'         , JPATH_APPLICATION );

define('JPATH_VENDOR'       , JPATH_ROOT.'/vendor' );
define('JPATH_SITES'        , JPATH_ROOT.'/site');

define( 'DS', DIRECTORY_SEPARATOR );

require_once(__DIR__.'/bootstrap.php' );

KService::get('loader')->loadIdentifier('com://admin/application.aliases');
KService::get('application')->run();