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

class DatabaseRowNode extends Library\DatabaseRowAbstract
{
	protected $_adapter;

	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

		$this->mixin('lib:command.mixin', $config);

		if ($config->validator !== false)
		{
			if ($config->validator === true) {
				$config->validator = 'com:files.command.validator.'.$this->getIdentifier()->name;
			}

			$this->getCommandChain()->enqueue($this->getObject($config->validator));
		}
	}

	protected function _initialize(Library\ObjectConfig $config)
	{
		$config->append(array(
			'command_chain'     => $this->getObject('lib:command.chain'),
			'dispatch_events'   => false,
			'event_dispatcher'  => $this->getObject('lib:event.dispatcher.default'),
			'enable_callbacks'  => true,
			'validator' 		=> true
		));

		parent::_initialize($config);
	}

	public function isNew()
	{
		return empty($this->name) || !$this->_adapter->exists();
	}

	public function copy()
	{
		$context = $this->getCommandContext();
		$context->result = false;

		if ($this->getCommandChain()->run('before.copy', $context) !== false)
		{
			$context->result = $this->_adapter->copy($this->destination_fullpath);
			$this->getCommandChain()->run('after.copy', $context);
        }

		if ($context->result === false)
		{
			$this->setStatus(Library\Database::STATUS_FAILED);
		}
		else
		{
			if ($this->destination_folder) {
				$this->folder = $this->destination_folder;
			}
			if ($this->destination_name) {
				$this->name = $this->destination_name;
			}

			$this->setStatus($this->overwritten ? Library\Database::STATUS_UPDATED : Library\Database::STATUS_CREATED);
		}

		return $context->result;
	}

	public function move()
	{
		$context = $this->getCommandContext();
		$context->result = false;

		if ($this->getCommandChain()->run('before.move', $context) !== false)
		{
			$context->result = $this->_adapter->move($this->destination_fullpath);
			$this->getCommandChain()->run('after.move', $context);
        }

		if ($context->result === false)
		{
			$this->setStatus(Library\Database::STATUS_FAILED);
		}
		else
		{
			if ($this->destination_folder) {
				$this->folder = $this->destination_folder;
			}
			if ($this->destination_name) {
				$this->name = $this->destination_name;
			}

			$this->setStatus($this->overwritten ? Library\Database::STATUS_UPDATED : Library\Database::STATUS_CREATED);
		}

		return $context->result;
	}

	public function delete()
	{
		$context = $this->getCommandContext();
		$context->result = false;

		if ($this->getCommandChain()->run('before.delete', $context) !== false)
		{
			$context->result = $this->_adapter->delete();
			$this->getCommandChain()->run('after.delete', $context);
        }

		if ($context->result === false) {
			$this->setStatus(Library\Database::STATUS_FAILED);
		} else {
            $this->setStatus(Library\Database::STATUS_DELETED);
        }

		return $context->result;
	}

	public function __get($column)
	{
		if ($column == 'fullpath' && !isset($this->_data['fullpath'])) {
			return $this->getFullpath();
		}

		if ($column == 'path') {
			return trim(($this->folder ? $this->folder.'/' : '').$this->name, '/\\');
		}
		
		if ($column == 'display_name' && empty($this->_data['display_name'])) {
			return $this->name;
		}

		if ($column == 'destination_path')
		{
			$folder = !empty($this->destination_folder) ? $this->destination_folder.'/' : (!empty($this->folder) ? $this->folder.'/' : '');
			$name   = !empty($this->destination_name) ? $this->destination_name : $this->name;

			return trim($folder.$name, '/\\');
		}

		if ($column == 'destination_fullpath') {
			return $this->container->path.'/'.$this->destination_path;
		}

		if ($column == 'adapter') {
			return $this->_adapter;
		}


		return parent::__get($column);
	}

	public function __set($column, $value)
	{
		parent::__set($column, $value);

		if (in_array($column, array('container', 'folder', 'name'))) {
			$this->setAdapter();
		}
	}

	public function setAdapter()
	{
		$type = $this->getIdentifier()->name;
		$this->_adapter = $this->container->getAdapter($type, array(
			'path' => $this->container->path.'/'.($this->folder ? $this->folder.'/' : '').$this->name
		));

		unset($this->_data['fullpath']);
		unset($this->_data['metadata']);

		return $this;
	}

	public function setData($data, $modified = true)
	{
		$result = parent::setData($data, $modified);

		if (isset($data['container'])) {
			$this->setAdapter();
		}

		return $result;
	}

	public function getFullpath()
	{
		return $this->_adapter->getRealPath();
	}

    public function toArray()
    {
        $data = parent::toArray();

        unset($data['_token']);
        unset($data['action']);
        unset($data['option']);
        unset($data['format']);
        unset($data['view']);

		$data['container'] = $this->container->slug;
		$data['type']      = $this->getIdentifier()->name;

        return $data;
    }

    public function isLockable()
    {
    	return false;
    }
}
