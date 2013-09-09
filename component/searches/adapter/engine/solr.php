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

//require JPATH_VENDOR.'/autoload.php';

use Nooku\Library\ObjectIdentifierInterface;
use Nooku\Library\ObjectManagerInterface;
use Solarium;
/**
 * Abstract Local Adapter
 *
 * @author   Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class AdapterEngineSolr extends Library\Object implements AdapterEngineInterface
{
    protected  $_client;

    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);
        //Setup the component locator
        $solr = array(
            'endpoint' => array(
                'localhost' => array(
                    'host' => $config->get('searchengine')->url,
                    'port' => $config->get('searchengine')->port,
                    'path' => '/'.$config->get('searchengine')->instance.'/',
                    'core' => $config->get('searchengine')->core,
                )
            )
        );

        $this->_client = new Solarium\Client($solr);

    }

    /**
     * Check if there is a connection with the solr server.
     * @return bool
     */
    public function isConnected(){

        $result = $this->_client->ping($this->_client->createPing());

        if($result->getResponse()->getStatusCode() == 200){
            return (bool) true;
        }
        return (bool) false;
    }
    public function getRowset(Library\ModelState $state)
    {

        $rowset = array();
        if($this->isConnected()){

            $query = $this->_client->createSelect();
            $query->setQueryDefaultOperator('AND');
            if($state->search){

                $query->setQuery("*".str_ireplace(" ","* *",$state->search)."*");
            }

            if(is_string($state->identifier)){

                $query->createFilterQuery('identifier')
                    ->setQuery('identifier:'.$state->identifier);
            }

            if($state->sort){
                $query->addSort($state->sort,$state->direction);
            }
            $query->setStart($state->offset);
            $query->setRows($state->limit);

            $results =$this->_client->select($query);
            $rowset['total'] = $results->getNumFound();

            foreach($results->getDocuments() as $doc){
                $rowset['items'][] = $doc->getFields();
            }

        }

        return $rowset;

    }
    public function getRow(Library\ModelState $state){
        $row = array();
        if($this->isConnected()){

            $query = $this->_client->createSelect();
            $query->setQueryDefaultOperator('AND');

            if(is_numeric($state->id)){

                $query->createFilterQuery('id')
                    ->setQuery('id:'.$state->id);
            }
            $results =$this->_client->select($query);


            foreach($results->getDocuments() as $doc){
                $row = $doc->getFields();
            }

        }

        return $row;
    }
    public function save(Library\CommandContext $context){

    }

    /**
     *
     */
    public function delete(Library\CommandContext $context)
    {
        // get an update query instance
        $update = $this->_solarium->createUpdate();

        // add the delete id and a commit command to the update query
        $update->addDeleteById($context->table."_".$this->id);
        $update->addCommit();

        $this->_solarium->update($update);
    }
    public function toArray(){

    }
}