<?php
/**
* @package      Koowa_Config
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Library;

/**
 * ObjectConfig Factory
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Config
 */
class ObjectConfigFactory extends ObjectFactoryAbstract implements ObjectSingleton
{
    /**
     * Registered config file formats.
     *
     * @var array
     */
    protected $_formats;

    /**
     * Constructor
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_formats = $config->formats;
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'formats' => array(
                'ini'  => 'Nooku\Library\Object\ObjectConfigIni',
                'json' => 'Nooku\Library\Object\ObjectConfigJson',
                'xml'  => 'Nooku\Library\Object\ObjectConfigXml',
                'yaml' => 'Nooku\Library\Object\ObjectConfigYaml'
            )
        ));

        parent::_initialize($config);
    }

    /**
     * Get a registered config object.
     *
     * @param  string $format The format name
     * @param  array  $config A optional array of configuration options
     * @throws \InvalidArgumentException    If the format isn't registered
     * @throws \UnexpectedValueException	If the format object doesn't implement the ObjectConfigSerializable
     * @return ObjectConfig
     */
    public function getInstance($format, $config = array())
    {
        $format = strtolower($format);

        if (!isset($this->_formats[$format])) {
            throw new \RuntimeException(sprintf('Unsupported config format: %s ', $format));
        }

        $format = $this->_formats[$format];

        if(!($format instanceof ObjectConfigSerializable))
        {
            $format = new $format();

            if(!$format instanceof ObjectConfigSerializable)
            {
                throw new \UnexpectedValueException(
                    'Format: '.get_class($format).' does not implement ObjectConfigSerializable Interface'
                );
            }

            $this->_formats[$format->name] = $format;
        }
        else $format = clone $format;

        return $format;
    }

    /**
     * Register config format
     *
     * @param string $format    The name of the format
     * @param mixed	$identifier An object that implements ObjectInterface, ObjectIdentifier object
     * 					        or valid identifier string
     * @return	ObjectConfigFactory
     * throws \InvalidArgumentException If the class does not exist.
     */
    public function registerFormat($format, $class)
    {
        if(!class_exists($class, true)) {
            throw new \InvalidArgumentException('Class : $class cannot does not exist.');
        }

        $this->_formats[$format] = $class;
        return $this;
    }

    /**
     * Read a config from a file.
     *
     * @param  string  $filename
     * @return ObjectConfig
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function fromFile($filename)
    {
        $pathinfo = pathinfo($filename);

        if (!isset($pathinfo['extension']))
        {
            throw new \RuntimeException(sprintf(
                'Filename "%s" is missing an extension and cannot be auto-detected', $filename
            ));
        }

        $config = $this->getIntance($pathinfo['extension'])->fromFile($filename);
        return $config;
    }

    /**
     * Writes a config to a file
     *
     * @param string $filename
     * @param ObjectConfig $config
     * @return boolean TRUE on success. FALSE on failure
     * @throws \RuntimeException
     */
    public function toFile($filename, ObjectConfig $config)
    {
        $pathinfo = pathinfo($filename);

        if (!isset($pathinfo['extension']))
        {
            throw new \RuntimeException(sprintf(
                'Filename "%s" is missing an extension and cannot be auto-detected', $filename
            ));
        }

        return $this->getInstance($pathinfo['extension'])->toFile($filename, $config);
    }
}