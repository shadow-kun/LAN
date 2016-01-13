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
	class EventsModelEvent extends JModelAdmin
	{
		
		/**
		* Method to get the Event form.
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
				
				// Creates Params Array
				$result->params = (object) $result->params;
				
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
				
				// gets hours and minutes for drop down boxes
				$result->event_start_hour = substr($result->event_start_time, 11, 2);
				$result->event_start_minute = substr($result->event_start_time, 14, 2);
				$result->event_start_time = substr($result->event_start_time, 0, 10);
				
				// gets hours and minutes for drop down boxes
				$result->event_end_hour = substr($result->event_end_time, 11, 2);
				$result->event_end_minute = substr($result->event_end_time, 14, 2);
				$result->event_end_time = substr($result->event_end_time, 0, 10);
				
				
				// gets hours and minutes for drop down boxes
				$result->registration_open_hour = substr($result->params->registration_open_time, 11, 2);
				$result->registration_open_minute = substr($result->params->registration_open_time, 14, 2);
				
				// gets hours and minutes for drop down boxes
				$result->registration_confirmation_hour = substr($result->params->registration_confirmation_time, 11, 2);
				$result->registration_confirmation_minute = substr($result->params->registration_confirmation_time, 14, 2);
				
				// gets hours and minutes for drop down boxes
				$result->registration_close_hour = substr($result->params->registration_close_time, 11, 2);
				$result->registration_close_minute = substr($result->params->registration_close_time, 14, 2);
			}
			
			return $result;
		}
		
		public function getPlayers($pk = null)
		{			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.event, p.status AS status, p.params');
			$query->from('#__events_players AS p');
			
			//Join over the events.
			//$query->select('e.event AS event');
			//$query->join('LEFT', '`#__events_events` AS e ON e.id = p.event');
			
			//Join over the users.
			$query->select('u.username AS username');
			$query->join('LEFT', '#__users AS u ON u.id = p.user');
			
			// Selects the event that is required.
			$id = (int) JRequest::getVar('id');
			$query->where('p.event = ' . $id);
			
			// Add the list ordering clause.
			$orderCol 		= $this->state->get('list.ordering');
			$orderDirn		= $this->state->get('list.direction');
			/*if ($orderCol == 'p.ordering' || $orderCol == 'id') 
			{
				$orderCol = 'id ' . $orderDirn . ', p.ordering';
			}*/
			//$query->order($db->escape($orderCol . ' ' . $orderDirn));
			
			$query->order('p.user');
			//echo nl2br(str_replace('#__','joom_',$query));
			$result = $db->setQuery($query)->loadObjectList();
			
			return $result;
		}
		
		public function getPlayersStats($pk = null)
		{			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
		}
		
		/**
		* A protected method to get a set of ordering conditions.
		*
		* @param JTable $table A record object.
		*
		* @return array An array of conditions to add to add to ordering queries.
		* @since 0.0
		*/
		public function getPayments($pk = null)
		{
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.created_time AS created_time, p.user AS user, p.transaction_id AS transaction_id, p.userEventID AS eventID, p.amount AS amount,' . 
							'p.currency AS currency, p.params AS params');
			$query->from('#__events_payments AS p');
						
			// Selects the store that is required.
			$event = JRequest::getInt('id');
			$query->join('LEFT', '#__events_players AS u ON u.id = p.userEventID');
			$query->where('u.event = ' . $event);
			
			$startdate = JRequest::getVar('startdate');
			if(!empty($startdate))
			{
				$date = new JDate($startdate);
				$query->where('p.created_date >= ' . $db->quote($date->tosql(true)));
			}
			
			$enddate = JRequest::getVar('enddate');
			if(!empty($enddate))
			{
				$date = new JDate($enddate);
				$query->where('p.created_date <= ' . $db->quote($date->tosql(true)));
			}
			
			$query->order('p.id ASC');
			//echo nl2br(str_replace('#__','joom_',$query));
			$result = $db->setQuery($query)->loadObjectList();
			$db->query();
			
			foreach($result AS $r => $row)
			{
				$result[$r]->params = json_decode($row->params);
			}
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
		public function getTable($type = 'Event', $prefix = 'EventsTable', $config = array())
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
			
			// Checks for duplicate aliases			
			//die('Result: ' . $this->aliasDuplicateCheck($table->alias, $table->id));
			if($this->aliasDuplicateCheck($table->alias , $table->id) === true)
			{
				// If there is a duplicate, then add a number to the end of the alias and retry until that alias is free.
				$duplicateID = 1;
			
				while ($this->aliasDuplicateCheck($table->alias . '-' . $duplicateID, $table->id) === true)
				{
					$duplicateID++;
				}
				$table->alias = $table->alias . '-' . $duplicateID;
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
					$query->from('#__events_events AS a');
					
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
		
		/**
		* Build an SQL query  to load the list data.
		*
		* @return 		JDatabaseQuery
		* @since		0.0
		*/
		
		protected function getPlayerListQuery()
		{	
			
		}
		
		protected function aliasDuplicateCheck($alias, $id)
		{
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('e.id AS id');
			$query->from('#__events_events AS e');
						
			// Selects the store that is required.
			$query->where('e.alias = ' . $db->quote($alias));
			$result = $db->setQuery($query)->loadObjectList();
			$db->query();
			
			// verifies number of results and returns a result appropriate.
			if ($db->getNumRows() == 0)
			{
				return false;
			}
			if ($db->getNumRows() == 1)
			{
				// Needs to be verified that the result is the current item.
				if($id == $result[0]->id)
				{
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return true;
			}
		}
	}