<?php
/**
 * @package		Koowa_Object
 * @subpackage  Identifier
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

/**
 * Object Identifier
 *
 * Wraps identifiers of the form type://package.[.path].name in an object, providing public accessors and methods for
 * derived formats.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Identifier
 */
class ObjectIdentifier implements ObjectIdentifierInterface
{
    /**
     * The object locators
     *
     * @var array
     */
    protected static $_locators = array();

    /**
     * The object identifier
     *
     * @var string
     */
    protected $_identifier = '';

    /**
     * The identifier type [com|lib]
     *
     * @var string
     */
    protected $_type = '';

    /**
     * The identifier package
     *
     * @var string
     */
    protected $_package = '';

    /**
     * The identifier path
     *
     * @var array
     */
    protected $_path = array();

    /**
     * The identifier object name
     *
     * @var string
     */
    protected $_name = '';

    /**
     * The file path
     *
     * @var string
     */
    protected $_classpath = '';

    /**
     * The classname
     *
     * @var string
     */
    protected $_classname = '';

    /**
     * The object config
     *
     * @var ObjectConfig
     */
    protected $_config = null;

    /**
     * The object mixins
     *
     * @var array
     */
    protected $_mixins = array();

    /**
     * The object decorators
     *
     * @var array
     */
    protected $_decorators = array();

    /**
     * Constructor
     *
     * @param   string $identifier Identifier string or object in type://namespace/package.[.path].name format
     * @throws  ObjectExceptionInvalidIdentifier If the identifier is not valid
     */
    public function __construct($identifier)
    {
        //Check if the identifier is valid
        if(strpos($identifier, ':') === FALSE) {
            throw new ObjectExceptionInvalidIdentifier('Malformed identifier : '.$identifier);
        }

        //Get the parts
        if(false === $parts = parse_url($identifier)) {
            throw new ObjectExceptionInvalidIdentifier('Malformed identifier : '.$identifier);
        }

        // Set the type
        $this->type = $parts['scheme'];

        // Set the path
        $this->_path = trim($parts['path'], '/');
        $this->_path = explode('.', $this->_path);

        // Set the extension (first part)
        $this->_package = array_shift($this->_path);

        // Set the name (last part)
        if(count($this->_path)) {
            $this->_name = array_pop($this->_path);
        }
    }

    /**
     * Get the identifier type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Set the identifier type
     *
     * @param  string $type
     * @return  ObjectIdentifierInterface
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get the identifier package
     *
     * @return string
     */
    public function getPackage()
    {
        return $this->_package;
    }

    /**
     * Set the identifier package
     *
     * @param  string $package
     * @return  ObjectIdentifierInterface
     */
    public function setPackage($package)
    {
        $this->package = $package;
        return $this;
    }

    /**
     * Get the identifier package
     *
     * @return array
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * Set the identifier path
     *
     * @param  string $path
     * @return  ObjectIdentifierInterface
     */
    public function setPath(array $path)
    {
        $this->_path = $path;
        return $this;
    }

    /**
     * Get the identifier package
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set the identifier name
     *
     * @param  string $name
     * @return  ObjectIdentifierInterface
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the config
     *
     * This function will lazy create a config object is one does not exist yet.
     *
     * @return ObjectConfig
     */
    public function getConfig()
    {
        if(!isset($this->_config)) {
            $this->_config = new ObjectConfig();
        }

        return $this->_config;
    }

    /**
     * Set the config
     *
     * @param   array    $data   A ObjectConfig object or a an array of configuration options
     * @param   boolean  $merge  If TRUE the data in $config will be merged instead of replaced. Default TRUE.
     * @return  ObjectIdentifierInterface
     */
    public function setConfig($data, $merge = true)
    {
        $config = $this->getConfig();

        if($merge) {
            $config->append($data);
        } else {
            $this->_config = new ObjectConfig($data);
        }

        return $this;
    }

    /**
     * Add a mixin
     *
     * @param mixed $mixin      An ObjectIdentifier, identifier string or object implementing ObjectMixinInterface
     * @return ObjectIdentifierInterface
     * @see Object::mixin()
     */
    public function addMixin($mixin)
    {
        $this->_mixins[] = $mixin;
        return $this;
    }

    /**
     * Get the mixin registry
     *
     * @return array
     */
    public function getMixins()
    {
        return $this->_mixins;
    }

    /**
     * Add a decorator
     *
     * @param mixed $decorator  An ObjectIdentifier, identifier string or object implementing ObjectDecoratorInterface
     * @return ObjectIdentifierInterface
     * @see Object::decorate()
     */
    public function addDecorator($decorator)
    {
        $this->_decorators[] = $decorator;
        return $this;
    }

    /**
     * Get the decorators
     *
     *  @return array
     */
    public function getDecorators()
    {
        return $this->_decorators;
    }

    /**
     * Add a object locator
     *
     * @param ObjectLocatorInterface $locator
     * @return ObjectIdentifierInterface
     */
    public static function addLocator(ObjectLocatorInterface $locator)
    {
        self::$_locators[$locator->getType()] = $locator;
    }

    /**
     * Get the object locator
     *
     * @return ObjectLocatorInterface|null  Returns the object locator or NULL if the locator can not be found.
     */
    public function getLocator()
    {
        $result = null;
        if(isset(self::$_locators[$this->_type])) {
            $result = self::$_locators[$this->_type];
        }

        return $result;
    }

    /**
     * Get the decorators
     *
     *  @return array
     */
    public static function getLocators()
    {
        return self::$_locators;
    }

    /**
     * Get the identifier class name
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->classname;
    }

    /**
     * Get the identifier file path
     *
     * @return string
     */
    public function getClassPath()
    {
        return $this->classpath;
    }

    /**
     * Check if the object is a singleton
     *
     * @return boolean Returns TRUE if the object is a singleton, FALSE otherwise.
     */
    public function isSingleton()
    {
        return array_key_exists(__NAMESPACE__.'\ObjectSingleton', class_implements($this->classname));
    }

    /**
     * Formats the identifier as a type://package.[.path].name string
     *
     * @return string
     */
    public function toString()
    {
        if($this->_identifier == '')
        {
            $this->_identifier .= $this->_type;
            $this->_identifier .= ':';

            if(!empty($this->_package)) {
                $this->_identifier .= $this->_package;
            }

            if(count($this->_path)) {
                $this->_identifier .= '.'.implode('.',$this->_path);
            }

            if(!empty($this->_name)) {
                $this->_identifier .= '.'.$this->_name;
            }
        }

        return $this->_identifier;
    }

	/**
	 * Serialize the identifier
	 *
	 * @return string 	The serialised identifier
	 */
	public function serialize()
	{
        $data = array(
            'type'		 => $this->_type,
            'package'	 => $this->_package,
            'path'		 => $this->_path,
            'name'		 => $this->_name,
            'identifier' => $this->_identifier,
            'classpath'  => $this->classpath,
            'classname'  => $this->classname,
        );

        return serialize($data);
	}

	/**
	 * Unserialize the identifier
	 *
	 * @return string $data	The serialised identifier
	 */
	public function unserialize($data)
	{
	    $data = unserialize($data);

	    foreach($data as $property => $value) {
	        $this->{'_'.$property} = $value;
	    }
	}

    /**
     * Implements the virtual class properties
     *
     * This functions creates a string representation of the identifier.
     *
     * @param   string  $property The virtual property to set.
     * @param   string  $value    Set the virtual property to this value.
     * @throws \DomainException If the type is unknown
     */
    public function __set($property, $value)
    {
        if(isset($this->{'_'.$property}))
        {
            //Force the path to an array
            if($property == 'path')
            {
                if(is_scalar($value)) {
                     $value = (array) $value;
                }
            }

            //Check if the type for a valid locator
            if($property == 'type')
            {
                //Set the type first then check if a locator can be found
                $this->_type = $value;

                //Make exception for 'lib' locator
                if($value != 'lib' && !$this->getLocator()) {
                    throw new \DomainException('Unknow type : '.$value);
                }
            }

            //Set the properties
            $this->{'_'.$property} = $value;

            //Reset the properties
            $this->_identifier = '';
            $this->_classname  = '';
            $this->_classpath  = '';
        }
    }

    /**
     * Implements access to virtual properties by reference so that it appears to be a public property.
     *
     * @param   string  $property The virtual property to return.
     * @return  array   The value of the virtual property.
     */
    public function &__get($property)
    {
        $result = null;
        if(isset($this->{'_'.$property}))
        {
            if($property == 'classpath' && empty($this->_classpath)) {
                $this->_classpath = $this->getLocator()->findPath($this);
            }

            if($property == 'classname' && empty($this->_classname)) {
                $this->_classname = $this->getLocator()->locate($this);
            }

            $result =& $this->{'_'.$property};
        }

        return $result;
    }

    /**
     * This function checks if a virtual property is set.
     *
     * @param   string  $property The virtual property to return.
     * @return  boolean True if it exists otherwise false.
     */
    public function __isset($property)
    {
        $name = ltrim($property, '_');
        $vars = get_object_vars($this);

        return isset($vars['_'.$name]);
    }

    /**
     * Allow PHP casting of this object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}