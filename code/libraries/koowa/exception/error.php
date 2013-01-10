<?php
/**
 * @version		$Id$
 * @package		Koowa_Exception
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Error Exception Class
 *
 * KException is the base class for all koowa related exceptions and provides an additional method for printing up a
 * detailed view of an exception.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Exception
 */
class KExceptionError extends \ErrorException implements KExceptionInterface
{
    /**
     * Format the exception for display
     *
     * @return string
     */
    public function __toString()
    {
         return "Exception '".get_class($this) ."' with message '".$this->getMessage()."' in ".$this->getFile().":".$this->getLine();
    }
}