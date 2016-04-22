<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	/**
	 * Events getEvent Model model.
	 *
	 * @pacakge  Examples
	 *
	 * @since   12.1
	 */
	class EventsModelsEvents extends EventsModelsDefault
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
		
		public function listEvents()
		{
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select(
				$this->getState(
					'list.select', 'a.id, a.title, a.alias, a.summary, a.body, a.checked_out, a.checked_out_time, a.category_id, a.players_confirmed, ' . 
					'a.published, a.access, a.created_time, a.ordering, a.language, a.players_current, a.players_max, a.players_prepaid, a.players_prepay, a.event_start_time, a.event_end_time, a.params'
				)
			);
			$query->from('#__events_events AS a');
			
			//Join over the language
			$query->select('l.title AS langugage_title');
			$query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');
			
			
			//Join over the users for the checked out user.
			$query->select('uc.name AS editor');
			$query->join('LEFT', '`#__users` AS uc ON uc.id = a.checked_out');
			
			//Join over the asset groups
			$query->select('ag.title AS access_level');
			$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
			
			// Join over the categories.
			$query->select('c.title AS category_title');
			$query->join('LEFT', '#__categories AS c ON c.id = a.category_id');
			
			// Join over the users for the author.
			$query->select('ua.name AS author_name');
			$query->join('LEFT', '#__users AS ua ON ua.id = a.created_user_id');
			
			// Filter by search in title
			$search = $this->getState('filter.search');
			if (!empty($search)) 
			{
				if (stripos($search, 'id:') === 0) 
				{
					$query->where('a.id = '.(int) substr($search, 3));
				} 
			}
			else 
			{
				$search = $db->Quote('%'.$db->escaped($search, true).'%');
				$query->where('(a.title LIKE '.$search.' OR a.alias LIKE '.$search.')');
			}
			 
			// Filter by access level.
			if ($access = $this->getState('filter.access')) 
			{
				$query->where('a.access = ' . (int) $access);
			}
			 
			// Filter by published state
			/*$published = $this->getState('filter.published');
			if (is_numeric($published)) 
			{
				$query->where('a.published = ' . (int) $published);
			} 
			else if ($published === '') 
			{*/
				// Shows published and Archived Events
				$query->where('(a.published = 1 or a.published = 2 or a.published = -1)');
			//}
			 
			// Filter by a single or group of categories.
			$categoryId = $this->getState('filter.category_id');
			if (is_numeric($categoryId)) {
				$query->where('a.category_id = '.(int) $categoryId);
			}
			else if (is_array($categoryId)) 
			{
				JArrayHelper::toInteger($categoryId);
				$categoryId = implode(',', $categoryId);
				$query->where('a.category_id IN ('.$categoryId.')');
			}
			 
			// Filter on the language.
			if ($language = $this->getState('filter.language')) 
			{
				$query->where('a.language = '.$db->quote($language));
			}
			
			// Filters by user's groups verses the access level set on each event
			$query->where('a.access IN (' . implode(',', JAccess::getAuthorisedViewLevels(JFactory::getUser()->id)) . ')');
			
			// Add the list ordering clause.
			/*$orderCol 		= $this->state->get('list.ordering');
			$orderDirn		= $this->state->get('list.direction');
			if ($orderCol == 'a.ordering' || $orderCol == 'category_title') 
			{
				$orderCol = 'category_title ' . $orderDirn . ', a.ordering';
			}
			$query->order($db->escape($orderCol . ' ' . $orderDirn));*/
			
			$query->order($db->escape('event_end_time' . ' ' . 'desc'));
			//echo nl2br(str_replace('#__','joom_',$query));
			
			
			
			// Runs query
			
			$result = $db->setQuery($query)->loadObjectList();
			$db->query();
			
			return $result;
		}
		
		public function getCurrentUser($pk = null)
		{
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
						
			// Select the required fields from the table.
			$query->select('p.event, p.status AS status, p.params');
			$query->from('#__events_players AS p');
						
			// Selects current user.
			$query->where('p.user = ' . JFactory::getUser()->id);
			
			// Selects only non cancelled entries. (Innactive as of current)
			
			// Runs query
			$result = $db->setQuery($query)->loadObjectList();
			$db->query();
			
			return $result;
		}
	}
?>