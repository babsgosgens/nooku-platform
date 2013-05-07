<?php
/**
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Template Paginator Helper
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package		Koowa_Template
 * @subpackage	Helper
 */
class TemplateHelperPaginator extends TemplateHelperSelect
{
	/**
	 * Render item pagination
	 * 
	 * @param 	array 	$config An optional array with configuration options
	 * @return	string	Html
	 * @see  	http://developer.yahoo.com/ypatterns/navigation/pagination/
	 */
	public function pagination($config = array())
	{
	    $config = new ModelPaginator($config);
		$config->append(array(
		    'total'      => 0,
            'display'    => 4,
            'offset'     => 0,
            'limit'      => 0,
		    'attribs'	 => array(),
		    'show_limit' => true,
		    'show_count' => true,
            'page_rows'  => array(10, 20, 50, 100)
		));
	
		$html = '';
		$html .= '<style src="media://css/koowa.css" />';

		$html .= '<div class="-koowa-pagination pagination pagination-centered">';
		if($config->show_limit) {
		    $html .= '<div class="limit">'.\JText::_('Display NUM').' '.$this->limit($config).'</div>';
		}
		$html .=  $this->pages($config);
		if($config->show_count) {
		    $html .= '<div class="count"> '.\JText::_('Page').' '.$config->current.' '.\JText::_('of').' '.$config->count.'</div>';
		}
		$html .= '</div>';

		return $html;
	}
	
	/**
	 * Render a select box with limit values
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return 	string	Html select box
	 */
	public function limit($config = array())
	{
		$config = new ObjectConfig($config);
		$config->append(array(
			'limit'	  	=> 0,
            'page_rows' => array(10, 20, 50, 100),
			'attribs'	=> array(),
		));
		
		$html = '';
		
		$selected = '';
		foreach($config->page_rows as $limit)
		{
			if($limit == $config->limit) {
				$selected = $limit;
			}

			$options[] = $this->option(array('text' => $limit, 'value' => $limit));
		}

		$html .= $this->optionlist(array('options' => $options, 'name' => 'limit', 'attribs' => $config->attribs, 'selected' => $selected));
		return $html;
	}

	/**
	 * Render a list of pages links
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return	string	Html
	 */
	public function pages($config = array())
	{
	    $config = new ModelPaginator($config);
		$config->append(array(
			'total'      => 0,
			'display'    => 4,
			'offset'     => 0,
			'limit'	     => 0,
			'attribs'	=> array(),
		));

        $html = '<ul>';

		if($config->offset) {
            $html .= $this->link($config->pages->prev);
        }
		foreach($config->pages->offsets as $offset) {
			$html .= $this->link($offset);
		}

        if($config->total > ($config->offset + $config->limit)) {
		    $html .= $this->link($config->pages->next);
        }

        $html .= '</ul>';

		return $html;
	}

	/**
	 * Render a page link
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return	string	Html
	 */
    public function link($config)
    {
        $config = new ObjectConfig($config);
		$config->append(array(
			'title'   => '',
			'current' => false,
		    'active'  => false,
			'offset'  => 0,
			'limit'	  => 0,
		    'rel'	  => '',
			'attribs'  => array(),
		));
		
        $route = $this->getTemplate()->getView()->getRoute('limit='.$config->limit.'&offset='.$config->offset);
        $rel   = !empty($config->rel) ? 'rel="'.$config->rel.'"' : ''; 
        
        if(!$config->active && $config->current) {
            $html = '<li class="active"><a href="#">'.\JText::_($config->title).'</a></li>';
        } elseif (!$config->active && !$config->current) {
            $html = '<li class="disabled"><a class="disabled" href="#">'.\JText::_($config->title).'</a></li>';
        } else {
            $html = '<li><a href="'.$route.'" '.$rel.'>'.\JText::_($config->title).'</a></li>';
        }

        return $html;
    }
}
