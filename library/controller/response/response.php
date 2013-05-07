<?php
/**
 * @package		Koowa_Controller
 * @subpackage  Response
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Controller Response Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Response
 */
class ControllerResponse extends HttpResponse implements ControllerResponseInterface
{
    /**
     * Sets a redirect
     *
     * @see http://tools.ietf.org/html/rfc2616#section-10.3
     *
     * @param  string   $location   The redirect location
     * @param  string   $code       The redirect status code
     * @throws \InvalidArgumentException If the location is empty
     * @throws \UnexpectedValueException If the location is not a string, or cannot be cast to a string
     * @return ControllerResponse
     */
    public function setRedirect($location, $code = self::SEE_OTHER)
    {
        if (empty($location)) {
            throw new \InvalidArgumentException('Cannot redirect to an empty URL.');
        }

        if (!is_string($location) && !is_numeric($location) && !is_callable(array($location, '__toString')))
        {
            throw new \UnexpectedValueException(
                'The Response location must be a string or object implementing __toString(), "'.gettype($location).'" given.'
            );
        }

        $this->setStatus($code);
        $this->_headers->set('Location', (string) $location);

        return $this;
    }

    /**
     * Implement a virtual 'headers' class property to return their respective objects.
     *
     * @param   string $name  The property name.
     * @return  string $value The property value.
     */
    public function __get($name)
    {
        if($name == 'headers') {
            return $this->getHeaders();
        }

        return parent::__get($name);
    }
}