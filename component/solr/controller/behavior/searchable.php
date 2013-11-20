<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Solr;

require JPATH_VENDOR.'/autoload.php';

use Nooku\Library;
use Nooku\Library\ObjectConfig;
use Solarium;

/**
 * Searchable Database Behavior
 *
 * @author  Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package Nooku\Component\Solr
 */
class ControllerBehaviorSearchable extends Library\ControllerBehaviorAbstract
{
    protected  $_client;

    protected function _initialize(ObjectConfig $config)
    {

        $application = $this->getObject('application');
        $this->_client = new Solarium\Client(array(
            'endpoint' => array(
                'localhost' => array(
                    'host' => $application->getCfg('solr_host'),
                    'port' => $application->getCfg('solr_port'),
                    'path' => '/'.$application->getCfg('solr_path').'/',
                    'core' => $application->getCfg('solr_core'),
                )
            )
        ));


        parent::_initialize($config);
    }

    /**
     * After table insert
     *
     * Add a new record to the solr db for indexing.
     *
     * @param   Library\CommandContext $context
     * @return  void
     */
    protected function _afterControllerAdd(Library\CommandContext $context)
    {
        $this->addToSearchEngine($context);
    }

    /**
     * After table update
     *
     * Update a the solr db for indexing.
     *
     * @param   Library\CommandContext $context
     * @return  void
     */
    protected function _afterControllerEdit(Library\CommandContext $context)
    {
        $this->addToSearchEngine($context);
    }

    protected function _afterControllerDelete(Library\CommandContext $context)
    {
        // get an update query instance
        $update = $this->_client->createUpdate();

        // add the delete id and a commit command to the update query
        $update->addDeleteById($context->table."_".$this->id);
        $update->addCommit();

        $this->_solarium->update($update);
    }

    protected  function addToSearchEngine(Library\CommandContext $context)
    {
        $schema = json_decode(file_get_contents($this->_solarium->getEndpoint()->getBaseUri().'admin/luke?show=schema&wt=json'));
        $update = $this->_client->createUpdate();
        $doc    = $update->createDocument();

        $id = $context->result->get('id');

        foreach($schema->schema->fields as $key => $value)
        {
            if($context->result->get($key) !== "" && $value->type != 'date')
            {
                $doc->{$key} = $context->result->get($key);
            }
            elseif($context->result->get($key) !== "" && $value->type == 'date')
            {
                $doc->{$key} = date('Y-m-d\TH:i:s\Z', strtotime($context->result->get($key)));
            }
            else
            {
                $field = 'attr_'.$key;
                $doc->{$field} = $context->result->get($key);
            }
        }

        $identifier = $context->getSubject()->getIdentifier();

        $doc->id                    = $identifier->identifier."?id=".$id;
        $doc->identifier            = $identifier->identifier;
        $doc->identifier_type       = $identifier->type;
        $doc->identifier_package    = $identifier->package;
        $doc->identifier_name       = $identifier->name;
        $doc->identifier_query      = "&id=".$id;


        if(is_numeric($context->result->get('categories_category_id')))
        {
            $category = $this->getObject('com:categories.model.categories')
                ->id($context->result->get('categories_category_id'))
                ->table($identifier->package)
                ->getRow();

            if(!empty($category->title))
            {
                $doc->category = $category->title;
            }
        }

        if($context->result->get('created_by'))
        {
            $doc->author = $this->getObject('com:users.model.users')->id($context->result->get('created_by'))->getRow()->get('name');
        }

        $update->addDocuments(array($doc));
        $update->addCommit();

        $this->_client->update($update);
    }
}