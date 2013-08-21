<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Searches;

use Nooku\Library;
use Nooku\Library\ObjectConfig;

/**
 *  Searches Model
 *
 * @author  Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package Nooku\Component\Searches
 */

class ModelSearches extends Library\ModelAbstract
{
    public function __construct(ObjectConfig $config)
    {

        parent::__construct($config);


        $this->getState()
            ->insert('limit'    , 'int')
            ->insert('offset'   , 'int')
            ->insert('sort'     , 'cmd')
            ->insert('direction', 'word', 'asc')
            ->insert('search'   , 'string')
            ->insert('identifier', 'string')
            ->insert('id','int',null,true);

    }

    protected function _initialize(ObjectConfig $config)
    {

        $parameters = $this->getObject('application.extensions')->searches->params;
        $config->append(array(
            'searchengine' =>
            array(
                'adapter' => $parameters->get('adapter','solr'),
                'url'   => $parameters->get('url'),
                'port'  => $parameters->get('port'),
                'instance'  => $parameters->get('instance'),
                'core'  => $parameters->get('core'),
                'username'  => $parameters->get('username',''),
                'password'  => $parameters->get('password','')
            )
        ));

        parent::_initialize($config); // TODO: Change the autogenerated stub
    }


    public function getRow()
    {

        $config = $this->getConfig();
        if(!isset($this->_row))
        {
            $query = null;
            $state = $this->getState();

            if($state->isUnique())
            {
                $result = $this->getObject('com:searches.adapter.engine.'.$config->get('searchengine')->adapter,$config->toArray())->getRow($state);
                $row = $this->getObject('com:searches.database.row.search',array('data' => $result));

            }
            $this->_row = $row;

        }

        return $this->_row;
    }

    /**
     * Get a list of items which represents a  table rowset
     *
     * @return DatabaseRowsetInterface
     */
    public function getRowset()
    {

        $config = $this->getConfig();

        // Get the data if it doesn't already exist
        if (!isset($this->_rowset))
        {
            $query = null;
            $state = $this->getState();


            if(!$state->isEmpty())
            {
                $results = $this->getObject('com:searches.adapter.engine.'.$config->get('searchengine')->adapter,$config->toArray())->getRowset($state);

                $this->_total = $results['total'];
                $this->_rowset = $this->getObject('com:searches.database.rowset.searches', array('data'=>$results['items']));

            }
            else $this->_rowset = $this->getTable()->getRowset(array('state' => $state));
        }

        return $this->_rowset;
    }

    public function getTotal()
    {
        return parent::getTotal(); // TODO: Change the autogenerated stub
    }

    protected function _buildQueryWhere($query)
    {
        $state = $this->getState();
        if($state->search){

            $query->setQuery($state->search);
        }

        if(is_string($state->identifier)){

            $query->createFilterQuery('identifier')
                ->setQuery('identifier:'.$state->identifier);
        }
    }

    /**
     * @param $query
     */
    protected function _buildQueryOrder($query)
    {
        $state = $this->getState();
        if($state->sort){
            $query->addSort($state->sort, $state->direction);
        }
    }

    /**
     * @param $query
     */
    protected function _buildQueryLimit($query)
    {
        $query->setStart($this->getState()->offset)->setRows($this->getState()->limit);
    }
}