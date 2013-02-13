<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Application Router Class
.*
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationRouter extends KDispatcherRouter
{
    public function parse(KHttpUrl $url)
	{
        $vars = array();
        $path = trim($url->getPath(), '/');

        //Remove basepath
        $path = substr_replace($path, '', 0, strlen($this->getService('request')->getBasePath()));

        //Remove suffix
        if(!empty($path))
        {
            if($suffix = pathinfo($path, PATHINFO_EXTENSION))
            {
                $path = str_replace('.'.$suffix, '', $path);
                $vars['format'] = $suffix;
            }

            //Get the segments
            $segments = explode('/', $path);
        }
        
	    // Find language if more languages are enabled. 
	    $languages = $this->getService('application.languages');
        if(count($languages) > 1)
        {
	        // Test if the first segment of the path is a language slug.
	        if(!empty($path) && !empty($segments[0]))
	        {
                foreach($languages as $language)
                {
                    if($segments[0] == $language->slug)
                    {
                        $languages->setActive($language);
                        
                        $vars['language'] = array_shift($segments);
                        $url->setPath(implode($segments, '/'));
                        break;
                    }
                }
	        }
	        
		    // Redirect if language wasn't found.
            //@TODO : Move redirect out of the dispatcher
            /*if(empty($path) || !isset($vars['language']))
            {
                $redirect  = JURI::base(true).'/'.$languages->getPrimary()->slug;
                $redirect .= '/'.$path.$url->getQuery();
                
                $this->getService('application')->redirect($redirect);
            }*/
	    }

        if(!empty($path))
        {
            if(isset($segments[0]))
            {
                $vars['option'] = 'com_'.$segments[0];

                if(isset($segments[1])) {
                    $vars['view']   = $segments[1];
                } else {
                    $vars['view']   = $segments[0];
                }
            }
        }

        $url->query = array_merge($url->query, $vars);
        $url->path  = '';

        return true;
	}

	public function build(KHttpUrl $url)
	{
        $query    = $url->query;
        $segments = array();

	    // Add language slug if more than languages are enabled.
	    $languages = $this->getService('application.languages');
        if(count($languages) > 1)
        {
	        if(!isset($query['language'])) {
	            $segments[] = $languages->getActive()->slug;
	        } else {
	            $segments[] = $query['language'];
	        }
        }
	        
        if(isset($query['option']))
        {
            $segments[] = substr($query['option'], 4);
            unset($query['option']);

            if(isset($query['view']))
            {
                if($query['view'] != $segments[0]) {
                    $segments[] = $query['view'];
                }

                unset($query['view']);
            }
        }

        $url->query  = $query;
        $route       = implode('/', $segments);

        //Add the format to the uri
        $format = isset($url->query['format']) ? $url->query['format'] : 'html';

        if($this->getService('application')->getCfg('sef_suffix'))
        {
            $url->format = $format;
            unset($url->query['format']);
        }
        else
        {
            $url->format = '';
            if($format == 'html') {
                unset($url->query['format']);
            }
        }

        $url->path = $this->getService('request')->getBasePath().'/'.$route;

        // Removed unused query variables
        unset($url->query['Itemid']);
        unset($url->query['option']);

        return true;
	}
}