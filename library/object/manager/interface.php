<?php
/**
 * @package		Koowa_Object
 * @subpackage  Manager
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Object Manager Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Manager
 */
interface ObjectManagerInterface
{
    /**
     * Get an object instance based on an object identifier
     *
     * If the object implements the ObjectSingleton interface the object will be automatically registered in the
     * object registry.
     *
     * If the object implements the ObjectInstantiable interface the manager will delegate object instantiation
     * to the object itself.
     *
     * @param	string|object	$identifier  An ObjectIdentifier or identifier string
     * @param	array  			$config     An optional associative array of configuration settings.
     * @throws	ObjectException
     * @return	ObjectInterface  Return object on success, throws exception on failure
     */
    public function getObject($identifier, array $config = array());

    /**
     * Register an object instance for a specific object identifier
     *
     * @param string|object	 $identifier  The identifier string or identifier object
     * @param ObjectInterface $object     An object that implements ObjectInterface
     * @return ObjectManagerInterface
     */
	public function setObject($identifier, ObjectInterface $object);

    /**
     * Returns an identifier object.
     *
     * If no identifier is passed the object identifier of this object will be returned. Function recursively
     * resolves identifier aliases and returns the aliased identifier.
     *
     * @param mixed $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @return ObjectIdentifier
     */
    public function getIdentifier($identifier = null);

    /**
     * Register and identifier
     *
     * @param mixed  $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @param array $config      An optional associative array of configuration options
     * @return ObjectManagerInterface
     */
    public function setIdentifier($identifier, array $config = null);

    /**
     * Register a mixin or an array of mixins for an identifier
     *
     * The mixin is mixed when the identified object is first instantiated see {@link get} The mixin is also mixed with
     * with the represented by the identifier if the object is registered in the object manager. This mostly applies to
     * singletons but can also apply to other objects that are manually registered.
     *
     * @param mixed $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @param mixed $mixin      An ObjectIdentifier, identifier string or object implementing ObjectMixinInterface
     * @return ObjectManagerInterface
     * @see Object::mixin()
     */
    public function registerMixin($identifier, $mixins);

    /**
     * Register a decorator or an array of decorators for an identifier
     *
     * The object is decorated when it's first instantiated see {@link get} The object represented by the identifier is
     * also decorated if the object is registered in the object manager. This mostly applies to singletons but can also
     * apply to other objects that are manually registered.
     *
     * @param mixed $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @param mixed $decorator  An ObjectIdentifier, identifier string or object implementing ObjectDecoratorInterface
     * @return ObjectManagerInterface
     * @see Object::decorate()
     */
    public function registerDecorator($identifier, $decorator);

    /**
     * Register an alias for an identifier
     *
     * @param string $alias      The alias
     * @param mixed  $identifier The class identifier or identifier object
     * @return ObjectManagerInterface
     */
    public function registerAlias($alias, $identifier);

    /**
     * Get the aliases for an identifier
     *
     * @param mixed $identifier An ObjectIdentifier, identifier string or object implementing ObjectInterface
     * @return array   An array of aliases
     */
    public function getAliases($identifier);

    /**
     * Register an object locator
     *
     * @param mixed $identifier An ObjectIdentifier, identifier string or object implementing ObjectLocatorInterface
     * @return ObjectManagerInterface
     */
    public function registerLocator($identifier, array $config = array());

    /**
     * Get a registered object locator based on his type
     *
     * @param string $type The locator type
     * @return ObjectLocatorInterface|null  Returns the object locator or NULL if it cannot be found.
     */
    public function getLocator($type);

    /**
     * Get the class loader
     *
     * @return ClassLoaderInterface
     */
    public function getClassLoader();

    /**
     * Set the class loader
     *
     * @param ClassLoaderInterface $loader
     * @return ObjectManagerInterface
     */
    public function setClassLoader(ClassLoaderInterface $loader);

    /**
     * Check if an object instance was registered for the identifier
     *
     * @param mixed $identifier An object that implements the ObjectInterface, an ObjectIdentifier or valid identifier string
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function isRegistered($identifier);

    /**
     * Check if the object is a singleton
     *
     * @param mixed $identifier An object that implements the ObjectInterface, an ObjectIdentifier or valid identifier string
     * @return boolean Returns TRUE if the object is a singleton, FALSE otherwise.
     */
    public function isSingleton($identifier);
}