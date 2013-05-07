<?php
/**
 * @package     Koowa_Template
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

 /**
  * Object Stack Class
  * 
  * Implements a simple stack collection (LIFO) 
  * 
  * @author     Johan Janssens <johan@nooku.org>
  * @package    Koowa_Object
  */
class ObjectStack extends Object implements \Countable
{ 
    /**
     * The object container
     *
     * @var array
     */
    protected $_object_stack = null;
    
    /**
     * Constructor
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return ObjectStack
     */
    public function __construct(ObjectConfig $config)
    { 
        parent::__construct($config);
        
        $this->_object_stack = array();
    }
    
    /**
     * Peeks at the element from the end of the stack
     *
     * @return mixed The value of the top element
     */
    public function top()
    {
        return end($this->_object_stack);
    }
      
    /**
     * Pushes an element at the end of the stack
     *
     * @param  Object $object
     * @throws \InvalidArgumentException if the object doesn't extend from Object
     * @return ObjectStack
     */
    public function push($object)
    {
        if(!$object instanceof ObjectInterface) {
            throw new \InvalidArgumentException('Object needs to extend from ObjectInterface');
        }

        $this->_object_stack[] = $object;
        return $this;
    }
    
    /**
     * Pops an element from the end of the stack
     *
     * @return  mixed The value of the popped element
     */
    public function pop()
    {
        return array_pop($this->_object_stack);
    } 
    
	/**
     * Counts the number of elements
     * 
     * @return integer	The number of elements
     */
    public function count()
    {
        return count($this->_object_stack);
    }

    /**
     * Check to see if the registry is empty
     * 
     * @return boolean	Return TRUE if the registry is empty, otherwise FALSE
     */
    public function isEmpty()
    {
        return empty($this->_object_stack);
    }  
}