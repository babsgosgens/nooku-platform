<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Searches;

use Nooku\Library;
use Nooku\Library\ObjectConfig;

/**
 * Abstract Local Adapter
 *
 * @author   Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package Nooku\Component\Searches
 */
interface AdapterEngineInterface
{
    public function isConnected();
    public function getRowset(Library\ModelState $state);
    public function getRow(Library\ModelState $state);
    public function save(Library\CommandContext $context);
    public function delete(Library\CommandContext $context);
}