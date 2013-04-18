<?php
/**
 * @package     Koowa_Object
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Object class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Object
 */
class Object extends Service implements ObjectInterface
{
    /**
     * Class methods
     *
     * @var array
     */
    private $__methods = array();

    /**
     * Mixed in methods
     *
     * @var array
     */
    protected $_mixed_methods = array();

    /**
     * Constructor
     *
     * @param Config  $config  A Config object with optional configuration options
     * @return Object
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        //Initialise the object
        $this->_initialize($config);

        //Add the mixins
        $mixins = (array)Config::unbox($config->mixins);

        foreach ($mixins as $key => $value)
        {
            if (is_numeric($key)) {
                $this->mixin($value);
            } else {
                $this->mixin($key, $value);
            }
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Config $object An optional Config object with configuration options
     * @return  void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'mixins' => array(),
        ));
    }

    /**
     * Mixin an object
     *
     * When using mixin(), the calling object inherits the methods of the mixed in objects, in a LIFO order.
     *
     * @@param   mixed  $mixin  An object that implements ObjectMixinInterface, ServiceIdentifier object
     *                          or valid identifier string
     * @param    array $config  An optional associative array of configuration options
     * @return  ObjectInterface
     */
    public function mixin($mixin, $config = array())
    {
        if (!($mixin instanceof ObjectMixinInterface))
        {
            if (!($mixin instanceof ServiceIdentifier))
            {
                //Create the complete identifier if a partial identifier was passed
                if (is_string($mixin) && strpos($mixin, '.') === false)
                {
                    $identifier = clone $this->getIdentifier();
                    $identifier->path = 'mixin';
                    $identifier->name = $mixin;
                }
                else $identifier = $this->getIdentifier($mixin);
            }
            else $identifier = $mixin;

            $mixin = new $identifier->classname(new Config($config));

            if(!$mixin instanceof ObjectMixinInterface)
            {
                throw new \UnexpectedValueException(
                    'Mixin: '.get_class($identifier).' does not implement ObjectMixinInterface'
                );
            }
        }

        //Set the mixed methods and overwrite existing methods
        $this->_mixed_methods = array_merge($this->_mixed_methods, $mixin->getMixableMethods($this));

        //Notify the mixin
        $mixin->onMixin($this);

        return $this;
    }

    /**
     * Decorate the object
     *
     * When using decorate(), the object will be decorated by the decorator
     *
     * @@param   mixed  $decorator  An object that implements ObjectDecorator, ServiceIdentifier object
     *                              or valid identifier string
     * @param    array $config  An optional associative array of configuration options
     * @return   ObjectDecoratable
     */
    public function decorate($decorator, $config = array())
    {
        if (!($decorator instanceof ObjectDecorator))
        {
            if (!($decorator instanceof ServiceIdentifier))
            {
                //Create the complete identifier if a partial identifier was passed
                if (is_string($decorator) && strpos($decorator, '.') === false)
                {
                    $identifier = clone $this->getIdentifier();
                    $identifier->path = 'decorator';
                    $identifier->name = $decorator;
                }
                else $identifier = $this->getIdentifier($decorator);
            }
            else $identifier = $decorator;

            $decorator = new $identifier->classname(new Config($config));

            if(!$decorator instanceof ObjectDecoratorInterface)
            {
                throw new \UnexpectedValueException(
                    'Decorator: '.get_class($identifier).' does not implement ObjectDecoratorInterface'
                );
            }
        }

        //Notify the decorator
        $decorator->onDecorate($this);

        return $decorator;
    }

    /**
     * Checks if the object or one of it's mixin's inherits from a class.
     *
     * @param   string|object   $class The class to check
     * @return  bool Returns TRUE if the object inherits from the class
     */
    public function inherits($class)
    {
        if ($this instanceof $class) {
            return true;
        }

        $objects = array_values($this->_mixed_methods);

        foreach ($objects as $object)
        {
            if ($object instanceof $class) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get a handle for this object
     *
     * This function returns an unique identifier for the object. This id can be used as a hash key for storing objects
     * or for identifying an object
     *
     * @return string A string that is unique
     */
    public function getHandle()
    {
        return spl_object_hash($this);
    }

    /**
     * Get a list of all the available methods
     *
     * This function returns an array of all the methods, both native and mixed in
     *
     * @return array An array
     */
    public function getMethods()
    {
        if (!$this->__methods)
        {
            $methods = array();

            $reflection = new \ReflectionClass($this);
            foreach ($reflection->getMethods() as $method) {
                $methods[] = $method->name;
            }

            $this->__methods = array_merge($methods, array_keys($this->_mixed_methods));
        }

        return $this->__methods;
    }

    /**
     * Preform a deep clone of the object.
     *
     * @return void
     */
    public function __clone()
    {
        foreach ($this->_mixed_methods as $method => $object)
        {
            if (!$object instanceof \Closure) {
                $this->_mixed_methods[$method] = clone $object;
            }
        }
    }

    /**
     * Search the mixin method map and call the method or trigger an error
     *
     * @param  string $method    The function name
     * @param  array  $arguments The function arguments
     * @throws \BadMethodCallException   If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        if (isset($this->_mixed_methods[$method]))
        {
            $result = null;

            if ($this->_mixed_methods[$method] instanceof \Closure)
            {
                $closure = $this->_mixed_methods[$method];

                switch (count($arguments)) {
                    case 0 :
                        $result = $closure();
                        break;
                    case 1 :
                        $result = $closure($arguments[0]);
                        break;
                    case 2 :
                        $result = $closure($arguments[0], $arguments[1]);
                        break;
                    case 3 :
                        $result = $closure($arguments[0], $arguments[1], $arguments[2]);
                        break;
                    default:
                        // Resort to using call_user_func_array for many segments
                        $result = call_user_func_array($closure, $arguments);
                }
            }
            else
            {
                $object = $this->_mixed_methods[$method];

                //Switch the mixin's attached mixer
                $object->setMixer($this);

                // Call_user_func_array is ~3 times slower than direct method calls.
                switch (count($arguments))
                {
                    case 0 :
                        $result = $object->$method();
                        break;
                    case 1 :
                        $result = $object->$method($arguments[0]);
                        break;
                    case 2 :
                        $result = $object->$method($arguments[0], $arguments[1]);
                        break;
                    case 3 :
                        $result = $object->$method($arguments[0], $arguments[1], $arguments[2]);
                        break;
                    default:
                        // Resort to using call_user_func_array for many segments
                        $result = call_user_func_array(array($object, $method), $arguments);
                }
            }

            return $result;
        }

        throw new \BadMethodCallException('Call to undefined method :' . $method);
    }
}