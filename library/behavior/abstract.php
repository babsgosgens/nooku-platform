<?php
/**
 * @package        Koowa_Behavior
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

/**
 * Abstract Behavior Class
 *
 * @author  Johan Janssens <johan@nooku.org>
 * @package Koowa_Behavior
 */
abstract class BehaviorAbstract extends ObjectMixinAbstract implements BehaviorInterface
{
    /**
     * The behavior priority
     *
     * @var integer
     */
    protected $_priority;

    /**
     * The service identifier
     *
     * @var ServiceIdentifier
     */
    private $__service_identifier;

    /**
     * The service manager
     *
     * @var ServiceManager
     */
    private $__service_manager;

    /**
     * Constructor.
     *
     * @param  Config $config  A Config object with configuration options
     */
    public function __construct(Config $config)
    {
        //Set the service container
        if (isset($config->service_manager)) {
            $this->__service_manager = $config->service_manager;
        }

        //Set the service identifier
        if (isset($config->service_identifier)) {
            $this->__service_identifier = $config->service_identifier;
        }

        parent::__construct($config);

        $this->_priority = $config->priority;

        //Automatically mixin the behavior
        if ($config->auto_mixin) {
            $this->mixin($this);
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Config $config A Config object with configuration options
     * @return void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'priority' => CommandChain::PRIORITY_NORMAL,
            'auto_mixin' => false
        ));

        parent::_initialize($config);
    }

    /**
     * Get the priority of a behavior
     *
     * @return  integer The command priority
     */
    public function getPriority()
    {
        return $this->_priority;
    }

    /**
     * Command handler
     *
     * This function translated the command name to a command handler function of the format '_before[Command]' or
     * '_after[Command]. Command handler functions should be declared protected.
     *
     * @param   string           $name     The command name
     * @param   CommandContext  $context  The command context
     *
     * @return  mixed  Method result if the method exsist, NULL otherwise.
     */
    public function execute($name, CommandContext $context)
    {
        $result = null;

        $identifier = clone $context->getSubject()->getIdentifier();
        $type = array_pop($identifier->path);

        $parts = explode('.', $name);
        $method = '_' . $parts[0] . ucfirst($type) . ucfirst($parts[1]);

        //If the method exists call the method and return the result
        if (method_exists($this, $method)) {
            $result = $this->$method($context);
        }

        return $result;
    }

    /**
     * Get an object handle
     *
     * This function only returns a valid handle if one or more command handler functions are defined. A commend handler
     * function needs to follow the following format : '_afterX[Event]' or '_beforeX[Event]' to be recognised.
     *
     * @return string A string that is unique, or NULL
     * @see execute()
     */
    public function getHandle()
    {
        $methods = $this->getMethods();

        foreach ($methods as $method)
        {
            if (substr($method, 0, 7) == '_before' || substr($method, 0, 6) == '_after') {
                return parent::getHandle();
            }
        }

        return null;
    }

    /**
     * Get the methods that are available for mixin based
     *
     * This function also dynamically adds a function of format is[Behavior] to allow client code to check if the
     * behavior is callable.
     *
     * @param  ObjectInterface $mixer The mixer requesting the mixable methods.
     * @return array An array of methods
     */
    public function getMixableMethods(ObjectMixable $mixer = null)
    {
        $methods = parent::getMixableMethods($mixer);
        $methods['is' . ucfirst($this->getIdentifier()->name)] = function() { return true; };

        unset($methods['execute']);
        unset($methods['getIdentifier']);
        unset($methods['getPriority']);
        unset($methods['getHandle']);
        unset($methods['getService']);

        return $methods;
    }

    /**
     * Get an instance of a class based on a class identifier only creating it if it does not exist yet.
     *
     * @param    string|object    $identifier The class identifier or identifier object
     * @param    array            $config     An optional associative array of configuration settings.
     * @throws   \RuntimeException If the service manager has not been defined.
     * @return   Service Return object on success, throws exception on failure
     * @see      ServiceInterface
     */
    final public function getService($identifier = null, array $config = array())
    {
        if (isset($identifier))
        {
            if (!isset($this->__service_manager))
            {
                throw new \RuntimeException(
                    "Failed to call " . get_class($this) . "::getService(). No service_manager object defined."
                );
            }

            $result = $this->__service_manager->get($identifier, $config);
        }
        else $result = $this->__service_manager;

        return $result;
    }

    /**
     * Gets the service identifier.
     *
     * @param   string|object     $identifier The class identifier or identifier object
     * @throws  \RuntimeException If the service manager has not been defined.
     * @return  ServiceIdentifier
     * @see     ServiceInterface
     */
    final public function getIdentifier($identifier = null)
    {
        if (isset($identifier))
        {
            if (!isset($this->__service_manager))
            {
                throw new \RuntimeException(
                    "Failed to call " . get_class($this) . "::getIdentifier(). No service_manager object defined."
                );
            }

            $result = $this->__service_manager->getIdentifier($identifier);
        }
        else  $result = $this->__service_identifier;

        return $result;
    }
}