<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	mod_events_internet_permissions
	 * @copyright	Copyright 2015 ZoR Systems. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	 
	class modEventsInternetPermissionsHelper
	{
		/**
		 * Retrives basic event information as either determined automatically
		 * or via a manual override in ACP.
		 *
		 * @param array $params An object containing the module parameters
		 * @access public
		 */
		 
		public static function getAllUsers ($params)
		{
			//Obtain a database connection
			$db = JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('i.id, i.user, i.mac_address, i.ip_address, i.created_time, i.expire_time, i.interface, i.params');
			$query->from('#__events_internet AS i');
						
			//Prepare the query
			$db->setQuery($query);
			
			// Load the row.
			$result = $db->loadObjectList();
			$db->query();
			
			foreach($result as $r => $row)
			{
				$result[$r]->params = json_decode($row->params);
			}
			
			//Return the Hello
			return $result;
		}
		
		public static function getCurrentUser ($params)
		{
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
						
			// Select the required fields from the table.
			$query->select('i.id, i.user, i.mac_address, i.ip_address, i.created_time, i.expire_time, i.interface AS intf, i.params');
			$query->from('#__events_internet AS i');
						
			// Selects the event that is required.
			$query->where('i.user = ' . JFactory::getUser()->id);
						
			// Runs query
			$result = $db->setQuery($query)->loadObjectList();
			$db->query();
			
			foreach($result as $r => $row)
			{
				$result[$r]->params = json_decode($row->params);
			}
			
			return $result;
		}
	}
?>