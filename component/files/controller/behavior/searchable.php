<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Files;

use Nooku\Library;

use Nooku\Component\Searches;
/**
 * Searchable Database Behavior
 *
 * @author  Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package Nooku\Component\Revisions
 */
class ControllerBehaviorSearchable extends Searches\ControllerBehaviorSearchable
{
    protected  function addToSearchEngine(Library\CommandContext $context){


        $query = $this->_solarium->createExtract();
        $doc = $query->createDocument();

        $table = 'files';

        $file = $this->getModel()->getRow();


        $doc->id = $file->filename;
        $doc->identifier = $table;


        $query = $this->_solarium->createExtract();
        $query->addFieldMapping('content', 'fulltext');
        $query->setUprefix('attr_');
        $query->setFile($file->fullpath);
        $query->setCommit(true);
        $query->setOmitHeader(false);

        $query->setDocument($doc);

        //Save the Document to Solr
        $this->_solarium->extract($query);


    }
}