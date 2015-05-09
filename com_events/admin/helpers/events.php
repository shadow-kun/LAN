<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		Events Party!
	* @subpackage	com_events
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	
   /**
	* Events Party Component Helper
	*
	* @package		Events Party!
	* @subpackage	com_events
	*/
	
	abstract class EventsHelper
	{
		/**
		 * Configure the Linkbar.
		 *
		 * @param string $vName The name of the active view.
		 *
		 * @return void
		 *
		 * @since 0.0
		 */
		 
		public static function addSubmenu($submenu)
		{
			JSubMenuHelper::addEntry(
				JText::_('COM_EVENTS_SUBMENU_EVENTS'),	'index.php?option=com_events&view=events',	$submenu == 'events');
			JSubMenuHelper::addEntry(
				JText::_('COM_EVENTS_SUBMENU_CATEGORIES'),	'index.php?option=com_categories&extension=com_events', $submenu == 'categories');
				
			// set some global property
			$document = JFactory::getDocument();
			//$document->addStyleDeclaration('.icon-48-helloworld ' .
			//                               '{background-image: url(../media/com_helloworld/images/tux-48x48.png);}');
			if ($vName == 'categories') 
			{
					$document->setTitle(JText::_('COM_EVENTS_ADMINISTRATION_CATEGORIES'));
			}
		}



		/**
		 * Gets a list of actions that can performed.
		 *
		 * @return	JObject
		 * @since	0.0
		 */
		 
		public static function getActions()
		{
			$user 	= JFactory::getUser();
			$result	= new JObject;
			
			$actions = array(
				'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete');
				
			foreach ($actions as $action)
			{
				$result->set($action, $user->authorise($action, 'com_events'));
			}
			
			return $result;
		}
	}
?>