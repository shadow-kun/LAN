<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	* @package 		Events
	* @subpackage 	com_events
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/

	jimport('joomla.application.component.model');

	/**
	* Message model.
	*
	* @package Events
	* @subpackage com_events
	* @since 1.0.1
	*/
	class EventsModelsInternet extends EventsModelsDefault
	{
		/**
		 * Model context string.
		 *
		 * @var        string
		 */
		  function __construct()
		{
			parent::__construct();
		}
		
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
		
		public static function getUserInternet ($params)
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
		
		public function storeMachine()
		{
			$app = JFactory::getApplication();
			
			// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			if(JFactory::getUser()->authorise('core.edit.state','com_events'))
			{
				// Select the required fields from the table.
				$query->select('i.id AS id, i.mac_address, i.interface');
				$query->from('#__events_internet AS i');
							
				// Selects the competition that is required.
				$query->where('p.competition = ' . $competition);
				
				// Selects current team.
				$query->where('p.team = ' . $team);
				
				// Selects only non cancelled entries. (Inactive as of current)
				
				// Runs query
				$result = $db->setQuery($query)->loadObject();
				$db->query();
					
				// Checks to see if already registered for this competition
				if(!(isset($result)))
				{					
					//Sets JSON Params data
					$params = $db->quote(json_encode(array('status' => 1)));
					
					// Sets columns
					$colums = array('id', 'competition', 'team', 'params');
					
					// Sets values
					$values = array('NULL', $competition, $team, $params);
					
					// Prepare Insert Query $db->quoteName('unconfirmed')
					$query  ->insert($db->quoteName('#__events_competition_teams'))
							->columns($db->quoteName($colums))
							->values(implode(',', $values));
					
					// Set the query and execute item
					$db->setQuery($query);
					$db->query();
				}
			}
			else
			{
				return false;
			}
			
			
			return true;
			
		}
	}