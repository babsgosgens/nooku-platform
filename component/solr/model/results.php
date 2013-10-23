<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Solr;

use Nooku\Library;
use Nooku\Library\ObjectConfig;
use Solarium;
/**
 *  Searches Model
 *
 * @author  Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package Nooku\Component\Solr
 */

class ModelResults extends Library\ModelAbstract
{
    protected  $_client;
    /**
     * @param ObjectConfig $config
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('limit'        , 'int')
            ->insert('offset'       , 'int')
            ->insert('sort'         , 'cmd')
            ->insert('direction'    , 'word', 'asc')
            ->insert('search'       , 'string')
            ->insert('identifier'   , 'string')
            ->insert('package'      , 'string')
            ->insert('distinct'     , 'string')
            ->insert('id'           , 'int', null, true);
    }

    /**
     * @param ObjectConfig $config
     */
    protected function _initialize(ObjectConfig $config)
    {
        /**
         * Getting the Search Engine params
         */
        $parameters = $this->getObject('application.extensions')->solr->params;
        //Setup the component locator
        $this->_client = new Solarium\Client(array(
            'endpoint' => array(
                'localhost' => array(
                    'host' => $parameters->get('url'),
                    'port' => $parameters->get('port'),
                    'path' => '/'.$parameters->get('instance').'/',
                    'core' => $parameters->get('core'),
                )
            )
        ));

        parent::_initialize($config); // TODO: Change the autogenerated stub
    }

    /**
     * Check if there is a connection with the solr server.
     * @return bool
     */
    public function isConnected()
    {
        $result = $this->_client->ping($this->_client->createPing());

        if($result->getResponse()->getStatusCode() == 200)
        {
            return (bool) true;
        }

        return (bool) false;
    }
    /**
     *
     * Get a row from the selected search engine
     *
     * @return Library\DatabaseRowInterface|Library\ObjectInterface|object
     */
    public function getRow()
    {
        $row = array();
        if(!isset($this->_row))
        {
            if($this->isConnected())
            {
                $query = null;
                $state = $this->getState();

                if($state->isUnique())
                {
                    $query = $this->_client->createSelect();
                    $query->setQueryDefaultOperator('AND');

                    if(is_numeric($state->id))
                    {
                        $query->createFilterQuery('id')
                            ->setQuery('id:'.$state->id);
                    }

                    $results = $this->_client->select($query);

                    foreach($results->getDocuments() as $doc)
                    {
                        $this->_row =  $this->getObject('com:solr.database.row.search', array('data' => $doc));
                    }
                }
            }
        }

        return $this->_row;
    }

    /**
     *
     * Get a list of items which represents a  search engine rowset
     *
     * @return Library\DatabaseRowsetInterface|Library\ObjectInterface|object
     */
    public function getRowset()
    {
        $rowset = array();
        $config = $this->getConfig();

        // Get the data if it doesn't already exist
        if (!isset($this->_rowset))
        {
            $query = null;
            $state = $this->getState();

            if(!$state->isEmpty())
            {
                if($this->isConnected())
                {
                    $query = $this->_client->createSelect();

                    if(is_string($state->distinct))
                    {
                        // get the facetset component
                        $facetSet = $query->getFacetSet();

                        // create a facet field instance and set options
                        $facetSet->createFacetField($state->distinct)->setField($state->distinct);

                    } else {
                        $query->setQueryDefaultOperator('AND');
                        if($state->search)
                        {
                            $query->setQuery("*".str_ireplace(" ", "* *", $state->search)."*");
                        }

                        if(is_string($state->identifier))
                        {
                            $query->createFilterQuery('identifier')
                                ->setQuery('identifier:'.$state->identifier);
                        }
                        if(is_string($state->package))
                        {
                            $query->createFilterQuery('package')
                                ->setQuery('identifier_package:'.$state->package);
                        }

                        if($state->sort)
                        {
                            $query->addSort($state->sort, $state->direction);
                        }

                        $query->setStart($state->offset);
                        $query->setRows($state->limit);
                    }

                    $results = $this->_client->select($query);

                    if(is_string($state->distinct))
                    {
                        $this->_total = $results->getNumFound();

                        $facets = $results->getFacetSet()->getFacet($state->distinct);
                        $i=1;
                        foreach($facets as $key=>$count)
                        {
                            $rowset[] = array('id'=>$i++, 'field'=> $key, 'count'=>$count);
                        }

                    } else {
                        $this->_total =$results->getNumFound();

                        foreach($results->getDocuments() as $doc)
                        {
                            $rowset[] = $doc->getFields();
                        }
                    }

                    if($this->_total > 0)
                    {
                        $this->_rowset = $this->getObject('com:solr.database.rowset.results', array('data' => $rowset, 'state' => $state));
                    }
                    else
                    {
                        $this->_rowset = $this->getObject('com:solr.database.rowset.results', array('state' => $state));
                    }
                }
                else $this->_rowset = $this->getObject('com:solr.database.rowset.results' , array('state' => $state));
            }
        }
        return $this->_rowset;
    }

    /**
     *
     * @return int
     */
    public function getTotal()
    {
        return parent::getTotal(); // TODO: Change the autogenerated stub
    }
}