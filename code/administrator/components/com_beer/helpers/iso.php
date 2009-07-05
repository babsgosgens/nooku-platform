<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * Developed for Brian Teeman's Developer Showdown, using Nooku Framework
 * @version		$Id$
 * @package		Beer
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class BeerHelperIso extends KObject
{
	/**
	 * Select
	 * @param $name
	 * @param $selected
	 * @return unknown_type
	 */
	public function country($name='country', $selected = '')
 	{
 		$countries = array();
 		$countries[] = KTemplate::loadHelper('select.option',  '', '- '. JText::_( 'Select a Country' ) .' -' );
		$list = KFactory::get('admin::com.beer.model.regions')
			->setState('region', 'world')->getList();
 		foreach($list as $code => $country) {
 			$countries[] = KTemplate::loadHelper('select.option',  $code, $country);
 		}

 		return KTemplate::loadHelper('select.genericlist', $countries, $name, 'class="inputbox" size="1" ', 'value', 'text', $selected );
 	}

 	public function states($region, $name = '', $selected = '' )
 	{
 		$states = array();
 		$states[] = KTemplate::loadHelper('select.option', '', '- '.JText::_( 'Select a State/Provence' ).' -' );
 		$list = KFactory::get('admin::com.beer.model.usstates')
 				->setState('region', $region)->getList();
 		foreach($list as $code => $state) {
 			$states[] = KTemplate::loadHelper('select.option',  $code, $state);
 		}

 		return KTemplate::loadHelper('select.genericlist', $states, $name, 'class="inputbox" size="1" ', 'value', 'text', $selected );
 	}
}