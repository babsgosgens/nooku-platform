<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;


/**
 * Searches Html View
 *
 * @author      Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package Component\Files
 */
class SearchesViewSearchesHtml extends Library\ViewHtml
{
    public function render()
    {
        $model = $this->getObject($this->getModel()->getIdentifier());
        $this->packages = $model->distinct('identifier_package')->getRowset();

        return parent::render();
    }
}