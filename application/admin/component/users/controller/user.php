<?php
/**
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * User Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class UsersControllerUser extends ApplicationControllerDefault
{ 
    public function __construct(Library\Config $config)
    {
        parent::__construct($config);

        $this->registerCallback(array('after.add','after.edit'), array($this, 'expire'));
    }

    protected function _initialize(Library\Config $config)
    {
        $config->append(array(
            'behaviors' => array(
                'resettable',
                'com:activities.controller.behavior.loggable' => array('title_column' => 'name'),
            )
        ));

        parent::_initialize($config);
    }

    protected function _actionDelete(Library\CommandContext $context)
    {
        $entity = parent::_actionDelete($context);

        $this->getService('com:users.model.sessions')
            ->email($entity->email)
            ->getRowset()
            ->delete();

        return $entity;
    }

    protected function _actionEdit(Library\CommandContext $context)
    {
        $entity = parent::_actionEdit($context);
        $user = $this->getService('user');

        if ($context->response->getStatusCode() == self::STATUS_RESET && $entity->id == $user->getId()) {
            // Logged user changed. Updated in memory/session user object.
            $user->values($entity->getSessionData($user->isAuthentic()));
        }

        return $entity;
    }

    public function expire(Library\CommandContext $context)
    {
        $entity = $context->result;
        // Expire the user's password if a password change was requested.
        if ($entity->getStatus() !== Library\Database::STATUS_FAILED && $context->request->data->get('password_change',
            'bool')
        ) {
            $entity->getPassword()->expire();
        }
    }
}