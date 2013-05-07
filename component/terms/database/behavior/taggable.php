<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Terms;

use Nooku\Library;

/**
 * Taggable Database Behavior
 *   
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Terms
 */
class DatabaseBehaviorTaggable extends Library\DatabaseBehaviorAbstract
{
	/**
	 * Get a list of tags
	 * 
	 * @return RowsetTerms
	 */
	public function getTerms()
	{
        $model = $this->getObject('com:terms.model.relations');

        if(!$this->isNew())
        {
            $tags = $model->row($this->id)
                ->table($this->getTable()->getName())
                ->getRowset();
        }
        else $tags = $model->getRowset();

        return $tags;
	}
        
    /**
	 * Modify the select query
	 * 
	 * If the query's where information includes a tag propery, auto-join the terms tables with the query and select
     * all the rows that are tagged with the term.
	 */
	protected function _beforeTableSelect(Library\CommandContext $context)
	{
		$query = $context->query;
		
		if(!is_null($query)) 
		{
            foreach($query->where as $key => $where) 
			{	
                if($where['condition'] == 'tbl.tag') 
                {
                    $table = $context->caller;
                                        
					$query->where('terms_terms.slug'     , $where['constraint'],  $where['value']);
					$query->where('terms_relations.table','=', $table->getName());
					$query->join('LEFT', 'terms_relations AS terms_relations', 'terms_relations.row       = tbl.'.$table->getIdentityColumn());
					$query->join('LEFT', 'terms_terms     AS terms_terms',     'terms_terms.terms_term_id = terms_relations.terms_term_id');
				
					unset($context->query->where[$key]);
				}
			}
		}
	}
}