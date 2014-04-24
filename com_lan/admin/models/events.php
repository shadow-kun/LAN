<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	
   /**
	* Events Model
	*
	* @oackage 		LAN
	* @subpackage	com_lan
	*/
	
	jimport('joomla.application.component.modellist');
	
	class LANModelEvents extends JModelList
	{
		/**
		* Moethod to auto-populate the model
		*
		* @param		string	$ordering	An optional ordering field.
		* @param		string	$direction	An optional direction (asc || desc).
		*
		* @oackage 		LAN
		* @subpackage	com_lan
		*/
		protected function populateState($ordering = 'a.title', $direction = 'asc')
		{
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
					'list.select', 'a.id, a.title, a.alias, a.checked_out, a.checked_out_time, a.category_id,' . 
					'a.published, a.access, a.created_time, a.ordering, a.language'
				)
			);
			$query->from('#__lan_events AS a');
			
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
			
			// Add the list ordering clause.
			$orderCol 		= $this->state->get('list.ordering');
			$orderDirn		= $this->state->get('list.direction');
			if ($orderCol == 'a.ordering' || $orderCol == 'category_title') 
			{
				$orderCol = 'category_title ' . $orderDirn . ', a.ordering';
			}
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
			
			//echo nl2br(str_replace('#__','joom_',$query));
			return $query;
		}
	}
?>