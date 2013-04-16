<?php
/**
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Library;

/**
 * Tidy filter.
 *
 * This filter will correct and escape a HTML fragment. It will also cleanup HTML generated by Microsoft Office
 * products.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Filter
 * @see         http://tidy.sourceforge.net/docs/quickref.html
 */
class FilterTidy extends FilterAbstract implements FilterTraversable
{
    /**
     * A tidy object
     *
     * @var object
     */
    protected $_tidy =  null;

    /**
     * The input/output encoding
     *
     * @var string
     */
    protected $_encoding;

    /**
     * The tidy configuration
     *
     * @var array
     */
    protected $_options;

    /**
     * Constructor
     *
     * @param  object  An optional Config object with configuration options
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);

        $this->_encoding = $config->encoding;
        $this->_options  = Config::unbox($config->options);
    }

 	/**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional Config object with configuration options
     * @return  void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'encoding'      => 'utf8',
            'options'       =>  array(
                	'clean'                       => true,
                	'drop-proprietary-attributes' => true,
            		'output-html'                 => true,
            		'show-body-only'              => true,
            		'bare'                        => true,
            		'wrap'                        => 0,
            		'word-2000'                   => true,
                )
            ));

        parent::_initialize($config);
    }

    /**
     * Validate a variable
     *
     * @param   scalar  $value Value to be validated
     * @return  bool    True when the variable is valid
     */
    public function validate($value)
    {
        return (is_string($value));
    }

    /**
     * Sanitize a variable
     *
     * @param   scalar  $value Value to be sanitized
     * @return  string
     */
    public function sanitize($value)
    {
        //Tidy is not installed, return the input
        if($tidy = $this->getTidy($value))
        {
            if($tidy->cleanRepair()) {
               $value = (string) $tidy;
            }
        }

        return $value;
    }

    /**
     * Gets a Tidy object
     *
     * @param string    The data to be parsed.
     */
    public function getTidy($string)
    {
        if(class_exists('Tidy'))
        {
            if (!$this->_tidy) {
                $this->_tidy = new \Tidy();
            }

            $this->_tidy->parseString($string, $this->_options, $this->_encoding);
        }

        return $this->_tidy;
    }

}