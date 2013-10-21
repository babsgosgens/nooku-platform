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

/**
 * Bootstrapper
 *
 * @author  Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package Nooku\Component\Files
 */
class Bootstrapper extends Library\BootstrapperAbstract
{
    public function bootstrap()
    {

        $this->getClassLoader()
            ->getLocator('psr')
            ->registerNamespace('Solarium', JPATH_VENDOR.'/solarium/solarium/library');
        $this->getClassLoader()
            ->getLocator('psr')
            ->registerNamespace('Symfony', JPATH_VENDOR.'/symfony/event-dispatcher');

    }
}