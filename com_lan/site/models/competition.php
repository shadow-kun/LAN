<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	* @package 		LAN
	* @subpackage 	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/

	jimport('joomla.application.component.model');

	/**
	* Message model.
	*
	* @package LAN
	* @subpackage com_lan
	* @since 1.0
	*/
	class LANModelCompetition extends JModelItem
	{
		/**
		 * Model context string.
		 *
		 * @var        string
		 */
		protected $_context = 'com_lan.competition';
		
		protected function populateState()
		{
			$app = JFactory::getApplication('site');

			// Load state from the request.
			$pk = $app->input->getInt('id');
			$this->setState('competitions.id', $pk);
			
			$offset = $app->input->getUInt('limitstart');
			$this->setState('list.offset', $offset);

			// Load the parameters.
			$params = $app->getParams();
			$this->setState('params', $params);
			
			// TODO: Tune these values based on other permissions.
			$user = JFactory::getUser();

			if ((!$user->authorise('core.edit.state', 'com_lan')) && (!$user->authorise('core.edit', 'com_lan')))
			{
				$this->setState('filter.published', 1);
				$this->setState('filter.archived', 2);
			}

			$this->setState('filter.language', JLanguageMultilang::isEnabled());
			
		}	
		
		/**
		 * Method to get event data.
		 *
		 * @param   integer  $pk  The id of the event.
		 *
		 * @return  mixed  Menu item data object on success, false on failure.
		 */
		public function getItem($pk = null)
		{
			$user	= JFactory::getUser();

			$pk = (!empty($pk)) ? $pk : (int) $this->getState('competitions.id');

			if ($this->_item === null)
			{
				$this->_item = array();
			}

			if (!isset($this->_item[$pk]))
			{
				try
				{
					$db = $this->getDbo();
					$query = $db->getQuery(true)
					->select(
						$this->getState(
							'item.select', 'a.id, a.title, a.alias, a.category_id, a.body, a.published, a.language, a.params, ' .
							'a.created_user_id, a.created_time, a.competition_start, a.competition_end'
						)
					);
					
					$query->from('#__lan_competitions AS a');
					
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

					// Convert parameter fields to objects.
					$registry = new JRegistry;
					$registry->loadString($data->params);

					$data->params = clone $this->getState('params');
					$data->params->merge($registry);
					
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
		
		public function getPlayers($pk = null)
		{			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.competition, p.params as params');
			$query->from('#__lan_competition_players AS p');
			
			//Join over the users.
			$query->select('u.username AS username');
			$query->join('LEFT', '#__users AS u ON u.id = p.user');
			
			// Selects the event that is required.
			$query->where('p.competition = ' . JRequest::getVar('id'));
			
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
		
		public function getTeams($pk = null)
		{			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('a.id AS id, a.competition, a.params as params');
			$query->from('#__lan_competition_teams AS a');
			
			//Join over the users.
			$query->select('t.title AS name');
			$query->join('LEFT', '#__lan_teams AS t ON t.id = a.team');
			
			// Selects the event that is required.
			$query->where('a.competition = ' . JRequest::getVar('id'));
			
			// Add the list ordering clause.
			$orderCol 		= $this->state->get('list.ordering');
			$orderDirn		= $this->state->get('list.direction');
			/*if ($orderCol == 'p.ordering' || $orderCol == 'id') 
			{
				$orderCol = 'id ' . $orderDirn . ', p.ordering';
			}*/
			//$query->order($db->escape($orderCol . ' ' . $orderDirn));
			
			$query->order('name');
			//echo nl2br(str_replace('#__','joom_',$query));
			$result = $db->setQuery($query)->loadObjectList();
			
			return $result;
		}
		
		public function getCurrentPlayer($pk = null)
		{
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
						
			// Select the required fields from the table.
			$query->select('p.id AS id, p.competition, p.params');
			$query->from('#__lan_competition_players AS p');
						
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
			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			

			// Join over the team names
			$query->select('t.title AS name, t.id AS id')
				->from('#__lan_teams AS t');
			
			// Select the required fields from the table.
			$query->select('ct.id AS entryid')
				->join('LEFT', '#__lan_competition_teams AS ct ON ct.team = t.id');
			
			// Join the user current status in each team
			$query->join('LEFT', '#__lan_team_players AS tp on tp.team = t.id');

			// Selects the competition that is required.
			//$query->where('ct.competition = ' . JRequest::getVar('id',NULL));
			
			// Selects current user.
			$query->where('tp.user = ' . JFactory::getUser()->id);
			
			// Selects only users of moderator status and above.
			$query->where('tp.status >= 2');
			
			// Runs query
			$result = $db->setQuery($query)->loadObjectList();
			$db->query();
			
			return $result;
		}		
	}