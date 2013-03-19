<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Languages;

use Nooku\Framework;

/**
 * Tables Model
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Languages
 */
class ModelTables extends Framework\ModelTable
{
    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('enabled', 'boolean')
            ->insert('component', 'int');
    }
    
    protected function _buildQueryColumns(Framework\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);
        
        $query->columns(array('component_name' => 'components.name'));
    }
    
    protected function _buildQueryJoins(Framework\DatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);
        
        $query->join(array('components' => 'extensions_components'), 'components.extensions_component_id = tbl.extensions_component_id');
    }

    protected function _buildQueryWhere(Framework\DatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();

        if(!$state->isUnique())
        {
            if(!is_null($state->enabled)) {
                $query->where('tbl.enabled = :enabled')->bind(array('enabled' => (int) $state->enabled));
            }

            if($state->component) {
                $query->where('tbl.extensions_component_id IN :component')->bind(array('component' => (array) $state->component));
            }
        }
    }
}