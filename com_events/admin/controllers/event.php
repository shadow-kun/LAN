<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		Events Party!
	* @subpackage	com_events
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	
	jimport('joomla.application.component.controllerform');
	
	/**
	 * Event Sub-Controller
	 *
	 * @package			Events Party!
	 * @subpackage		com_events
	 * @since			0.0
	 */
	 
	class EventsControllerEvent extends JControllerForm
	{
		public function save ($key = null, $urlVar = null)
		{
			parent::save($key, $urlVar);
			
			$app            = JFactory::getApplication();
			
			
			$model          = $this->getModel();
			$table          = $model->getTable();
			$event			= $app->input->getInt('id');
			$data           = JRequest::getVar('jform', array(), 'post', 'array');
			
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);			
			
			
			if(!(empty($data['add_user'])))
			{
				// Select the required fields from the table.
				$query->select('e.id AS id, e.event, e.status, e.params');
				$query->from('#__events_players AS e');
							
				// Selects the team that is required.
				$query->where('e.event = ' . $event);
				
				// Selects current user.
				$query->where('e.user = ' . $data['add_user']);
				
				// Selects only non cancelled entries. (Inactive as of current)
				
				// Runs query
				$result = $db->setQuery($query)->loadObject();
				$db->query();
				
				// Checks to see if already registered for this team
				if(!(isset($result)))
				{
					// Sets columns
					$colums = array('id', 'event', 'user', 'status', 'params');
					
					// Sets values
					$values = array('NULL',$event, $data['add_user'], $data['add_user_status'], 'NULL');
					
					// Prepare Insert Query $db->quoteName('unconfirmed')
					$query  ->insert($db->quoteName('#__events_players'))
							->columns($db->quoteName($colums))
							->values(implode(',', $values));
					
					// Set the query and execute item
					$db->setQuery($query);
					$db->query();
					
					
					$query	= $db->getQuery(true);
					
					$currentPlayers = $this->items->a.players_current;
					
					$fields = 'players_current' . ' = ' . $currentPlayers . ' + 1';

					$conditions = array($db->quoteName('id') . ' = ' . $event);
					
					$query->update($db->quoteName('#__events_events'));
					$query->set($fields);
					$query->where($conditions);
					
					$db->setQuery($query);
					
					$db->query();
					
					
					// Returns a notice message stating user has been added to the team.
					$app->enqueueMessage(JFactory::getUser($data['add_user'])->username . JText::_('COM_EVENTS_EVENT_MSG_PLAYER_ADDED'), 'notice');
				}
				else
				{
					// Returns an error message stating user already in the team.
					$app->enqueueMessage(JText::_('COM_EVENTS_EVENT_ERROR_USER_ALREADY_IN_EVENT'), 'error');
				}
			}
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('e.id AS id, e.event, e.status, e.params');
			$query->from('#__events_players AS e');
						
			// Selects the team that is required.
			$query->where('e.event = ' . $event);
						
			// Runs query
			$result = $db->setQuery($query)->loadObjectList();
			$db->query();
			
			
			
			
			foreach ($result as $p => $player) :
			{
				$status = JRequest::getVar('player_status_change#' . $player->id);
				
				if($player->status != $status)
				{
					if($player->status == 2)
					{
						$db->query();
			
						$query	= $db->getQuery(true);
						
						$confirmedPlayers = $this->event->a.players_confirmed;
						$fields = 'players_confirmed' . ' = ' . $confirmedPlayers . ' - 1';

						$conditions = array($db->quoteName('id') . ' = ' . $event);
						
						$query->update($db->quoteName('#__events_events'));
						$query->set($fields);
						$query->where($conditions);
						
						$db->setQuery($query);
						
						$db->query();
					}
					elseif($status == 4)
					{
						$db->query();
			
						$query	= $db->getQuery(true);
						
						$prepaidPlayers = $this->event->a.players_prepaid;
						$fields = 'players_prepaid' . ' = ' . $prepaidPlayers . ' + 1';

						$conditions = array($db->quoteName('id') . ' = ' . $event);
						
						$query->update($db->quoteName('#__events_events'));
						$query->set($fields);
						$query->where($conditions);
						
						$db->setQuery($query);
						
						$db->query();
					}
					
					if($status == 2)
					{
						$db->query();
			
						$query	= $db->getQuery(true);
						
						$confirmedPlayers = $this->event->a.players_confirmed;
						$fields = 'players_confirmed' . ' = ' . $confirmedPlayers . ' + 1';

						$conditions = array($db->quoteName('id') . ' = ' . $event);
						
						$query->update($db->quoteName('#__events_events'));
						$query->set($fields);
						$query->where($conditions);
						
						$db->setQuery($query);
						
						$db->query();
					}
					elseif($player->status == 4)
					{
						$db->query();
			
						$query	= $db->getQuery(true);
						
						$prepaidPlayers = $this->event->a.players_prepaid;
						$fields = 'players_prepaid' . ' = ' . $prepaidPlayers . ' - 1';

						$conditions = array($db->quoteName('id') . ' = ' . $event);
						
						$query->update($db->quoteName('#__events_events'));
						$query->set($fields);
						$query->where($conditions);
						
						$db->setQuery($query);
						
						$db->query();
					}
						
					
					// If status is to remove the user 
					if($status == -2)
					{
						$query	= $db->getQuery(true);
						
						// Sets delete statement and clauses
						$query->delete($db->quoteName('#__events_players'));
						
						// Sets conditions for a single player for that team.
						$query->where($db->quoteName('event') . ' = ' . $event);
						$query->where($db->quoteName('id') . ' = ' . $player->id);
						
						// Set the query and execute item
						$db->setQuery($query);
						$db->query();
						
						
						$query	= $db->getQuery(true);
						
						$db->query();
			
						$query	= $db->getQuery(true);
						
						$currentPlayers = $this->event->a.players_current;
						$fields = 'players_current' . ' = ' . $currentPlayers . ' - 1';

						$conditions = array($db->quoteName('id') . ' = ' . $event);
						
						$query->update($db->quoteName('#__events_events'));
						$query->set($fields);
						$query->where($conditions);
						
						$db->setQuery($query);
						
						$db->query();
					}
					else
					{
						$query	= $db->getQuery(true);
						
						// Sets data to be updated
						$query->set($db->quoteName('status') . ' = ' . (int) $status);
						
						// Sets conditions to change the status for an event.
						$query->where($db->quoteName('event') . ' = ' . $event);
						$query->where($db->quoteName('id') . ' = ' . $player->id);
						
						// Executes Query
						$query->update($db->quoteName('#__events_players'));
						
						// Set the query and execute item
						$db->setQuery($query);							
						$db->query();
					}
				}
			}			
			endforeach;
			
			// Creates the groups if it doesn't exist
			JModelLegacy::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_users/models/', 'UsersModel' );

			$groupModel = JModelLegacy::getInstance( 'Group', 'UsersModel' );

			
			// Refreshes query
			$query	= $db->getQuery(true);
			
			// Gets current event
			$query->select('e.params AS params');
			$query->from('#__events_events AS e');
			
			$query->where('id = ' . $event);
			$result = $db->setQuery($query)->loadResult();
			
			$db->query();
			// Creates Params Array
			$params = json_decode($result, true);
			
			// Refreshes query
			$query	= $db->getQuery(true);
			
			// Gets current event name
			$query->select('e.title AS title');
			$query->from('#__events_events AS e');
			
			$query->where('id = ' . $event);
			$eventName = $db->setQuery($query)->loadResult();
			
			$db->query();
				
			if(empty($params['usergroup']))
			{
				
				// Finds root user group for Events Party!
				$query	= $db->getQuery(true);
				
				$query->select('ug.id AS id');
				$query->from('#__usergroups AS ug');
				
				$query->where($db->quoteName('ug.title') . ' = ' . $db->quote("Events Party! Event Groups"));
				$result = $db->setQuery($query)->loadResult();
				
				$db->query();
				
				if(!empty($result))
				{
					$groupData = array(
					'title' => $data['title'],
					'parent_id' => $result,
					'id' => 0 );

					$groupModel->save( $groupData );
					
					$query	= $db->getQuery(true);
					
					$query->select('ug.id AS id');
					$query->from('#__usergroups AS ug');
					
					$query->where($db->quoteName('ug.title') . ' = ' . $db->quote($data['title']));
					$result = $db->setQuery($query)->loadResult();
									
					$db->query();
					
					if(!empty($result))
					{
						// Creates Checked-In Group
						$groupData = array(
						'title' => 'Checked-In Users',
						'parent_id' => $result,
						'id' => 0 );

						$groupModel->save( $groupData );
					}
					/*
					// Creates Paid Group
					$groupData = array(
					'title' => 'Paid Users',
					'parent_id' => $result,
					'id' => 0 );

					$groupModel->save( $groupData );
					
					// Creates Registered Group 
					$groupData = array(
					'title' => 'Registered Users',
					'parent_id' => $result,
					'id' => 0 );

					$groupModel->save( $groupData );*/
				}
				$usergroup = array('usergroup' => $result);
				$params = array_merge($params, $usergroup);
				
			}	
			else 
			{
				$groupData = array(
				'title' => $eventName,
				'id' => $params['usergroup'] );

				$groupModel->save( $groupData );
				
				$usergroup = null;
			}
			
			$query	= $db->getQuery(true);
			
			$event_start_date = $data['event_start_time'] . ' ' . $data['event_start_hour'] . ':' . $data['event_start_minute'] . ':00';
			$event_end_date = $data['event_end_time'] . ' ' . $data['event_end_hour'] . ':' . $data['event_end_minute'] . ':00';
			
			// Sets data to be updated
			$query->set($db->quoteName('event_start_time') . ' = ' . $db->quote($event_start_date));
			$query->set($db->quoteName('event_end_time') . ' = ' . $db->quote($event_end_date));
			
			// If usergroup has been added
			if(isset($usergroup))
			{
				$query->set($db->quoteName('params') . ' = ' . $db->quote(json_encode($params)));
			}
				
			//$app->enqueueMessage(var_dump($params));
			$query->where($db->quoteName('id') . ' = ' . $db->quote($event));
			
			// Executes Query
			$query->update($db->quoteName('#__events_events'));
			
			// Set the query and execute item
			$db->setQuery($query);							
			$db->query();
			
			// Get event info
			
			
		}
	}
?>