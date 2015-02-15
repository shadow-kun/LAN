<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		Events Party!
	* @subpackage	com_events
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	
   /**
	* Players Model
	*
	* @oackage 		Events Party!
	* @subpackage	com_events
	*/
	
	jimport('joomla.application.component.modellist');
	
	class EventsModelPlayers extends JModelList
	{
		/**
		* Constructor override.
		*
		* @param array $config An optional associative array of configuration settings.
		*
		* @return $EventsModelPlayers
		* @since 0.0
		* @see JModelList
		*/
		
		public function __construct($config = array())
		{
			if (empty($config['filter_fields'])) 
			{
				$config['filter_fields'] = array(
				'id', 'a.id',
				'user', 'a.user',
				'checked_in', 'a.checked_in',
				'event', 'e.event',
				'status', 'a.status',
				);
			}
		 
			parent::__construct($config);
		}
		
		/**
		* Method to auto-populate the model
		*
		* @param		string	$ordering	An optional ordering field.
		* @param		string	$direction	An optional direction (asc || desc).
		*
		* @oackage 		LAN
		* @subpackage	com_lan
		*/
		protected function populateState($ordering = 'a.id', $direction = 'desc')
		{
			$app = JFactory::getApplication();
 
			$value = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
			$this->setState('filter.search', $value);
 
			/*$value = $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
			$this->setState('filter.access', $value);
 
			$value = $app->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
			$this->setState('filter.published', $value);
 
			$value = $app->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id');
			$this->setState('filter.category_id', $value);
 
			$value = $app->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
			$this->setState('filter.language', $value);*/
			
			// Set list state ordering defaults.
			parent::populateState($ordering, $direction);
		}
		
		/**
		* Build an SQL query  to load the list data.
		*
		* @return 		JDatabaseQuery
		* @since		0.0
		*/
		
		protected function getListQuery()
		{	
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select(
				$this->getState(
					'list.select', 'a.id, a.status, a.checked_in'
				)
			);
			$query->from('#__lan_players AS a');
			
			//Join over the events
			$query->select('e.title AS event');
			$query->join('LEFT', '`#__lan_events` AS e ON e.id = a.event');
			
			
			//Join over the users.
			$query->select('uc.name AS user');
			$query->join('LEFT', '`#__users` AS uc ON uc.id = a.user');
			
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
				$query->where('(uc.name LIKE '.$search.' OR e.title LIKE '.$search.')');
			}
			 
			// Filter by access level.
			/*if ($access = $this->getState('filter.access')) 
			{
				$query->where('a.access = ' . (int) $access);
			}
			 
			// Filter by published state
			$published = $this->getState('filter.published');
			if (is_numeric($published)) 
			{
				$query->where('a.published = ' . (int) $published);
			} 
			else if ($published === '') 
			{
				$query->where('(a.published = 0 OR a.published = 1)');
			}*/
			 
			// Filter by a single or group of events.
			$categoryId = $this->getState('filter.event');
			if (is_numeric($categoryId)) {
				$query->where('a.event = '.(int) $categoryId);
			}
			else if (is_array($categoryId)) 
			{
				JArrayHelper::toInteger($categoryId);
				$categoryId = implode(',', $categoryId);
				$query->where('a.event IN ('.$categoryId.')');
			}
			
			 
			// Filter on the language.
			/*if ($language = $this->getState('filter.language')) 
			{
				$query->where('a.language = '.$db->quote($language));
			}
			*/
			// Add the list ordering clause.
			$orderCol 		= $this->state->get('list.ordering');
			$orderDirn		= $this->state->get('list.direction');
			if ($orderCol == 'a.id' || $orderCol == 'id') 
			{
				$orderCol = 'id ' . $orderDirn . ', a.id';
			}
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
			
			//echo nl2br(str_replace('#__','joom_',$query));
			return $query;
		}
		
		/**
		* Method to get a store id based on model configuration state.
		*
		* This is necessary because the model is used by the component and
		* different modules that might need different sets of data or different
		* ordering requirements.
		*
		* @param string $id A prefix for the store id.
		*
		* @return string A store id.
		* @since 0.0
		*/
		
		protected function getStoreId($id = '')
		{
			// Compile the store id.
			$id .= ':'.$this->getState('filter.search');
			$id .= ':'.$this->getState('filter.event');
			/*$id .= ':'.$this->getState('filter.access');
			$id .= ':'.$this->getState('filter.published');
			$id .= ':'.$this->getState('filter.language');*/
		 
			return parent::getStoreId($id);
		}
	}