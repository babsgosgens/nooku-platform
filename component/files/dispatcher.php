<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Dispatcher Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */

class Dispatcher extends Library\DispatcherComponent
{
	public function __construct(Library\Config $config)
	{
		parent::__construct($config);
	
		// Return JSON response when possible
		$this->registerCallback('after.post' , array($this, 'renderResponse'));

        // Return correct status code for plupload
        $this->getService('application')->registerCallback('before.send', array($this, 'setStatusForPlupload'));
	}
	
	public function renderResponse(Library\CommandContext $context)
	{
		if ($context->action !== 'delete' && $this->getRequest()->getFormat() === 'json') {
			$this->getController()->execute('render', $context);
		}
	}

    /**
     * We need to return 200 even if an error happens in requests using Plupload.
     * Otherwise we cannot get the error message and display it to the user interface
     */
    public function setStatusForPlupload(Library\CommandContext $context)
    {
        if ($context->request->getFormat() == 'json' && $context->request->query->get('plupload', 'int'))
        {
            $context->response->setStatus('200');
        }
    }
}