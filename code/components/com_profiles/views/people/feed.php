<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesViewPeopleFeed extends KViewAbstract
{
	public function display()
	{	
		$this->_document->link = $this->createRoute('view=people');
		
		$people = KFactory::get($this->getModel())->getList();
		
		foreach ( $people as $person )
		{
			$title = $this->escape( $person->name );
			$title = html_entity_decode( $title );

			// url link to article
			$link = $this->createRoute('view=person&id='.$person->slug );

			// generate the description as a hcard
			$this->assign('person', $person);
			$description = $this->loadTemplate('site::com.profiles.view.person.hcard');
			
			// load individual item creator class
			$item = new JFeedItem();
			$item->title 		= $title;
			$item->link 		= $link;
			$item->description 	= $description;
			$item->date			= date( 'r', strtotime($person->created) );
			$item->category   	= $person->department;

			// loads item info into rss array
			$this->_document->addItem( $item );
		}

		return $this;
	}
}