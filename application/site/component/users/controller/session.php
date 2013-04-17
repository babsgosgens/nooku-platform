<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Session Controller Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class UsersControllerSession extends ApplicationControllerDefault
{
    public function __construct(Library\Config $config)
    {
        parent::__construct($config);

        //Only authenticate POST requests
        $this->registerCallback('before.add' , array($this, 'authenticate'));

        //Authorize the user before adding
        $this->registerCallback('before.add' , array($this, 'authorize'));
        $this->registerCallback('after.add'  , array($this, 'redirect'));
    }

    protected function _initialize(Library\Config $config)
    {
        $config->append(array(
            'behaviors' => array(
                'com:activities.controller.behavior.loggable' => array('title_column' => 'name')
            )
        ));

        parent::_initialize($config);
    }

    public function authenticate(Library\CommandContext $context)
    {
        $email = $context->request->data->get('email', 'email');

        if(isset($email))
        {
            $user = $this->getService('com:users.model.users')->email($email)->getRow();

            //Authenticate the user
            if($user->id)
            {
                $password = $user->getPassword();

                if(!$password->verify($context->request->data->get('password', 'string'))) {
                    throw new Library\ControllerExceptionUnauthorized('Wrong password');
                }
            }

            //Start the session (if not started already)
            $context->user->session->start();

            //Set user data in context
            $data = array(
                'id'         => $user->id,
                'email'      => $user->email,
                'name'       => $user->name,
                'role'       => $user->role_id,
                'groups'     => $user->getGroups(),
                'password'   => $user->getPassword()->password,
                'salt'       => $user->getPassword()->salt,
                'authentic'  => true,
                'enabled'    => $user->enabled,
                'attributes' => $user->params->toArray(),
                'session'    => true,
            );

            $context->user->values($data);
        }
        else throw new Library\ControllerExceptionUnauthorized('Wrong email');

        return true;
    }

    public function authorize(Library\CommandContext $context)
    {
        //If the user is blocked, redirect with an error
        if (!$context->user->isEnabled()) {
            throw new Library\ControllerExceptionForbidden('Account disabled');
        }

        return true;
    }

    public function redirect(Library\CommandContext $context)
    {
        if ($context->result !== false) {
            $user     = $context->user;
            $password = $this->getService('com:users.database.row.password')->set('id', $user->getEmail())->load();
            if ($password->expired()) {
                $component = $this->getService('application.components')->getComponent('users');
                $pages     = $this->getService('application.pages');

                $page = $pages->find(array(
                    'extensions_component_id' => $component->id,
                    'link'                    => array(array('view' => 'user'))));

                $url                  = $page->getLink();
                $url->query['layout'] = 'password';
                $url->query['id']     = $user->getId();

                $this->getService('application')->getRouter()->build($url);
                $context->response->setRedirect($url);
            } else $context->response->setRedirect($context->request->getReferrer());
        }
    }

    protected function _actionAdd(Library\CommandContext $context)
    {
        $session = $context->user->session;

        //Insert the session into the database
        if(!$session->isActive()) {
            throw new Library\ControllerExceptionActionFailed('Session could not be stored. No active session');
        }

        //Fork the session to prevent session fixation issues
        $session->fork();

        //Prepare the data
        $data = array(
            'id'          => $session->getId(),
            'guest'       => !$context->user->isAuthentic(),
            'email'       => $context->user->getEmail(),
            'data'        => '',
            'time'        => time(),
            'application' => 'site',
        );

        $context->request->data->add($data);

        //Store the session
        $entity = parent::_actionAdd($context);

        //Set the session data
        $session->site = $this->getService('application')->getSite();

        //Redirect to caller
        $context->response->setRedirect($context->request->getReferrer());

        return $entity;
    }

    protected function _actionDelete(Library\CommandContext $context)
    {
        //Force logout from site only
        $context->request->query->application = array('site');

        //Remove the session from the session store
        $entity = parent::_actionDelete($context);

        if(!$context->response->isError())
        {
            // Destroy the php session for this user if we are logging out ourselves
            if($context->user->getEmail() == $entity->email) {
                $context->user->session->destroy();
            }
        }
        //Redirect to caller
        $context->response->setRedirect($context->request->getReferrer());

        return $entity;
    }
}