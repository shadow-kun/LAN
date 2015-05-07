<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	* @package 		Events Party!
	* @subpackage 	com_events
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/

	jimport('joomla.application.component.modeladmin');

	/**
	* Message model.
	*
	* @package Events Party!
	* @subpackage com_events
	* @since 1.0
	*/
	class EventsModelCompetition extends JModelAdmin
	{
		
		/**
		* Method to get the Competition form.
		*
		* @param array $data An optional array of data for the form to interogate.
		* @param boolean $loadData True if the form is to load its own data (default case), false if not.
		*
		* @return JForm A JForm object on success, false on failure
		* @since 0.0
		*/
		public function getForm($data = array(), $loadData = true)
		{
			// Get the form.
			$form = $this->loadForm(
				$this->option.'.'.$this->name,
				$this->getName(),
				array('control' => 'jform', 'load_data' => $loadData)
			);

			if (empty($form)) 
			{
				return false;
			}

			return $form;
		}

		/**
		* Method to get an Event.
		*
		* @param integer An optional id of the object to get, otherwise the id from the model state is used.
		* @return mixed Category data object on success, false on failure.
		* @since 1.6
		*/
		public function getItem($pk = null)
		{
			if ($result = parent::getItem($pk)) 
			{
				// Convert the created and modified dates to local user time for display in the form.
				jimport('joomla.utilities.date');
				$tz	= new DateTimeZone(JFactory::getApplication()->getCfg('offset'));

				if (intval($result->created_time)) 
				{
					$date = new JDate($result->created_time);
					$date->setTimezone($tz);
					$result->created_time = $date->tosql(true);
				} 
				else 
				{
					$result->created_time = null;
				}

				if (intval($result->modified_time)) {
					$date = new JDate($result->modified_time);
					$date->setTimezone($tz);
					$result->modified_time = $date->tosql(true);
				} 
				else 
				{
					$result->modified_time = null;
				}
				
			}
			
			
			return $result;
		}
		
		public function getEvents($pk = null)
		{			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('e.id AS id, e.title');
			$query->from('#__events_events AS e');
									
			// Selects the event that is required.
			$id = (int) JRequest::getVar('id');
			$query->where('e.published = 1 or e.published = 2 or e.published = -1');
			
			// Add the list ordering clause.
			/*if ($orderCol == 'p.ordering' || $orderCol == 'id') 
			{
				$orderCol = 'id ' . $orderDirn . ', p.ordering';
			}*/
			//$query->order($db->escape($orderCol . ' ' . $orderDirn));
			
			$query->order('e.title');
			//echo nl2br(str_replace('#__','joom_',$query));
			$result = $db->setQuery($query)->loadObjectList();
			
			return $result;
		}
		
		public function getPlayers($pk = null)
		{			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.competition, p.params');
			$query->from('#__events_competition_players AS p');
			
			//Join over the competitions.
			$query->select('c.id AS competition');
			$query->join('LEFT', '`#__events_competitions` AS c ON c.id = p.competition');
			
			//Join over the users.
			$query->select('u.username AS username');
			$query->join('LEFT', '#__users AS u ON u.id = p.user');
			
			// Selects the event that is required.
			$id = (int) JRequest::getVar('id');
			$query->where('p.competition = ' . $id);
			
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
			$query->select('ct.id AS id, ct.competition, ct.params');
			$query->from('#__events_competition_teams AS ct');
			
			//Join over the competitions.
			$query->join('LEFT', '`#__events_competitions` AS c ON c.id = ct.competition');
			
			//Join over the users.
			$query->select('t.title AS name');
			$query->join('LEFT', '#__events_teams AS t ON t.id = ct.team');
			
			// Selects the competition that is required.
			$id = (int) JRequest::getVar('id');
			$query->where('ct.competition = ' . $id);
			
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

		/**
		* A protected method to get a set of ordering conditions.
		*
		* @param JTable $table A record object.
		*
		* @return array An array of conditions to add to add to ordering queries.
		* @since 0.0
		*/
		protected function getReorderConditions($table = null)
		{
			$condition = array(
				'category_id = '.(int) $table->category_id
			);

			return $condition;
		}

		/**
		* Returns a reference to the a Table object, always creating it.
		*
		* @param type 	$type The table type to instantiate
		* @param string $prefix A prefix for the table class name.
		* @param array 	$config Configuration array for model.
		*
		* @return JTable A database object
		* @since 0.0
		*/
		public function getTable($type = 'Competition', $prefix = 'EventsTable', $config = array())
		{
			return JTable::getInstance($type, $prefix, $config);
		}

		/**
		* Method to get the data that should be injected in the form.
		*
		* @return mixed The data for the form.
		* @since 1.0
		*/
		protected function loadFormData()
		{
			// Check the session for previously entered form data.
			$data = JFactory::getApplication()->getUserState($this->option.'.edit.'.$this->getName().'.data', array());

			if (empty($data)) 
			{
				$data = $this->getItem();
			}

			return $data;
		}

		/**
		* Prepare and sanitise the table prior to saving.
		*
		* @param JTable $table The table object for the record.
		*
		* @return boolean True if successful, otherwise false and the error is set.
		* @since 1.0
		*/
		protected function prepareTable($table)
		{
			jimport('joomla.filter.output');

			// Prepare the alias.
			$table->alias = JApplication::stringURLSafe($table->alias);

			// If the alias is empty, prepare from the value of the title.
			if (empty($table->alias)) 
			{
				$table->alias = JApplication::stringURLSafe($table->title);
			}

			if (empty($table->id)) 
			{
				// For a new record.

				// Set ordering to the last item if not set
				if (empty($table->ordering)) 
				{
					$db	= JFactory::getDbo();
					$query	= $db->getQuery(true);
					
					$query->select('MAX(ordering)');
					$query->from('#__events_competitions AS a');
					
					$query->where('a.category_id = '.(int) $table->category_id);
					
					$max = (int) $db->setQuery($query)->loadResult();

					if ($error = $db->getErrorMsg()) 
					{
						$this->setError($error);
						return false;
					}

					$table->ordering = $max + 1;
				}
			}

			// Clean up keywords -- eliminate extra spaces between phrases
			// and cr (\r) and lf (\n) characters from string
			if (!empty($this->metakey)) 
			{
				// Only process if not empty.

				// array of characters to remove.
				$strip = array("\n", "\r", '"', '<', '>');

				// Remove bad characters.
				$clean = JString::str_ireplace($strip, ' ', $this->metakey);

				// Create array using commas as delimiter.
				$oldKeys = explode(',', $clean);
				$newKeys = array();

				foreach ($oldKeys as $key) 
				{
					// Ignore blank keywords
					if (trim($key)) 
					{
					$newKeys[] = trim($key);
					}
				}

				// Put array back together, comma delimited.
				$this->metakey = implode(', ', $newKeys);
			}
		}
	}