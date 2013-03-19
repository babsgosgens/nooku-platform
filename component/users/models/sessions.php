<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Users;

use Nooku\Framework;

/**
 * Sessions Model
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Users
 */
class ModelSessions extends Framework\ModelTable
{
    /**
     * Constructor.
     *
     * @param   Config  An optional Framework\Config object with configuration options.
     */
    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('application', 'word')
            ->insert('email'      , 'email');

        //@TODO : Add special session id filter
        $this->getState()
            ->insert('id', 'string', null, true);
    }

    /**
     * Builds SELECT columns list for the query.
     *
     * @param   Framework\DatabaseQuerySelect  A query object.
     * @return  void
     */
    protected function _buildQueryColumns(Framework\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        $query->columns(array(
            'name'     => 'users.name'
        ));
    }

    /**
     * Builds LEFT JOINS clauses for the query.
     *
     * @param   Framework\DatabaseQuerySelect  A query object.
     * @return  void
     */
    protected function _buildQueryJoins(Framework\DatabaseQuerySelect $query)
    {
        $state = $this->getState();

        $query->join(array('users' => 'users'), 'tbl.email = users.email');
    }

    /**
     * Builds a WHERE clause for the query.
     *
     * @param   Framework\DatabaseQuerySelect  A query object.
     * @return  void
     */
    protected function _buildQueryWhere(Framework\DatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();
        
        if ($state->application)
        {
            $query->where('application IN :application')
                  ->bind(array('application' => (array) $state->application));
        }

        if ($state->email)
        {
            $query->where('tbl.email IN :email')
                  ->bind(array('email' => (array) $state->email));
        }
    }
}