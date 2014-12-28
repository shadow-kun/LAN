<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	/**
	 * Events getEvent Model model.
	 *
	 * @pacakge  Examples
	 *
	 * @since   12.1
	 */
	class EventsModelsEvent extends EventsModelsDefault
	{
		/**
		 * Get the time.
		 *
		 * @return  integer
		 *
		 * @since   12.1
		 */
		 
		 function __construct()
		{
			parent::__construct();
		}
		
		public function getEvent($pk = null)
		{
			$user	= JFactory::getUser();

			$pk = (!empty($pk)) ? $pk : (int) $this->getState('events.id');

			/*if ($this->_item === null)
			{
				$this->_item = array();
			}*/

			if (!isset($this->_item[$pk]))
			{
				try
				{
					$db = $this->getDb();
					$query = $db->getQuery(true)
					->select(
						$this->getState(
							'item.select', 'a.id, a.title, a.alias, a.category_id, a.body, a.terms, a.published, a.language, a.params, a.details, a.players_max, a.players_current, ' .
							'a.players_confirmed, a.created_user_id, a.created_time, a.players_prepaid, a.players_prepay, a.event_start_time, a.event_end_time, a.details'
						)
					);
					
					$query->from('#__lan_events AS a');
					
					// Join on category table.
					$query->select('c.title AS category_title')
						->join('LEFT', '#__categories AS c on c.id = a.category_id');

					// Join on user table.
					$query->select('u.name AS author')
						->join('LEFT', '#__users AS u on u.id = a.created_user_id');

					// Filter by published state.
					$published = $this->getState('filter.published');
					$archived = $this->getState('filter.archived');

					if (is_numeric($published))
					{
						$query->where('(a.published = ' . (int) $published . ' OR a.published =' . (int) $archived . ')');
					}
					
					$query->where('a.id = ' . (int) $pk);
					$db->setQuery($query);

					$data = $db->loadObject();

					if (empty($data))
					{
						return JError::raiseError(404, JText::_('COM_LAN_ERROR_EVENT_NOT_FOUND'));
					}
					
					$data->params = json_decode($data->params);
					$registry = new JRegistry;
					//$registry->loadString($data->metadata);
					$data->metadata = $registry;
					
					$this->_item[$pk] = $data;
				}
				catch (Exception $e)
				{
					if ($e->getCode() == 404)
					{
						// Need to go thru the error handler to allow Redirect to work.
						JError::raiseError(404, $e->getMessage());
					}
					else
					{
						$this->setError($e);
						$this->_item[$pk] = false;
					}
				}
				
			}

			return $this->_item[$pk];
		}
		
		public function getCurrentUser($pk = null)
		{
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
						
			// Select the required fields from the table.
			$query->select('p.id AS id, p.event, p.status AS status, p.params');
			$query->from('#__lan_players AS p');
						
			// Selects the event that is required.
			$query->where('p.event = ' . JRequest::getVar('id',NULL));
			
			// Selects current user.
			$query->where('p.user = ' . JFactory::getUser()->id);
			
			// Selects only non cancelled entries. (Innactive as of current)
			
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
			
			return $result;
		}
		
		public function getUsers($pk = null)
		{			
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.event, p.status AS status, p.params');
			$query->from('#__lan_players AS p');
			
			//Join over the users.
			$query->select('u.username AS username');
			$query->join('LEFT', '#__users AS u ON u.id = p.user');
			
			// Selects the event that is required.
			$query->where('p.event = ' . (int) JRequest::getInt('id'));
			
			// Add the list ordering clause.
			$orderCol 		= $this->state->get('list.ordering');
			$orderDirn		= $this->state->get('list.direction');
			/*if ($orderCol == 'p.ordering' || $orderCol == 'id') 
			{
				$orderCol = 'id ' . $orderDirn . ', p.ordering';
			}*/
			//$query->order($db->escape($orderCol . ' ' . $orderDirn));
			
			$query->order('status DESC');
			//echo nl2br(str_replace('#__','joom_',$query));
			$result = $db->setQuery($query)->loadObjectList();
			
			return $result;
		}
		
		public function setConfirmAttendee()
		{
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Gets data to update
			$fields = $db->quoteName('status') . ' = 2';
			
			// Sets the conditions of which event and which player to update
			$conditions = array($db->quoteName('event') . ' = ' . JRequest::getVar('id',NULL,'GET'), $db->quoteName('user') . ' = ' . $user->id);
			
			// Executes Query
			$query->update($db->quoteName('#__lan_players'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			/************************************************/
			
			$query	= $db->getQuery(true);
			
			$confirmedPlayers = $this->items->a.players_confirmed;
			
			$fields = 'players_confirmed' . ' = ' . $confirmedPlayers . ' + 1';

			$conditions = array($db->quoteName('id') . ' = ' . JRequest::getVar('id',NULL,'GET'));
			
			$query->update($db->quoteName('#__lan_events'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			return true;
		}
		
		public function storeAttendee()
		{
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Sets columns
			$colums = array('id', 'event', 'user', 'status', 'params');
			
			// Sets values
			$values = array('NULL',JRequest::getVar('id'), $user->id, '1', 'NULL');
			
			// Prepare Insert Query $db->quoteName('unconfirmed')
			$query  ->insert($db->quoteName('#__lan_players'))
					->columns($db->quoteName($colums))
					->values(implode(',', $values));
			
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			$query	= $db->getQuery(true);
			
			$currentPlayers = $this->items->a.players_current;
			
			$fields = 'players_current' . ' = ' . $currentPlayers . ' + 1';

			$conditions = array($db->quoteName('id') . ' = ' . JRequest::getVar('id',NULL,'GET'));
			
			$query->update($db->quoteName('#__lan_events'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			return true;
		}
		
		public function deleteAttendee()
		{
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			$query->select('a.players_confirmed', 'a.players_current');
			$query->from('#__lan_events AS a');
				
			$query->where('a.id = ' . (int) JRequest::getInt('id',NULL,'GET'));
			$db->setQuery($query);

			$this->event = $db->loadObject();
			
			$query	= $db->getQuery(true);
			
			$currentStatus = $this->currentPlayer->status;
			
			$model = new EventsModelsEvent();
			
			if($model->getCurrentUser()->status == 2)
			{	
				$confirmedPlayers = $this->event->a.players_confirmed;
				$fields = 'players_confirmed' . ' = ' . $confirmedPlayers . ' - 1';

				$conditions = array($db->quoteName('id') . ' = ' . JRequest::getVar('id',NULL,'GET'));
				
				$query->update($db->quoteName('#__lan_events'));
				$query->set($fields);
				$query->where($conditions);
				
				$db->setQuery($query);
				$db->query();
				
				$query	= $db->getQuery(true);
			}
			
			// Sets the conditions of the delete of the user with the event
			$conditions = array($db->quoteName('event') . ' = ' . JRequest::getVar('id',NULL,'GET'), $db->quoteName('user') . ' = ' .  $user->id);
			
			$query->delete($db->quoteName('#__lan_players'));
			$query->where($conditions);
						
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			$query	= $db->getQuery(true);
			
			$currentPlayers = $this->event->a.players_current;
			$fields = 'players_current' . ' = ' . $currentPlayers . ' - 1';

			$conditions = array($db->quoteName('id') . ' = ' . JRequest::getVar('id',NULL,'GET'));
			
			$query->update($db->quoteName('#__lan_events'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			return true;
		}
	}
	
?>