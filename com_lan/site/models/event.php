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
	class LANModelEvent extends JModelItem
	{
		/**
		 * Model context string.
		 *
		 * @var        string
		 */
		protected $_context = 'com_lan.event';
		
		protected function populateState()
		{
			$app = JFactory::getApplication('site');

			// Load state from the request.
			$pk = $app->input->getInt('id');
			$this->setState('events.id', $pk);
			
			$offset = $app->input->getUInt('limitstart');
			$this->setState('list.offset', $offset);

			// Load the parameters.
			$params = $app->getParams();
			$this->setState('params', $params);
			
			// TODO: Tune these values based on other permissions.
			$user = JFactory::getUser();

			if ((!$user->authorise('core.edit.state', 'com_content')) && (!$user->authorise('core.edit', 'com_content')))
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

			$pk = (!empty($pk)) ? $pk : (int) $this->getState('events.id');

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
							'item.select', 'a.id, a.title, a.alias, a.category_id, a.body, a.published, a.language, a.params, a.details, a.players_max, a.players_current, ' .
							'a.created_user_id, a.created_time, a.players_prepaid, a.players_prepay, a.event_start_time, a.event_end_time'
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

					// Convert parameter fields to objects.
					$registry = new JRegistry;
					$registry->loadString($data->details);

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
		
		
	}