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
	class LANModelCheckin extends JModelItem
	{
		/**
		 * Model context string.
		 *
		 * @var        string
		 */
		protected $_context = 'com_lan.checkin';
				
		protected function populateState()
		{
			$app = JFactory::getApplication('site');

			// Load state from the request.
			$pk = $app->input->getInt('id');
			$this->setState('checkIn.id', $pk);
			
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

			$pk = (!empty($pk)) ? $pk : (int) $this->getState('checkIn.id');

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
							'item.select', 'a.id, a.event, a.params, a.user, a.status'
						)
					);
					
					$query->from('#__lan_players AS a');
					
					// Join on category table.
					$query->select('e.title AS event_name, e.params AS event_params')
						->join('LEFT', '#__lan_events AS e on e.id = a.event');
/*
					// Join on user table.
					$query->select('u.name AS author')
						->join('LEFT', '#__users AS u on u.id = a.created_user_id');

					// Filter by published state.
					$published = $this->getState('filter.published');
					$archived = $this->getState('filter.archived');

					if (is_numeric($published))
					{
						$query->where('(a.published = ' . (int) $published . ' OR a.published =' . (int) $archived . ')');
					}*/
					
					$query->where('a.id = ' . (int) $pk);
					$db->setQuery($query);

					$data = $db->loadObject();

					if (empty($data) and $pk != null)
					{
						return JError::raiseError(404, JText::_('COM_LAN_ERROR_TICKET_NOT_FOUND'));
					}
					
					if($pk != null)
					{
						// Convert parameter fields to objects.
						$registry = new JRegistry;
						$registry->loadString($data->params);

						$data->params = clone $this->getState('params');
						$data->params->merge($registry);
						
						$registry = new JRegistry;
						//$registry->loadString($data->metadata);
						$data->metadata = $registry;
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
						$this->setError($e);
						$this->_item[$pk] = false;
					}
				}
				
			}

			return $this->_item[$pk];
		}
		
		public function getCheckedInGroup()
		{
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
		
			$query->select('a.id');
					
			$query->from('#__usergroups AS a');
				
			$query->where('parent_id = ' . json_decode($this->getItem()->event_params)->usergroup);
					
			//Join on category table.
			/*$query->select('e.title AS event_name')
				->join('LEFT', '#__lan_events AS e on e.id = a.event');
				*/
			$db->setQuery($query);

			$data = $db->loadResult();	
			return $data;
		}
	}