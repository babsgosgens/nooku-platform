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
 * Abstract Local Adapter
 *
 * @author  Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package Nooku\Component\Searches
 */
class AdapterEngineSolr extends Library\Object implements AdapterEngineInterface
{
    protected  $_client;

    /**
     * @param ObjectConfig $config
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Setup the component locator
        $this->_client = new Solarium\Client(array(
            'endpoint' => array(
                'localhost' => array(
                    'host' => $config->get('searchengine')->url,
                    'port' => $config->get('searchengine')->port,
                    'path' => '/'.$config->get('searchengine')->instance.'/',
                    'core' => $config->get('searchengine')->core,
                )
            )
        ));
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
     * Retrives results from solr and returns them as an array;
     *
     * @param Library\ModelState $state
     * @return array
     */
    public function getRowset(Library\ModelState $state)
    {
        $rowset = array();

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
                $rowset['total'] = $results->getNumFound();

                $facets = $results->getFacetSet()->getFacet($state->distinct);
                $i=1;
                foreach($facets as $key=>$count)
                {
                    $rowset['items'][] = array('id'=>$i++, 'field'=> $key, 'count'=>$count);
                }

            } else {
                $rowset['total'] = $results->getNumFound();

                foreach($results->getDocuments() as $doc)
                {
                    $rowset['items'][] = $doc->getFields();
                }
            }
        }
        return $rowset;
    }

    /**
     * Returns 1 result from solr and returns this as an array
     *
     * @param Library\ModelState $state
     * @return array
     */
    public function getRow(Library\ModelState $state)
    {
        $row = array();

        if($this->isConnected())
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
                $row = $doc->getFields();
            }
        }

        return $row;
    }

    /**
     * Save's a result to solr
     *
     * @param Library\CommandContext $context
     * @return bool
     */
    public function save(Library\CommandContext $context)
    {
        // TODO: Need to implement this later..
        return (bool) true;
    }

    /**
     * Delete an record from solr.
     *
     * @param Library\CommandContext $context
     * @return bool
     */
    public function delete(Library\CommandContext $context)
    {
        // get an update query instance
        $update = $this->_solarium->createUpdate();

        // add the delete id and a commit command to the update query
        $update->addDeleteById($context->table."_".$this->id);
        $update->addCommit();

        $this->_solarium->update($update);

        return (bool) true;
    }
}