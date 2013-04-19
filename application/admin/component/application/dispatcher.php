<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Application Dispatcher Class
.*
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ApplicationDispatcher extends Library\DispatcherApplication
{
    /**
     * The site identifier.
     *
     * @var string
     */
    protected $_site;

    /**
     * The application message queue.
     *
     * @var	array
     */
    protected $_message_queue = array();

    /**
     * The application options
     *
     * @var Library\Config
     */
    protected $_options = null;

    /**
     * Constructor.
     *
     * @param 	object 	An optional Library\Config object with configuration options.
     */
    public function __construct(Library\Config $config)
    {
        parent::__construct($config);

        //Register the default exception handler
        $this->addEventListener('onException', array($this, 'exception'), Library\Event::PRIORITY_LOW);

        //Set callbacks
        $this->registerCallback('before.run', array($this, 'loadConfig'));
        $this->registerCallback('before.run', array($this, 'loadSession'));
        $this->registerCallback('before.run', array($this, 'loadLanguage'));

        // Set the connection options
        $this->_options = $config->options;

        //Set the base url in the request
        $this->getRequest()->setBaseUrl($config->base_url);

        //Setup the request
        Library\Request::root(str_replace('/administrator', '', Library\Request::base()));

        //Set the site name
        if(empty($config->site)) {
            $this->_site = $this->_findSite();
        } else {
            $this->_site = $config->site;
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional Library\Config object with configuration options.
     * @return 	void
     */
    protected function _initialize(Library\Config $config)
    {
        $config->append(array(
            'base_url'          => '/administrator',
            'component'         => 'dashboard',
            'event_dispatcher'  => 'com:debug.event.dispatcher.debug',
            'event_subscribers' => array('com:application.event.subscriber.unauthorized'),
            'site'     => null,
            'options'  => array(
                'session_name' => 'admin',
                'config_file'  => JPATH_ROOT.'/config/config.php',
                'theme'        => 'bootstrap'
            ),
        ));

        parent::_initialize($config);
    }

    /**
     * Run the application
     *
     * @param Library\CommandContext $context	A command context object
     */
    protected function _actionRun(Library\CommandContext $context)
    {
        //Set the site error reporting
        $this->getEventDispatcher()->setDebugMode($this->getCfg('debug_mode'));

        //Set the paths
        $params = $this->getService('application.components')->files->params;

        define('JPATH_FILES'  , JPATH_SITES.'/'.$this->getSite().'/files');
        define('JPATH_IMAGES' , JPATH_SITES.'/'.$this->getSite().'/files/'.$params->get('image_path', 'images'));
        define('JPATH_CACHE'  , $this->getCfg('cache_path', JPATH_ROOT.'/cache'));

        // Set timezone to user's setting, falling back to global configuration.
        $timezone = new \DateTimeZone($context->user->get('timezone', $this->getCfg('timezone')));
		date_default_timezone_set($timezone->getName());

        //Route the request
        $this->route();
    }

    /**
     * Route the request
     *
     * @param Library\CommandContext $context	A command context object
     */
    protected function _actionRoute(Library\CommandContext $context)
    {
        $url = clone $context->request->getUrl();

        //Parse the route
        $this->getRouter()->parse($url);

        //Set the request
        $context->request->query->add($url->query);

        //Set the controller to dispatch
        if($context->request->query->has('option'))
        {
            $component = substr( $context->request->query->get('option', 'cmd'), 4);
            $this->setComponent($component);
        }

        //Dispatch the request
        $this->dispatch();
    }

    /**
     * Dispatch the request
     *
     * @param Library\CommandContext $context	A command context object
     */
    protected function _actionDispatch(Library\CommandContext $context)
    {
        $component = $this->getController()->getIdentifier()->package;

        if (!$this->getService('application.components')->isEnabled($component)) {
            throw new ControllerExceptionNotFound('Component Not Enabled');
        }

        /*
         * Disable controller persistency on non-HTTP requests, e.g. AJAX. This avoids changing
         * the model state session variable of the requested model, which is often undesirable
         * under these circumstances.
         */
        if($this->getRequest()->isGet() && !$this->getRequest()->isAjax()) {
            $this->getComponent()->attachBehavior('persistable');
        }

        //Dispatch the controller
        parent::_actionDispatch($context);

        //Render the page
        if(!$context->response->isRedirect() && $context->request->getFormat() == 'html')
        {
            $config = array('response' => $context->response);

            $layout = $context->request->query->get('tmpl', 'cmd', 'default');
            if(!$this->isPermitted('render')) {
                $layout = 'login';
            }

            $this->getService('com:application.controller.page', $config)
                 ->layout($layout)
                 ->render();
        }

        //Send the response
        $this->send($context);
    }

    /**
     * Render an exception
     *
     * @throws InvalidArgumentException If the action parameter is not an instance of Library\Exception
     * @param Library\CommandContext $context	A command context object
     */
    protected function _actionException(Library\CommandContext $context)
    {
        //Check an exception was passed
        if(!isset($context->param) && !$context->param instanceof Exception)
        {
            throw new \InvalidArgumentException(
                "Action parameter 'exception' [Library\EventException] is required"
            );
        }

        $config = array(
            'request'  => $this->getRequest(),
            'response' => $this->getResponse()
        );

        $this->getService('com:application.controller.exception',  $config)
             ->render($context->param->getException());

        //Send the response
        $this->send($context);
    }

    /**
     * Load the configuration
     *
     * @param Library\CommandContext $context	A command context object
     * @return	void
     */
    public function loadConfig(Library\CommandContext $context)
    {
        // Check if the site exists
        if($this->getService('com:sites.model.sites')->getRowset()->find($this->getSite()))
        {
            //Load the application config settings
            JFactory::getConfig()->loadArray($this->_options->toArray());

            //Load the global config settings
            require_once( $this->_options->config_file );
            JFactory::getConfig()->loadObject(new JConfig());

            //Load the site config settings
            require_once( JPATH_SITES.'/'.$this->getSite().'/config/config.php');
            JFactory::getConfig()->loadObject(new JSiteConfig());

        }
        else throw new ControllerExceptionNotFound('Site :'.$this->getSite().' not found');
    }

    /**
     * Load the user session or create a new one
     *
     * Old sessions are flushed based on the configuration value for the cookie lifetime. If an existing session,
     * then the last access time is updated. If a new session, a session id is generated and a record is created
     * in the #__users_sessions table.
     *
     * @param Library\CommandContext $context	A command context object
     * @return	void
     */
    public function loadSession(Library\CommandContext $context)
    {
        $session = $context->user->session;

        //Set Session Name
        $session->setName(md5($this->getCfg('secret').$this->getCfg('session_name')));

        //Set Session Lifetime
        $session->setLifetime($this->getCfg('lifetime', 15) * 60);

        //Set Session Handler
        $session->setHandler('database', array('table' => 'com:users.database.table.sessions'));

        //Set Session Options
        $session->setOptions(array(
            'cookie_path'   => (string) $context->request->getBaseUrl()->getPath(),
            'cookie_secure' => $this->getCfg('force_ssl') == 2 ? true : false
        ));

        //Auto-start the session if a cookie is found
        if(!$session->isActive())
        {
            if ($context->request->cookies->has($session->getName())) {
                $session->start();
            }
        }

        //Re-create the session if we changed sites
        if($context->user->isAuthentic() && ($session->site != $this->getSite()))
        {
            //@TODO : Fix this
            //if(!$this->getService('com:users.controller.session')->add()) {
            //    $session->destroy();
            //}
        }
    }

    /**
     * Get the application languages.
     *
     * @return	LanguagesDatabaseRowsetLanguages
     */
    public function loadLanguage(Library\CommandContext $context)
    {
        $languages = $this->getService('application.languages');
        $primary   = $languages->getPrimary();

        // Set content language.
        if(count($languages) > 1)
        {
            $url      = clone $context->request->getUrl();
            $path     = explode('/', $url->getPath());
            $language = isset($path[2]) ? $languages->find(array('slug' => $path[2])) : array();

            // If language slug is not in the path, make a redirect.
            if(!count($language))
            {
                $url->setPath(implode('/', array_merge(array_slice($path, 0, 2), array($primary->slug), array_slice($path, 2))));
                $context->response->setRedirect($url);
                $languages->setActive($primary);
            }
            else
            {
                $language = $language->top();
                $languages->setActive($language);

                $behavior = $this->getService('com:languages.database.behavior.translatable');
                $this->getService('lib:database.adapter.mysql')->getCommandChain()->enqueue($behavior);
            }
        }
        else $languages->setActive($primary);

        // Set application language.
        $language = $languages->find(array('iso_code' => $context->user->get('language')));

        if(count($language)) {
            $language = $language->top();
        } else {
            $language = $languages->getPrimary();
        }

        JFactory::getConfig()->setValue('config.language', $language->iso_code);
    }

    /**
     * Get the application router.
     *
     * @param  array $options 	An optional associative array of configuration options.
     * @return	\ApplicationRouter
     */
    public function getRouter(array $options = array())
    {
        $router = $this->getService('com:application.router', $options);
        return $router;
    }

    /**
     * Gets a configuration value.
     *
     * @param	string	$name    The name of the value to get.
     * @param	mixed	$default The default value
     * @return	mixed	The user state.
     */
    public function getCfg( $name, $default = null )
    {
        return JFactory::getConfig()->getValue('config.' . $name, $default);
    }

    /**
     * Gets the name of site
     *
     * @return	string
     */
    public function getSite()
    {
        return $this->_site;
    }

    /**
     * Get the theme
     *
     * @return string The theme name
     */
    public function getTheme()
    {
        return $this->_options->theme;
    }

    /**
     * Enqueue a system message.
     *
     * @param	string 	$msg 	The message to enqueue.
     * @param	string	$type	The message type.
     */
    function enqueueMessage( $msg, $type = 'message' )
    {
        // For empty queue, if messages exists in the session, enqueue them first
        if (!count($this->_message_queue))
        {
            $session_queue = $this->getUser()->get('application.queue');

            if (count($session_queue))
            {
                $this->_message_queue = $session_queue;
                $this->getUser()->remove('application.queue');
            }
        }

        // Enqueue the message
        $this->_message_queue[] = array('message' => $msg, 'type' => strtolower($type));
    }

    /**
     * Get the system message queue.
     *
     * @return	The system message queue.
     */
    function getMessageQueue()
    {
        // For empty queue, if messages exists in the session, enqueue them
        if (!count($this->_message_queue))
        {
            $session_queue = $this->getUser()->get('application.queue');

            if (count($session_queue))
            {
                $this->_message_queue = $session_queue;
                $this->getUser()->set('application.queue', null);
            }
        }

        return $this->_message_queue;
    }

    /**
     * Find the site name
     *
     * This function tries to get the site name based on the information present in the request. If no site can be found
     * it will return 'default'.
     *
     * @return string   The site name
     */
    protected function _findSite()
    {
        // Check URL host
        $uri  = clone(JURI::getInstance());

        $host = $uri->getHost();
        if(!$this->getService('com:sites.model.sites')->getRowset()->find($host))
        {
            // Check folder
            $base = $this->getRequest()->getBaseUrl()->getPath();
            $path = trim(str_replace($base, '', $uri->getPath()), '/');
            if(!empty($path)) {
                $site = array_shift(explode('/', $path));
            } else {
                $site = 'default';
            }

            //Check if the site can be found, otherwise use 'default'
            if(!$this->getService('com:sites.model.sites')->getRowset()->find($site)) {
                $site = 'default';
            }

        } else $site = $host;

        return $site;
    }
}
