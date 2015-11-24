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
	class EventsModelsCheckin extends EventsModelsDefault
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
		
		/**
		 * Method to get event data.
		 *
		 * @param   integer  $pk  The id of the event.
		 *
		 * @return  mixed  Menu item data object on success, false on failure.
		 */
		public function getPlayer($pk = null)
		{
			$user	= JFactory::getUser();

			$pk = (!empty($pk)) ? $pk : (int) $this->getState('checkIn.id');

			if ($this->_item === null)
			{
				$this->_item = array();
			}

			if (!isset($this->_item[$pk]))
			{
				try
				{
					$db = $this->getDb();
					$query = $db->getQuery(true)
					->select(
						$this->getState(
							'item.select', 'a.id AS id, a.event, a.params, a.user, a.status AS status'
						)
					);
					
					$query->from('#__events_players AS a');
					
					// Join on events table.
					$query->select('e.title AS event_name, e.params AS event_params')
						->join('LEFT', '#__events_events AS e on e.id = a.event');
					
					// Join over the users table.
					$query->select('u.username AS username');
					$query->join('LEFT', '#__users AS u ON u.id = a.user');
					
					$query->where('a.id = ' . (int) $pk);
					$db->setQuery($query);

					$data = $db->loadObject();

					if (empty($data) and $pk != null)
					{
						return JError::raiseError(404, JText::_('COM_EVENTS_ERROR_TICKET_NOT_FOUND'));
					}
					
					if($pk != null)
					{
						$data->params = json_decode($data->params);
						$data->event_params = json_decode($data->event_params);
					}
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
						JError::raiseError(500, $e->getMessage());
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
			$query->select('p.id AS id, p.team, p.status, p.params');
			$query->from('#__events_team_players AS p');
						
			// Selects the team that is required.
			$query->where('p.team = ' . JRequest::getVar('id',NULL));
			
			// Selects current user.
			$query->where('p.user = ' . JFactory::getUser()->id);
			
			// Selects only non cancelled entries. (Inactive as of current)
			
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
			
			return $result;
		}
		
		public function getPlayerID($id = null, $event = null)
		{
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
						
			// Select the required fields from the table.
			$query->select('a.id AS id');
			$query->from('#__events_players AS a');
											
			// Selects the team that is required.
			$query->where('a.user = ' . $id);
			
			// Selects current user.
			$query->where('a.event = ' . $event);
						
			// Runs query
			$result = $db->setQuery($query)->loadResult();
			$db->query();
			
			return intval($result);
		}
		
		public function getCheckinGroup($pk = null)
		{
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
		
			$query->select('a.id');
					
			$query->from('#__usergroups AS a');
				
			$query->where('parent_id = ' . $this->getPlayer($pk)->event_params->usergroup);
			$query->where('title = "Checked-In Users"');
			
			$db->setQuery($query);

			$data = $db->loadResult();	
			return $data;
		}
		
		public function setCheckinUser($pk = null)
		{
			$user = JFactory::getUser();
			
			if(empty($pk))
			{
				return false;
			}
			else
			{
				if(JFactory::getUser()->guest || !($user->authorise('core.edit.own', 'com_events')))
				{
					return false;
				}
				else
				{
					$checkinGroup = intval($this->getCheckinGroup($pk));
					$player = intval($this->getPlayer($pk)->user);
					if(empty($checkinGroup))
					{
						return false;
					}
					else
					{
						if(in_array($checkinGroup, JAccess::getGroupsByUser($player, $true)))
						{
							return false;
						}
						else
						{
							return JUserHelper::addUserToGroup($player, $checkinGroup);
						}
					}
				}
			}
		}
		
		public function listEvents()
		{
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select(
				$this->getState(
					'list.select', 'a.id AS id, a.title AS title, a.event_start_time, a.event_end_time'
				)
			);
			$query->from('#__events_events AS a');
			 
			// Filter by published state
			$published = $this->getState('filter.published');
			if (is_numeric($published)) 
			{
				$query->where('a.published = ' . (int) $published);
			} 
			else if ($published === '') 
			{
				// Shows published and Archived Events
				$query->where('(a.published = 1)');
			}
			
			$query->where('a.event_end_time > NOW()');
			
			//echo nl2br(str_replace('#__','joom_',$query));
			
			// Runs query
			
			$result = $db->setQuery($query)->loadObjectList();
			$db->query();
			
			return $result;
		}
		
		
	}