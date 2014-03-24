<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Model Entity Collection
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Model
 */
class ModelEntityCollection extends ObjectSet implements ModelEntityInterface, ModelEntityTraversable
{
    /**
     * Name of the identity column in the collection
     *
     * @var    string
     */
    protected $_identity_column;

    /**
     * Clone entity object when adding data
     *
     * @var    boolean
     */
    protected $_row_cloning;

    /**
     * Constructor
     *
     * @param ObjectConfig  $config  An optional ObjectConfig object with configuration options
     * @return ModelEntityCollection
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_row_cloning = $config->row_cloning;

        // Set the table identifier
        if (isset($config->identity_column)) {
            $this->_identity_column = $config->identity_column;
        }

        // Reset the collection
        $this->reset();

        // Insert the data, if exists
        if (!empty($config->data)) {
            $this->addEntity($config->data->toArray(), $config->status);
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $object An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'data'            => null,
            'identity_column' => null,
            'row_cloning'     => true
        ));

        parent::_initialize($config);
    }

    /**
     * Returns a ModelEntityCollection
     *
     * This functions accepts either a know position or associative array of key/value pairs
     *
     * @param   string|array  $needle The position or the key or an associative array of column data to match
     * @return  ModelEntityCollection Returns a collection if successful. Otherwise NULL.
     */
    public function find($needle)
    {
        $result = null;

        if(is_array($needle))
        {
            $result = clone $this;

            foreach($this as $entity)
            {
                foreach($needle as $key => $value)
                {
                    if(!in_array($entity->{$key}, (array) $value)) {
                        $result->extract($entity);
                    }
                }
            }
        }

        if(is_scalar($needle) && isset($this->_data[$needle])) {
            $result = $this->_data[$needle];
        }

        return $result;
    }

    /**
     * Store all entities in the collection to the data store
     *
     * @return boolean  If successful return TRUE, otherwise FALSE
     */
    public function save()
    {
        $result = false;

        if (count($this))
        {
            $result = true;

            foreach ($this as $i => $entity)
            {
                if (!$entity->save())
                {
                    // Set current entity status message as collection status message.
                    $this->setStatusMessage($entity->getStatusMessage());
                    $result = false;
                }
            }
        }

        return $result;
    }

    /**
     * Remove all entities in the collection from the data store
     *
     * @return bool  If successful return TRUE, otherwise FALSE
     */
    public function delete()
    {
        $result = false;

        if (count($this))
        {
            $result = true;

            foreach ($this as $i => $entity)
            {
                if (!$entity->delete())
                {
                    // Set current entity status message as collection status message.
                    $this->setStatusMessage($entity->getStatusMessage());
                    $result = false;
                }
            }
        }

        return $result;
    }

    /**
     * Reset the collection
     *
     * @return  ModelEntityCollection
     */
    public function reset()
    {
        $this->_data = array();
        return $this;
    }

    /**
     * Checks if the current entity is new or not
     *
     * @return boolean
     */
    public function isNew()
    {
        $result = true;
        if($entity = $this->getIterator()->current()) {
            $result = $entity->isNew();
        }

        return $result;
    }

    /**
     * Check if a the current entity or specific entity property has been modified.
     *
     * If a specific property name is giving method will return TRUE only if this property was modified.
     *
     * @param   string $property The property name
     * @return  boolean
     */
    public function isModified($property = null)
    {
        $result = false;
        if($entity = $this->getIterator()->current()) {
            $result = $entity->isModified($property);
        }

        return $result;
    }

    /**
     * Test the connected status of the collection.
     *
     * @return  bool Returns TRUE by default.
     */
    public function isConnected()
    {
        return true;
    }

    /**
     * Get a property
     *
     * @param   string  $property The property name.
     * @return  mixed
     */
    public function get($property)
    {
        $result = null;
        if($entity = $this->getIterator()->current()) {
            $result = $entity->get($property);
        }

        return $result;
    }

    /**
     * Set a property
     *
     * @param   string  $property   The property name.
     * @param   mixed   $value      The property value.
     * @param   boolean $modified   If TRUE, update the modified information for the property
     * @return  ModelEntityCollection
     */
    public function set($property, $value, $modified = true)
    {
        if($entity = $this->getIterator()->current()) {
            $entity->set($property, $value, $modified);
        }

        return $this;
    }

    /**
     * Test existence of a property
     *
     * @param  string  $property The property name.
     * @return boolean
     */
    public function has($property)
    {
        $result = false;
        if($entity = $this->getIterator()->current()) {
            $result = $entity->has($property);
        }

        return $result;
    }

    /**
     * Remove a property
     *
     * @param   string  $property The property name.
     * @return  ModelEntityCollection
     */
    public function remove($property)
    {
        if($entity = $this->getIterator()->current()) {
            $entity->remove($property);
        }

        return $this;
    }

    /**
     * Get the properties
     *
     * @param   boolean  $modified If TRUE, only return the modified data.
     * @return  array   An associative array of the entity properties
     */
    public function getProperties($modified = false)
    {
        $result = array();

        if($entity = $this->getIterator()->current()) {
            $result = $entity->getProperties($modified);
        }

        return $result;
    }

    /**
     * Set the properties
     *
     * @param   mixed   $data        Either and associative array, an object or a ModelEntityInterface
     * @param   boolean $modified If TRUE, update the modified information for each column being set.
     * @return  ModelEntityCollection
     */
    public function setProperties($properties, $modified = true)
    {
        //Prevent changing the identity column
        if (isset($this->_identity_column)) {
            unset($properties[$this->_identity_column]);
        }

        if($entity = $this->getIterator()->current()) {
            $entity->setProperties($properties, $modified);
        }

        return $this;
    }

    /**
     * Add entities to the collection
     *
     * This function will either clone the entity object, or create a new instance of the entity object for each entity
     * being inserted. By default the entity will be cloned.
     *
     * @param  array   $properties  An associative array of entity properties to be inserted.
     * @param  string  $status  The entities(s) status
     *
     * @return  ModelEntityCollection
     * @see __construct
     */
    public function addEntity(array $properties, $status = NULL)
    {
        if ($this->_row_cloning)
        {
            $prototype = $this->createEntity()->setStatus($status);

            foreach ($properties as $k => $data)
            {
                $entity = clone $prototype;
                $entity->setProperties($data, $entity->isNew());

                $this->insert($entity);
            }
        }
        else
        {
            foreach ($properties as $k => $data)
            {
                $entity = $this->createEntity()->setStatus($status);
                $entity->setProperties($data, $entity->isNew());

                $this->insert($entity);
            }
        }

        return $this;
    }

    /**
     * Get an instance of a entity object for this collection
     *
     * @param   array $options An optional associative array of configuration settings.
     * @return  ModelEntityCollection
     */
    public function createEntity(array $options = array())
    {
        $identifier = $this->getIdentifier()->toArray();
        $identifier['path'] = array('model', 'entity');
        $identifier['name'] = StringInflector::singularize($this->getIdentifier()->name);

        //The entity default options
        $options['identity_column'] = $this->getIdentityColumn();

        return $this->getObject($identifier, $options);
    }

    /**
     * Returns the status
     *
     * @return string The status
     */
    public function getStatus()
    {
        $status = null;

        if($entity = $this->getIterator()->current()) {
            $status = $entity->getStatus();
        }

        return $status;
    }

    /**
     * Set the status
     *
     * @param   string|null  $status The status value or NULL to reset the status
     * @return  ModelEntityCollection
     */
    public function setStatus($status)
    {
        if($entity = $this->getIterator()->current()) {
            $entity->setStatusMessage($status);
        }

        return $this;
    }

    /**
     * Returns the status message
     *
     * @return string The status message
     */
    public function getStatusMessage()
    {
        $message = false;

        if($entity = $this->getIterator()->current()) {
            $message = $entity->getStatusMessage($message);
        }

        return $message;
    }

    /**
     * Set the status message
     *
     * @param   string $message The status message
     * @return  ModelEntityCollection
     */
    public function setStatusMessage($message)
    {
        if($entity = $this->getIterator()->current()) {
            $entity->setStatusMessage($message);
        }

        return $this;
    }

    /**
     * Gets the identity key
     *
     * @return string
     */
    public function getIdentityColumn()
    {
        return $this->_identity_column;
    }

    /**
     * Return an associative array of the data.
     *
     * @return array
     */
    public function toArray()
    {
        $result = array();
        foreach ($this as $key => $entity) {
            $result[$key] = $entity->toArray();
        }
        return $result;
    }

    /**
     * Insert an entity into the collection
     *
     * The entity will be stored by it's identity_column if set or otherwise by it's object handle.
     *
     * @param  ModelEntityInterface $entity
     * @return boolean    TRUE on success FALSE on failure
     * @throws \InvalidArgumentException if the object doesn't implement ModelEntity
     */
    public function insert(ObjectHandlable $entity)
    {
        if (!$entity instanceof ModelEntityInterface) {
            throw new \InvalidArgumentException('Entity needs to implement ModelEntityInterface');
        }

        $this->offsetSet($entity);

        return true;
    }

    /**
     * Removes an entity from the collection
     *
     * The entity will be removed based on it's identity_column if set or otherwise by it's object handle.
     *
     * @param  ModelEntityInterface $entity
     * @return ModelEntityCollection
     * @throws \InvalidArgumentException if the object doesn't implement ModelEntityInterface
     */
    public function extract(ObjectHandlable $entity)
    {
        if (!$entity instanceof ModelEntityInterface) {
            throw new \InvalidArgumentException('Entity needs to implement ModelEntityInterface');
        }

        if ($this->offsetExists($entity)) {
            $this->offsetUnset($entity);
        }

        return $this;
    }

    /**
     * Get a property
     *
     * @param   string  $property The property name.
     * @return  mixed
     */
    public function __get($property)
    {
        return $this->get($property);
    }

    /**
     * Set a property
     *
     * @param   string  $property   The property name.
     * @param   mixed   $value      The property value.
     * @return  void
     */
    public function __set($property, $value)
    {
        $this->set($property, $value);
    }

    /**
     * Test existence of a property
     *
     * @param  string  $property The property name.
     * @return boolean
     */
    public function __isset($property)
    {
        return $this->has($property);
    }

    /**
     * Remove a property
     *
     * @param   string  $property The property name.
     * @return  ModelEntityCollection
     */
    public function __unset($property)
    {
        $this->remove($property);
    }

    /**
     * Forward the call to the current entity
     *
     * @param  string   $method    The function name
     * @param  array    $arguments The function arguments
     * @throws \BadMethodCallException   If method could not be found
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        $result = null;

        if($entity = $this->getIterator()->current())
        {
            // Call_user_func_array is ~3 times slower than direct method calls.
            switch (count($arguments))
            {
                case 0 :
                    $result = $entity->$method();
                    break;
                case 1 :
                    $result = $entity->$method($arguments[0]);
                    break;
                case 2 :
                    $result = $entity->$method($arguments[0], $arguments[1]);
                    break;
                case 3 :
                    $result = $entity->$method($arguments[0], $arguments[1], $arguments[2]);
                    break;
                default:
                    // Resort to using call_user_func_array for many segments
                    $result = call_user_func_array(array($entity, $method), $arguments);
            }
        }

        return $result;
    }
}