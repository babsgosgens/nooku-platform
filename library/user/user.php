<?php
/**
 * @package		Koowa_Controller
 * @subpackage  User
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * User Class
 *
 * User is the user implementation used by the in-memory user provider. This object is tightly coupled to the session.
 * all data is stored and retrieved from the session attribute container, using a special 'user' namespace to avoid
 * conflicts.
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_User
 */
class User extends Object implements UserInterface, ServiceInstantiatable
{
    /**
     * Constructor
     *
     * @param Config $config An optional Config object with configuration options.
     * @return User
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        //Set the user properties and attributes
        $this->values(Config::unbox($config));
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Config $config An optional Config object with configuration options.
     * @return void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'id'         => 0,
            'email'      => '',
            'name'       => '',
            'role'       => 0,
            'groups'     => array(),
            'password'   => '',
            'salt'       => '',
            'authentic'  => false,
            'enabled'    => true,
            'expired'    => false,
            'attributes' => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Force creation of a singleton
     *
     * @param 	Config                 $config	  A Config object with configuration options
     * @param 	ServiceManagerInterface	$manager  A ServiceInterface object
     * @return DispatcherRequest
     */
    public static function getInstance(Config $config, ServiceManagerInterface $manager)
    {
        if (!$manager->has('user'))
        {
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $manager->set($config->service_identifier, $instance);

            $manager->setAlias('user', $config->service_identifier);
        }

        return $manager->get('user');
    }

    /**
     * Returns the id of the user
     *
     * @return int The id
     */
    public function getId()
    {
        return $this->getSession()->get('user.id');
    }

    /**
     * Returns the email of the user
     *
     * @return string The email
     */
    public function getEmail()
    {
       return $this->getSession()->get('user.email');
    }

    /**
     * Returns the name of the user
     *
     * @return string The name
     */
    public function getName()
    {
        return $this->getSession()->get('user.name');
    }

    /**
     * Returns the role of the user
     *
     * @return int The role id
     */
    public function getRole()
    {
        return $this->getSession()->get('user.role');
    }

    /**
     * Returns the groups the user is part of
     *
     * @return array An array of group id's
     */
    public function getGroups()
    {
        return $this->getSession()->get('user.groups');
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text password will be salted, encoded, and
     * then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->getSession()->get('user.password');
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string The salt
     */
    public function getSalt()
    {
        return $this->getSession()->get('user.salt');
    }

    /**
     * Checks whether the user is not logged in
     *
     * @return Boolean true if the user is not logged in, false otherwise
     */
    public function isAuthentic()
    {
        return $this->getSession()->get('user.authentic');
    }

    /**
     * Checks whether the user is enabled.
     *
     * @return Boolean true if the user is not logged in, false otherwise
     */
    public function isEnabled()
    {
        return $this->getSession()->get('user.enabled');
    }

    /**
     * Checks whether the user account has expired.
     *
     * @return Boolean
     */
    public function isExpired()
    {
        return $this->getSession()->get('user.expired');
    }

    /**
     * Get the user session
     *
     * This function will create a session object if it hasn't been created yet.
     *
     * @return UserSessionInterface
     */
    public function getSession()
    {
        return $this->getService('lib:user.session');
    }

    /**
     * Get the user data as an array
     *
     * @return array An associative array of data
     */
    public function toArray()
    {
        return $this->getSession()->get('user');
    }

    /**
     * Set the user data from an array
     *
     * @param  array $data An associative array of data
     * @return User
     */
    public function values(array $data)
    {
        //Re-initialize the object
        $data = new Config($data);
        $this->_initialize($data);

        unset($data['mixins']);
        unset($data['service_manager']);
        unset($data['service_identifier']);

        //Set the user data
        $this->getSession()->set('user', Config::unbox($data));

        return $this;
    }

    /**
     * Get an user attribute
     *
     * @param   string  $identifier Attribute identifier, eg .foo.bar
     * @param   mixed   $value      Default value when the attribute doesn't exist
     * @return  mixed   The value
     */
    public function get($identifier, $default = null)
    {
        return $this->getSession()->get('user.attributes'.$identifier, $default);
    }

    /**
     * Set an user attribute
     *
     * @param   mixed   $identifier Attribute identifier, eg foo.bar
     * @param   mixed   $value Attribute value
     * @return User
     */
    public function set($identifier, $value)
    {
        $this->getSession()->set('user.attributes'.$identifier, $value);
        return $this;
    }

    /**
     * Check if a user attribute exists
     *
     * @param   string  $identifier Attribute identifier, eg foo.bar
     * @return  boolean
     */
    public function has($identifier)
    {
        return $this->getSession()->has('user.attributes'.$identifier);
    }

    /**
     * Removes an user attribute
     *
     * @param string $identifier Attribute identifier, eg foo.bar
     * @return User
     */
    public function remove($identifier)
    {
        $this->getSession()->remove('user.attributes'.$identifier);
        return $this;
    }

    /**
     * Get a user attribute
     *
     * @param   string $name  The attribute name.
     * @return  string $value The attribute value.
     */
    public function __get($name)
    {
        return $this->getSession()->get('user.attributes'.$name);
    }

    /**
     * Set a user attribute
     *
     * @param   string $name  The attribute name.
     * @param   mixed  $value The attribute value.
     * @return  void
     */
    public function __set($name, $value)
    {
        $this->getSession()->set('user.attributes'.$name, $value);
    }

    /**
     * Test existence of a use attribute
     *
     * @param  string $name The attribute name.
     * @return boolean
     */
    public function __isset($name)
    {
        return $this->getSession()->has('user.attributes'.$name);
    }

    /**
     * Unset a user attribute
     *
     * @param   string $key  The attribute name.
     * @return  void
     */
    public function __unset($name)
    {
        $this->getSession()->remove('user.attributes'.$name);
    }
}