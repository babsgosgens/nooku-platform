<?php
/**
 * @package     Koowa_Object
 * @subpackage  Decorator
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Object Decorator Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Decorator
 */
class ObjectDecorator extends Object implements ObjectDecoratorInterface
{
    /**
     * Class methods
     *
     * @var array
     */
    private $__methods = array();

    /**
     *  The object being decorated
     *
     * @var Object
     */
    protected $_delegate;

    /**
     * Constructor
     *
     * @param Config $config  An optional Config object with configuration options
     * @return ObjectDecorator
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        $this->_delegate = $config->delegate;
    }

    /**
     * Get the decorated object
     *
     * @return ObjectDecoratable The decorated object
     */
    public function getDelegate()
    {
        return $this->_delegate;
    }

    /**
     * Set the decorated object
     *
     * @param   ObjectDecoratable $delegate The decorated object
     * @return  ObjectDecorator
     */
    public function setDelegate(ObjectDecoratable $delegate)
    {
        $this->_delegate = $delegate;
        return $this;
    }

    /**
     * Decorate Notifier
     *
     * This function is called when an object is being decorated. It will get the object passed in.
     *
     * @param ObjectDecoratable $delegate The object being decorated
     * @return ObjectMixinInterface
     */
    public function onDecorate(ObjectDecoratable $delegate)
    {
        $this->setDelegate($delegate);
        return $this;
    }

    /**
     * Get a list of all the available methods
     *
     * This function returns an array of all the methods, both native and mixed. It will also return the methods
     * exposed by the decorated object.
     *
     * @return array An array
     */
    public function getMethods()
    {
        if (!$this->__methods)
        {
            $methods = array();
            $object = $this->getDelegate();

            if (!($object instanceof ObjectMixable))
            {
                $reflection = new \ReflectionClass($object);
                foreach ($reflection->getMethods() as $method) {
                    $methods[] = $method->name;
                }
            }
            else $methods = $object->getMethods();

            $this->__methods = array_merge(parent::getMethods(), $methods);
        }

        return $this->__methods;
    }

    /**
     * Checks if the decorated object or one of it's mixin's inherits from a class.
     *
     * @param   string|object $class  The class to check
     * @return  boolean  Returns TRUE if the object inherits from the class
     */
    public function inherits($class)
    {
        $result = false;
        $object = $this->getDelegate();

        if ($object instanceof ObjectMixable) {
            $result = $object->inherits($class);
        } else {
            $result = $object instanceof $class;
        }

        return $result;
    }

    /**
     * Overloaded set function
     *
     * @param  string $key   The variable name
     * @param  mixed  $value The variable value.
     * @return mixed
     */
    public function __set($key, $value)
    {
        $this->getDelegate()->$key = $value;
    }

    /**
     * Overloaded get function
     *
     * @param  string $key  The variable name.
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getDelegate()->$key;
    }

    /**
     * Overloaded isset function
     *
     * Allows testing with empty() and isset() functions
     *
     * @param  string $key The variable name
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->getDelegate()->$key);
    }

    /**
     * Overloaded isset function
     *
     * Allows unset() on object properties to work
     *
     * @param string $key The variable name.
     * @return void
     */
    public function __unset($key)
    {
        if (isset($this->getDelegate()->$key)) {
            unset($this->getDelegate()->$key);
        }
    }

    /**
     * Overloaded call function
     *
     * @param  string     $method    The function name
     * @param  array      $arguments The function arguments
     * @throws \BadMethodCallException     If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        $object = $this->getDelegate();

        //Check if the method exists
        if ($object instanceof ObjectMixable)
        {
            $methods = $object->getMethods();
            $exists = in_array($method, $methods);
        }
        else $exists = method_exists($object, $method);

        //Call the method if it exists
        if ($exists)
        {
            $result = null;

            // Call_user_func_array is ~3 times slower than direct method calls.
            switch (count($arguments))
            {
                case 0 :
                    $result = $object->$method();
                    break;
                case 1 :
                    $result = $object->$method($arguments[0]);
                    break;
                case 2:
                    $result = $object->$method($arguments[0], $arguments[1]);
                    break;
                case 3:
                    $result = $object->$method($arguments[0], $arguments[1], $arguments[2]);
                    break;
                default:
                    // Resort to using call_user_func_array for many segments
                    $result = call_user_func_array(array($object, $method), $arguments);
            }

            //Allow for method chaining through the decorator
            $class = get_class($object);
            if ($result instanceof $class) {
                return $this;
            }

            return $result;
        }

        return parent::__call($method, $arguments);
    }
}