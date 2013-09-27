<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activity Translator Catalogue.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class TranslatorCatalogueActivity extends ComKoowaTranslatorCatalogue
{
    /**
     * Overloaded for avoiding key length limit.
     *
     * @see ComKoowaTranslatorCatalogue::generateKey()
     */
    public function generateKey($string)
    {
        $string = strtolower($string);

        $key = strip_tags($string);
        $key = preg_replace('#\s+#m', ' ', $key);
        $key = preg_replace('#%([A-Za-z0-9_\-\.]+)%#', ' $1 ', $key);
        $key = preg_replace('#(%[^%|^\s|^\b]+)#', 'X', $key);
        $key = preg_replace('#&.*?;#', '', $key);
        $key = preg_replace('#[\s-]+#', '_', $key);
        $key = preg_replace('#[^A-Za-z0-9_%]#', '', $key);
        $key = preg_replace('#_+#', '_', $key);
        $key = trim($key, '_');
        $key = trim(strtoupper($key));

        return $key;
    }
}