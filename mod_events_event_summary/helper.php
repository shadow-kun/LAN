<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	mod_events_event_summary
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	 
	class modEventsEventSummaryHelper
	{
		/**
		 * Retrives basic event information as either determined automatically
		 * or via a manual override in ACP.
		 *
		 * @param array $params An object containing the module parameters
		 * @access public
		 */
		 
		public static function getEvent ($params)
		{
			//Obtain a database connection
			$db = JFactory::getDbo();
			
			// Format time
			$time = date('Y-m-d H:i:s', time());
			
			//Retrieve data for the next event based off time.
			$query = $db->getQuery(true)
						->select('id AS id, title AS title, event_start_time AS startTime, event_end_time AS endTime, players_max AS playersMax, players_current AS playersCurrent, params')
						->from($db->quoteName('#__events_events'))
						->where('event_end_time > "' . date('Y-m-d H:i:s', time()) . '"')
						->order('startTime ASC');
						
			//Prepare the query
			$db->setQuery($query);
			
			// Load the row.
			$result = $db->loadObject();
			$db->query();
						
			//Return the Hello
			return $result;
		}
		
		public static function getCurrentPlayer ($params, $event)
		{
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
						
			// Select the required fields from the table.
			$query->select('p.id AS id, p.event, p.status AS status, p.params');
			$query->from('#__events_players AS p');
						
			// Selects the event that is required.
			$query->where('p.event = ' . $event);
			
			// Selects current user.
			$query->where('p.user = ' . JFactory::getUser()->id);
						
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
			
			return $result;
		}
	}
?>