<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Contacts;

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
        $context->result->set('title', $context->result->get('name'));
        $context->result->set('text', $context->result->get('misc'));
        parent::_afterControllerAdd($context);

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
        $context->result->set('title', $context->result->get('name'));
        $context->result->set('text', $context->result->get('misc'));
        parent::_afterControllerEdit($context);
    }
}