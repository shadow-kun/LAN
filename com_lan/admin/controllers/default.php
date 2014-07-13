<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	
   /**
	* LAN Party Component Controller
	*
	* @package		LAN
	* @subpackage	com_lan
	*/
	
	class LANController extends JControllerLegacy
	{
		/**
		 * @var		String		Sets the default view for the component.
		 * @since	0.0
		 */
		 
		 protected $default_view = 'events';
		 
		/**
		 * Override the display method for the controller   $cachable = false, $urlparams = Array
		 *
		 * @return	void
		 * @since 	0.0
		 */  
		 
		function display ($cachable = false, $urlparams = Array())
		{
			// Load the component helper.
			require_once (JPATH_COMPONENT.'/helpers/lan.php');
			
			// Display the view
			parent::display();
			
			// Set the submenu
			//LANHelper::addSubmenu('events');
		}
	}
?>