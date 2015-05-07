<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	/**
	 * Events getEvent Model model.
	 *
	 * @pacakge  Examples
	 *
	 * @since   12.1
	 */
	class EventsModelsCompetition extends EventsModelsDefault
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
		
		public function getCompetition($pk = null)
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
							'item.select', 'a.id, a.title, a.alias, a.category_id, a.body, a.published, a.language, a.params, ' .
							'a.created_user_id, a.created_time, a.competition_start, a.competition_end'
						)
					);
					
					$query->from('#__events_competitions AS a');
					
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
						return JError::raiseError(404, JText::_('COM_LAN_ERROR_COMPETITION_NOT_FOUND'));
					}
					
					$data->params = json_decode($data->params);
					
					// Loads competition event if linked.
					if(!empty($data->params->competition_event))
					{
						$query = $db->getQuery(true);
						$query->select('title');
						$query->from('#__events_events');
						$query->where('id = ' . $data->params->competition_event);
						$db->setQuery($query);
						$data->event = $db->loadResult();
					}
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
		
		public function canRegister($competition)
		{
			// Gets event data
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			$query->select('params');
			$query->from('#__events_competitions');
			$query->where('id = ' . $competition);
			$cparams = $db->setQuery($query)->loadResult();
			$db->query();
			$cparams = json_decode($cparams);
			$signup = 0;
			
			if($cparams->competition_signup !== '')
			{
				
				$signup = intval($cparams->competition_signup);
			}
			else
			{
				$signup = intval(JComponentHelper::getParams('com_events')->get('competition_signup'));
			}
			
			if($signup == 1)
			{
				// Gets the event params
				$query	= $db->getQuery(true);
				$query->select('params');
				$query->from('#__events_events');
				$query->where('id = ' . $cparams->competition_event);
				$event = $db->setQuery($query)->loadResult();
				$db->query();
				$event = intval(json_decode($event));
			}
			
			
				
			if($signup == 0 || ($signup == 1 && in_array(intval($event->usergroup), JAccess::getGroupsByUser(JFactory::getUser()->id, $true)) == true))
			{
				$return = true;
			}
			else
			{
				$return = false;
			}
			
			return $return;
		}
		
		public function getCurrentUser($pk = null)
		{
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
						
			// Select the required fields from the table.
			$query->select('p.id AS id, p.competition, p.params, p.status AS status');
			$query->from('#__events_competition_players AS p');
						
			// Selects the competition that is required.
			$query->where('p.competition = ' . JRequest::getVar('id',NULL));
			
			// Selects current user.
			$query->where('p.user = ' . JFactory::getUser()->id);
			
			// Selects only non cancelled entries. (Inactive as of current)
			
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
			
			return $result;
		}
		
		public function getCurrentTeams($pk = null)
		{
			/* Function Purpose: To gather teams that the current user has the permissions to act on behalf
			 *
			 * Permission Levels: Team Leader, Team Moderator
			 */
			
			// Get current data on team status on the current user.
			//
			
			
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			

			// Join over the team names
			$query->select('t.title AS name, t.id AS id')
				->from('#__events_teams AS t');
			
			// Select the required fields from the table.
			$query->select('ct.id AS entryid, ct.status AS status')
				->join('LEFT', '#__events_competition_teams AS ct ON ct.team = t.id');
			
			// Join the user current status in each team
			$query->join('LEFT', '#__events_team_players AS tp on tp.team = t.id');

			// Selects the competition that is required.
			//$query->where('ct.competition = ' . JRequest::getVar('id',NULL));
			
			// Selects current user.
			$query->where('tp.user = ' . JFactory::getUser()->id);
			
			// Selects only users of moderator status and above.
			$query->where('tp.status >= 2');
			
			// Runs query
			$result = $db->setQuery($query)->loadObjectList();
			$db->query();
			
			$teams = $this->getTeams();
						
			foreach ($result as $t => $team)
			{				
				if(isset($team->entryid))
				{	
					$registered++;
					if($team->status == -2)
					{
						$forfeit++;
					}
					elseif($team->status == -1)
					{
						$eliminated++;
					}
				}
				else
				{
					$unregistered++;
					
				}
			}
			
			$result['eliminated'] = $eliminated;
			$result['forfeit'] = $forfeit;
			$result['registered'] = $registered;
			$result['unregistered'] = $unregistered;
			
			return $result;
		}
		
		public function getTeams($pk = null)
		{			
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.competition, p.params as params, p.status AS status');
			$query->from('#__events_competition_teams AS p');
			
			//Join over the users.
			$query->select('t.title AS name');
			$query->join('LEFT', '#__events_teams AS t ON t.id = p.team');
			
			// Selects the event that is required.
			$query->where('p.competition = ' . JRequest::getInt('id'));
			
			// Add the list ordering clause.
			$orderCol 		= $this->state->get('list.ordering');
			$orderDirn		= $this->state->get('list.direction');
			/*if ($orderCol == 'p.ordering' || $orderCol == 'id') 
			{
				$orderCol = 'id ' . $orderDirn . ', p.ordering';
			}*/
			//$query->order($db->escape($orderCol . ' ' . $orderDirn));
			
			$query->order('id');
			//echo nl2br(str_replace('#__','joom_',$query));
			$result = $db->setQuery($query)->loadObjectList();
			
			return $result;
		}
		
		public function getUsers($pk = null)
		{			
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.competition, p.params as params, p.status AS status');
			$query->from('#__events_competition_players AS p');
			
			//Join over the users.
			$query->select('u.username AS username');
			$query->join('LEFT', '#__users AS u ON u.id = p.user');
			
			// Selects the event that is required.
			$query->where('p.competition = ' . JRequest::getInt('id'));
			
			// Add the list ordering clause.
			$orderCol 		= $this->state->get('list.ordering');
			$orderDirn		= $this->state->get('list.direction');
			/*if ($orderCol == 'p.ordering' || $orderCol == 'id') 
			{
				$orderCol = 'id ' . $orderDirn . ', p.ordering';
			}*/
			//$query->order($db->escape($orderCol . ' ' . $orderDirn));
			
			$query->order('id');
			//echo nl2br(str_replace('#__','joom_',$query));
			$result = $db->setQuery($query)->loadObjectList();
			
			return $result;
		}
		
		
		
		public function getCompetitionPlayers($competition)
		{
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			$query->select('a.id');
			$query->from('#__events_competition_players AS a');
			
			$query->where('a.competition = ' . (int) $competition);
			
			$db->setQuery($query)->query();
			return $db->getNumRows();
			
		}
		
		public function getCompetitionTeams($competition)
		{
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			$query->select('a.id');
			$query->from('#__events_competition_teams AS a');
			
			$query->where('a.competition = ' . (int) $competition);
			
			$db->setQuery($query)->query();
			return $db->getNumRows();
			
		}
		
		public function storeCompetitionUser($competition, $user)
		{
			$app = JFactory::getApplication();
			
			// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.competition, p.params');
			$query->from('#__events_competition_players AS p');
						
			// Selects the competition that is required.
			$query->where('p.competition = ' . $competition);
			
			// Selects current user.
			$query->where('p.user = ' . $user);
			
			// Selects only non cancelled entries. (Inactive as of current)
			
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
				
			// Checks to see if already registered for this competition
			if(!(isset($result)))
			{
				// Checks signup constraints and verifies the user meets them
				
				// Gets event data
				$query	= $db->getQuery(true);
				$query->select('params');
				$query->from('#__events_competitions');
				$query->where('id = ' . $competition);
				$cparams = $db->setQuery($query)->loadResult();
				$db->query();
				$cparams = json_decode($cparams);
				$signup = 0;
				
				if($cparams->competition_signup !== '')
				{
					
					$signup = intval($cparams->competition_signup);
				}
				else
				{
					$signup = intval(JComponentHelper::getParams('com_events')->get('competition_signup'));
				}
				
				if($signup == 1)
				{
					// Gets the event params
					$query	= $db->getQuery(true);
					$query->select('params');
					$query->from('#__events_events');
					$query->where('id = ' . intval($cparams->competition_event));
					$event = $db->setQuery($query)->loadResult();
					$db->query();
					$event = json_decode($event);
					
					
				}
				
				if($signup == 0 || ($signup == 1 && in_array(intval($event->usergroup), JAccess::getGroupsByUser($user, $true))))
				{
					//Sets JSON Params data
					$params = $db->quote(json_encode(array('status' => 1)));
					
					// Sets columns
					$colums = array('id', 'competition', 'user', 'params');
					
					// Sets values
					$values = array('NULL', $competition, $user, $params);
					
					// Prepare Insert Query $db->quoteName('unconfirmed')
					$query  ->insert($db->quoteName('#__events_competition_players'))
							->columns($db->quoteName($colums))
							->values(implode(',', $values));
					
					// Set the query and execute item
					$db->setQuery($query);
					$db->query();
				}
			}
			
			return true;
			
		}
		
		public function storeCompetitionTeam($competition, $team)
		{
			$app = JFactory::getApplication();
			
			// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.competition, p.params');
			$query->from('#__events_competition_teams AS p');
						
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
			
			return true;
			
		}
		
		public function deleteCompetitionUser($competition, $user)
		{
			$app = JFactory::getApplication();
			
			// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			// Sets the conditions of the delete of the user with the competition
			$conditions = array($db->quoteName('competition') . ' = ' . $competition, $db->quoteName('user') . ' = ' .  $user);
			
			$query->delete($db->quoteName('#__events_competition_players'));
			$query->where($conditions);
						
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			return true;
		}
		
		public function deleteCompetitionTeam($competition, $team)
		{
			$app = JFactory::getApplication();
			
			// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			// Sets the conditions of the delete of the user with the competition
			$conditions = array($db->quoteName('competition') . ' = ' . $competition, $db->quoteName('team') . ' = ' .  $team);
			
			$query->delete($db->quoteName('#__events_competition_teams'));
			$query->where($conditions);
						
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			return true;
		}
		
		public function setCompetitionEntrantStatus($competition, $user, $status = 0)
		{			
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Gets data to update
			$fields = $db->quoteName('status') . ' = ' . ((int) $status);
			
			// Sets the conditions of which event and which player to update
			$conditions = array($db->quoteName('competition') . ' = ' . ((int) $competition), $db->quoteName('user') . ' = ' . ((int) $user));
			
			// Executes Query
			$query->update($db->quoteName('#__events_competition_players'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			return true;
		}
		
		public function setCompetitionTeamStatus($competition, $team, $status = 0)
		{			
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Gets data to update
			$fields = $db->quoteName('status') . ' = ' . ((int) $status);
			
			// Sets the conditions of which event and which player to update
			$conditions = array($db->quoteName('competition') . ' = ' . ((int) $competition), $db->quoteName('team') . ' = ' . ((int) $team));
			
			// Executes Query
			$query->update($db->quoteName('#__events_competition_teams'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			var_dump($fields);
			var_dump($conditions);
			
			$db->query();
			
			return true;
		}
		
	}
	
	
	
?>