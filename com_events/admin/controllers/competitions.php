<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	
	jimport('joomla.application.component.controlleradmin');
	
	/**
	 * Events Sub-Controller
	 *
	 * @package			LAN
	 * @subpackage		com_lan
	 * @since			0.0
	 */
	 
	class EventsControllerCompetitions extends JControllerAdmin
	{
		/**
		 * Proxy for getModel.
		 *
		 * @param	string	$name	The name of the model.
		 * @param	string	$prefix	The prefix for the model class name.
		 * @param	string	$config The model configuration array.
		 *
		 * @return	LANModelEvents	The model for the controller set to ignore the request.
		 * @since	0.0
		 */
		 
		public function getModel($name = 'Competition', $prefix = 'EventsModel', $config = array('ignore_request' => true))
		{
			return parent::getModel($name, $prefix, $config);
		}
	}
?>