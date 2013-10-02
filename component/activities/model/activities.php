<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activities Model
 *
 * @author  Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class ModelActivities extends Library\ModelTable
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $state = $this->getState();

        $state->insert('application', 'cmd')
            ->insert('package', 'cmd')
            ->insert('name', 'cmd')
            ->insert('action', 'cmd')
            ->insert('row', 'int')
            ->insert('user', 'cmd')
            ->insert('distinct', 'boolean', false)
            ->insert('column', 'cmd')
            ->insert('start_date', 'date')
            ->insert('end_date', 'date')
            ->insert('day_range', 'int')
            ->insert('ip', 'ip');

        $state->remove('direction')->insert('direction', 'word', 'desc');

        // Force ordering by created_on
        $state->sort = 'created_on';
    }

    public function getPurgeQuery()
    {
        $state = $this->getState();

        $query = $this->getObject('lib:database.query.delete');

        $query->table(array($this->getTable()->getName()));

        if ($state->end_date && $state->end_date != '0000-00-00')
        {
            $end_date = new Library\Date(array('date' => $state->end_date));
            $end      = $end_date->format('Y-m-d');

            $query->where('DATE(created_on) <= :end')->bind(array('end' => $end));
        }

        return $query;
    }

    protected function _buildQueryColumns(Library\DatabaseQueryInterface $query)
    {
        $state = $this->getState();

        if ($state->distinct && !empty($state->column))
        {
            $query->distinct()
            ->columns($state->column)
            ->columns(array('activities_activity_id' => $state->column));
        }
        else
        {
            parent::_buildQueryColumns($query);
            $query->columns(array('created_by_name' => 'users.name'));
        }
    }

    protected function _buildQueryJoins(Library\DatabaseQueryInterface $query)
    {
        $query->join(array('users' => 'users'), 'users.users_user_id = tbl.created_by');
    }

    protected function _buildQueryWhere(Library\DatabaseQueryInterface $query)
    {
        parent::_buildQueryWhere($query);

        $state = $this->getState();

        if ($state->application)
        {
            $query->where('tbl.application = :application')->bind(array('application' => $state->application));
        }

        if ($state->package)
        {
            $query->where('tbl.package = :package')->bind(array('package' => $state->package));
        }

        if ($state->name)
        {
            $query->where('tbl.name = :name')->bind(array('name' => $state->name));
        }

        if ($state->action)
        {
            $query->where('tbl.action IN (:action)')->bind(array('action' => $state->action));
        }

        if (is_numeric($state->row))
        {
            $query->where('tbl.row IN (:row)')->bind(array('row' => $state->row));
        }

        if ($state->start_date && $state->start_date != '0000-00-00')
        {
            $start_date = new Library\Date(array('date' => $state->start_date));

            $query->where('DATE(tbl.created_on) >= :start')->bind(array('start' => $start_date->format('Y-m-d')));

            if ($day_range = $state->day_range)
            {
                $query->where('DATE(tbl.created_on) <= :range_start')->bind(array(
                    'range_start' => $start_date
                                     ->add(new \DateInterval('P' . $day_range . 'D'))
                                     ->format('Y-m-d')));
            }
        }

        if ($state->end_date && $state->end_date != '0000-00-00')
        {
            $end_date = new Library\Date(array('date' => $state->end_date));

            $query->where('DATE(tbl.created_on) <= :end')->bind(array('end' => $end_date->format('Y-m-d')));

            if ($day_range = $state->day_range)
            {
                $query->where('DATE(tbl.created_on) >= :range_end')->bind(array(
                    'range_end' => $end_date
                                   ->sub(new \DateInterval('P' . $day_range . 'D'))
                                   ->format('Y-m-d')));
            }
        }

        if ($state->user)
        {
            $query->where('tbl.created_by = :created_by')->bind(array('created_by' => $state->user));
        }

        if ($ip = $state->ip)
        {
            $query->where('tbl.ip IN (:ip)')->bind(array('ip' => $state->ip));
        }
    }

    protected function _buildQueryOrder(Library\DatabaseQueryInterface $query)
    {
        $state = $this->getState();

        if ($state->distinct && !empty($state->column))
        {
            $query->order('package', 'asc');
        }
        else
        {
            parent::_buildQueryOrder($query);
        }
    }
}