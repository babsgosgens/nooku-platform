<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Library\HttpUrl;

/**
 * File Html View
 *
 * @author  Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package Component\Searches
 */
class SearchesViewFileHtml extends Library\ViewFile
{
    public function render()
    {
        $state = $this->getObject('request')->getQuery()->get('view', 'cmd');
        print_r($this->getUrl()->getQuery()->get('id','raw'));
        die();

        $file  = $this->getModel()->getRow();

        $this->path = $file->fullpath;
        $this->filename = $file->name;
        $this->mimetype = $file->mimetype ? $file->mimetype : 'application/octet-stream';
        if ($file->isImage() || $file->extension === 'pdf') {
            $this->disposition = 'inline';
        }

        if (!file_exists($this->path)) {
            throw new Library\ViewException(JText::_('File not found'));
        }

        return parent::render();
    }
}