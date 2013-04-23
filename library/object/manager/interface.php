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
	 * @param	string|object	$identifier The identifier string or identifier object
	 * @param	array  			$config     An optional associative array of configuration settings.
	 * @throws	ObjectException
	 * @return	object  		Return object on success, throws exception on failure
	 */
	public function get($identifier, array $config = array());

    /**
     * Load a file based on an identifier
     *
     * @param string|object	$identifier The identifier string or identifier object
     * @return boolean      Returns TRUE if the identifier could be loaded, otherwise returns FALSE.
     * @see ClassLoader::loadFile();
     */
    public function load($identifier);

	/**
	 * Register an object instance for a specific object identifier
	 *
	 * @param string|object	$identifier   The identifier string or identifier object
	 * @param ObjectInterface $config     The object instance to store
     * @return ObjectManagerInterface
	 */
	public function register($identifier, ObjectInterface $object);

    /**
     * Returns an identifier object.
     *
     * Accepts various types of parameters and returns a valid identifier. Parameters can either be an object that
     * implements ObjectInterface, or a ObjectIdentifier object, or valid identifier string.
     *
     * Function will also check for identifier mappings and return the mapped identifier.
     *
     * @param	mixed $identifier An object that implements ObjectInterface, an ObjectIdentifier object
     * 					         or valid identifier string
     * @return ObjectIdentifier
     */
    public function getIdentifier($identifier);

    /**
     * Set the configuration options for an identifier
     *
     * @param mixed	$identifier An object that implements ObjectInterface, an ObjectIdentifier object
     * 				            or valid identifier string
     * @param array	$config     An associative array of configuration options
     * @return ObjectManagerInterface
     */
    public function setIdentifier($identifier, array $config);

    /**
     * Register a mixin for an identifier
     *
     * The mixin is mixed when the identified object is first instantiated see {@link get} The mixin is also mixed with
     * with the represented by the identifier if the object is registered in the object manager. This mostly applies to
     * singletons but can also apply to other objects that are manually registered.
     *
     * @param mixed $identifier An object that implements ObjectInterface, an ObjectIdentifier or an identifier string
     * @param mixed $mixin      An object that implements ObjectMixerInterface, an ObjectIdentifier or an identifier string
     * @return ObjectManager
     * @see Object::mixin()
     */
    public function registerMixin($identifier, $mixins);

    /**
     * Get the mixins for an identifier
     *
     * @param mixed $identifier An object that implements ObjectInterface, an ObjectIdentifier object
     *                          or valid identifier string
     * @return ObjectRegistry   An array of mixins
     */
    public function getMixins($identifier);

    /**
     * Register a decorator for an identifier
     *
     * The object is decorated when it's first instantiated see {@link get} The object represented by the identifier is
     * also decorated if the object is registered in the object manager. This mostly applies to singletons but can also
     * apply to other objects that are manually registered.
     *
     * @param mixed $identifier An object that implements ObjectInterface, an ObjectIdentifier or an identifier string
     * @param mixed $decorator  An object that implements ObjectDecoratorInterface, an ObjectIdentifier or an identifier string
     * @param  string $decorator A decorator identifier
     * @return ObjectManagerInterface
     * @see Object::decorate()
     */
    public function registerDecorator($identifier, $decorator);

    /**
     * Get the decorators for an identifier
     *
     * @param mixed $identifier An object that implements ObjectInterface, an ObjectIdentifier object
     *                          or valid identifier string
     * ObjectRegistry   An array of decorators
     */
    public function getDecorators($identifier);

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
     * @param mixed $identifier An object that implements ObjectInterface, an ObjectIdentifier object
     *                          or valid identifier string
     * @return array   An array of aliases
     */
    public function getAliases($identifier);

    /**
     * Register an object locator
     *
     * @param mixed $identifier An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @return ObjectManager
     */
    public function registerLocator($identifier);

    /**
     * Get the registered object locators
     *
     * @return array
     */
    public function getLocators();

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
     * Check if the object instance exists based on the identifier
     *
     * @param string|object	$identifier The identifier string or identifier object
     * @return boolean Returns TRUE on success or FALSE on failure.
     */
    public function isRegistered($identifier);

    /**
     * Check if the object is a singleton
     *
     * @param string|object	$identifier The identifier string or identifier object
     * @return boolean Returns TRUE if the object is a singleton, FALSE otherwise.
     */
    public function isSingleton($identifier);
}