<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Searches;

require JPATH_VENDOR.'/autoload.php';

use Nooku\Library;
use Nooku\Library\CommandChain;
use Nooku\Library\ObjectConfig;
use Nooku\Library\ObjectIdentifierInterface;
use Nooku\Library\ObjectManagerInterface;
use Solarium;

/**
 * Searchable Database Behavior
 *
 * @author  Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package Nooku\Component\Revisions
 */
class ControllerBehaviorSearchable extends Library\ControllerBehaviorAbstract
{
    protected  $_solarium;

    protected function _initialize(ObjectConfig $config)
    {
        $parameters = $this->getObject('application.extensions')->searches->params;

        $solr = array(
            'endpoint' => array(
                'localhost' => array(
                    'host' => $parameters->get('url'),
                    'port' => $parameters->get('port'),
                    'path' => '/'.$parameters->get('instance').'/',
                    'core' => $parameters->get('core'),
                )
            )
        );

        $this->_solarium = new Solarium\Client($solr);

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

    protected function _afterControllerDelete(Library\CommandContext $context){

        // get an update query instance
        $update = $this->_solarium->createUpdate();

        // add the delete id and a commit command to the update query
        $update->addDeleteById($context->table."_".$this->id);
        $update->addCommit();

        $this->_solarium->update($update);
    }


    protected  function addToSearchEngine(Library\CommandContext $context){

        $schema = json_decode(file_get_contents($this->_solarium->getEndpoint()->getBaseUri().'admin/luke?show=schema&wt=json'));
        $update = $this->_solarium->createUpdate();
        $doc = $update->createDocument();

        $id = $context->result->get('id');



        foreach($schema->schema->fields as $key=>$value){

            if($context->result->get($key) !== "" && $value->type != 'date'){
                $doc->{$key} = $context->result->get($key);
            }elseif($context->result->get($key) !== "" && $value->type == 'date'){
                $doc->{$key} = date('Y-m-d\TH:i:s\Z',strtotime($context->result->get($key)));
            }else{
                $field = 'attr_'.$key;
                $doc->{$field} = $context->result->get($key);
            }

        }
        $identifier = $context->getSubject()->getIdentifier();

        $doc->id = $identifier->name."_".$id;
        $doc->identifier = $identifier->package;
        $doc->identifier_id = $id;


        if(is_numeric($context->result->get('categories_category_id'))){
            $category = $this->getObject('com:categories.model.categories')
                ->id($context->result->get('categories_category_id'))
                ->table($identifier->package)
                ->getRow();
            if(!empty($category->title)){
                $doc->category = $category->title;
            }
        }

        /**
         * Getting all the tags if this is taggable
         */

        $tags = array();
        if(is_array($context->result->get('tags'))){
            foreach($context->result->get('tags') as $tag){
                $tags[] = $this->getObject('com:tags.model.tags')->id($tag)->table($identifier->name)->getRow()->get('title');
            }
            $doc->tags = $tags;
        }

        if($context->result->get('created_by')){
            $doc->author = $this->getObject('com:users.model.users')->id($context->result->get('created_by'))->getRow()->get('name');
        }


        if($this->isAttachable()){
            // Need to think how i should do this..
            $tmp = array();
            $attatchments = $this->getObject('com:attachments.model.attachments')->row($id)->table($identifier->name)->getRowset();
            foreach($attatchments as $attatchment){
                if(!$attatchment->file->isImage()){
                    //base64_encode
                    $tmp[] = file_get_contents($attatchment->file->fullpath);
                }
                $doc->attachments = $tmp;
            }        }


        $update->addDocuments(array($doc));

        $update->addCommit();
        $this->_solarium->update($update);


    }


}