<?php
/**
 * @package		Koowa_Config
 * @subpackage  Format
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Config Format Xml
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Config
 */
class ConfigXml extends ConfigFormat
{
    /**
     * Read from a string and create an array
     *
     * @param  string $string
     * @return ConfigXml|false   Returns a Config object. False on failure.
     * @throws \RuntimeException
     */
    public static function fromString($string)
    {
        $data = array();

        if(!empty($string))
        {
            $xml  = simplexml_load_string($string);
            foreach ($xml->children() as $node) {
                $data[(string) $node['name']] = self::_decodeValue($node);
            }
        }

        $config = new static($data);

        return $config;
    }

    /**
     * Write a config object to a string.
     *
     * @param  Config $config
     * @return string|false   Returns a XML encoded string on success. False on failure.
     */
    public function toString()
    {
        $addChildren = function($value, $key, $node)
        {
            if (is_scalar($value))
            {
                $n = $node->addChild('option', $value);
                $n->addAttribute('name', $key);
                $n->addAttribute('type', gettype($value));
             }
             else
             {
                $n = $node->addChild('config');
                $n->addAttribute('name', $key);
                $n->addAttribute('type', gettype($value));

                 array_walk($value, $addChildren, $n);
            }
        };

        $xml  = simplexml_load_string('<config />');
        $data = $this->toArray();
        array_walk($data, $addChildren, $xml);

        return $xml->asXML();
    }

    /**
     * Method to get a PHP native value for a SimpleXMLElement object
     *
     * @param   object  $node  SimpleXMLElement object for which to get the native value.
     * @return  mixed  Native value of the SimpleXMLElement object.
     */
    protected static function _decodeValue($node)
    {
        switch ($node['type'])
        {
            case 'integer':
                $value = (string) $node;
                return (int) $value;
                break;

            case 'string':
                return (string) $node;
                break;

            case 'boolean':
                $value = (string) $node;
                return (bool) $value;
                break;

            case 'double':
                $value = (string) $node;
                return (float) $value;
                break;

            case 'array':
            default     :

                $value = array();
                foreach ($node->children() as $child) {
                    $value[(string) $child['name']] = self::_decodeValue($child);
                }

                break;
        }

        return $value;
    }
}