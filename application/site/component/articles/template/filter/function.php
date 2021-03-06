<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Function Template Filter
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Articles
 */
class ArticlesTemplateFilterFunction extends Library\TemplateFilterFunction
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->addFunction('highlight', '$this->getView()->highlight(');
    }
}