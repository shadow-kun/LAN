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
	class EventsModelStore extends JModelAdmin
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
				
				$startdate = JRequest::getVar('startdate');
				
				if (!empty($startdate)) 
				{
					
					$result->params['filter_start_date'] = $startdate;
				}
				
				$enddate = JRequest::getVar('enddate');
				
				if (!empty($enddate)) 
				{
					
					$result->params['filter_end_date'] = $enddate;
				}
				
				$status = JRequest::getInt('status');
				if (!empty($enddate)) 
				{
					
					$result->params['filter_status'] = $status;
				}
					
			}
			
			
			return $result;
		}
		
		/**
		* Method to get an Event.
		*
		* @param integer An optional id of the object to get, otherwise the id from the model state is used.
		* @return mixed Category data object on success, false on failure.
		* @since 1.6
		*/
		
		public function getGroups($pk = null)
		{
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('g.id AS id, g.store, g.title, g.params, g.items');
			$query->from('#__events_shop_store_groups AS g');
						
			// Selects the store that is required.
			$query->where('g.store = ' . JRequest::getInt('id'));
			
			$query->order('g.title ASC');
			//echo nl2br(str_replace('#__','joom_',$query));
			$result = $db->setQuery($query)->loadObjectList();
			$db->query();
			
			foreach($result as $group => $g)
			{
				$query	= $db->getQuery(true);
				$query->select('i.id AS id, i.title, i.alias, i.body, i.amount');
				$query->from('#__events_shop_items AS i');
				
				
				//Join over the users.
				//$query->select('gi.group AS group');
				$query->join('LEFT', '#__events_shop_store_group_items AS gi ON gi.item = i.id');
				
				$query->where('gi.group = ' . $g->id);
				$query->order('i.title ASC');
				
				$items = $db->setQuery($query)->loadObjectList();
				$db->query();
				
				$result[$group]->items = $items;
				
			}
			
			return $result;
		}
		
		/**
		* Method to get an Event.
		*
		* @param integer An optional id of the object to get, otherwise the id from the model state is used.
		* @return mixed Category data object on success, false on failure.
		* @since 1.6
		*/
		
		public function getOrders($pk = null)
		{
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('o.id AS id, o.created_date, o.user, o.store, o.amount, o.status, o.note, o.items, o.params');
			$query->from('#__events_shop_orders AS o');
						
			// Selects the store that is required.
			$store = JRequest::getInt('id');
			if(!empty($store))
			{
				$query->where('o.store = ' . $store);
			}
			
			$startdate = JRequest::getVar('startdate');
			if(!empty($startdate))
			{
				$date = new JDate($startdate);
				$query->where('o.created_date >= ' . $db->quote($date->tosql(true)));
			}
			
			$enddate = JRequest::getVar('enddate');
			if(!empty($enddate))
			{
				$date = new JDate($enddate);
				$query->where('o.created_date <= ' . $db->quote($date->tosql(true)));
			}
			
			$status = JRequest::getInt('status');
			if(!empty($status))
			{
				switch($status)
				{
					// Unpaid
					case 0: case 1:
						$query->where('o.status = 0 OR o.status = 1');
						break;
					// Paid but not collected
					case 2:
						$query->where('o.status = 2');
						break;
					// Collected only
					case 3:
						$query->where('o.status = 3');
						break;
					// Paid or Collected
					case 4:
						$query->where('o.status = 2 OR o.status = 3');
						break;
				}
			}
			
			$query->order('o.id ASC');
			//echo nl2br(str_replace('#__','joom_',$query));
			$result = $db->setQuery($query)->loadObjectList();
			$db->query();
			
			foreach($result as $o => $order)
			{
				$items = json_decode($order->items);
				
				foreach($items as $i => $item)
				{
					$query	= $db->getQuery(true);
					$query->select('i.title');
					$query->from('#__events_shop_items AS i');
					$query->where('i.id = ' . $item->id);
					
					$title = $db->setQuery($query)->loadResult();	
					$db->query();			
					$items[$i]->title = $title;
					
					
				}
				
				$result[$o]->items = $items;
				
			}
			
			return $result;
		}
		
		public function getOrdersSummary($pk = null)
		{
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('o.id AS id, o.store, o.amount, o.status, o.note, o.items, o.params');
			$query->from('#__events_shop_orders AS o');
						
			// Selects the store that is required.
			$store = JRequest::getInt('id');
			if(!empty($store))
			{
				$query->where('o.store = ' . $store);
			}
			
			$startdate = JRequest::getVar('startdate');
			if(!empty($startdate))
			{
				$date = new JDate($startdate);
				$query->where('o.created_date >= ' . $db->quote($date->tosql(true)));
			}
			
			$enddate = JRequest::getVar('enddate');
			if(!empty($enddate))
			{
				$date = new JDate($enddate);
				$query->where('o.created_date <= ' . $db->quote($date->tosql(true)));
			}
			
			$status = JRequest::getInt('status');
			if(!empty($status))
			{
				switch($status)
				{
					// Unpaid
					case 0: case 1:
						$query->where('o.status = 0 OR o.status = 1');
						break;
					// Paid but not collected
					case 2:
						$query->where('o.status = 2');
						break;
					// Collected only
					case 3:
						$query->where('o.status = 3');
						break;
					// Paid or Collected
					case 4:
						$query->where('o.status = 2 OR o.status = 3');
						break;
				}
			}
			
			$query->order('o.id ASC');
			//echo nl2br(str_replace('#__','joom_',$query));
			$result = $db->setQuery($query)->loadObjectList();
			$db->query();
			
			$summaryItems = array();
			foreach($result as $o => $order)
			{
				$items = json_decode($order->items);
				
				foreach($items as $i => $item)
				{
					
					
					// Sort each item and quantity so that they are grouped together. 
					$summaryItems[$item->id][$order->status]['quantity'] += $item->quantity; 
					$summaryItems[$item->id][$order->status]['amount'] += $item->amount * $item->quantity;
				}
			}
			
			//$summaryItems = (object) $summaryItems;
			
			foreach($summaryItems as $i => $item)
			{
				$query	= $db->getQuery(true);
				$query->select('i.title');
				$query->from('#__events_shop_items AS i');
				$query->where('i.id = ' . $i);
				
				$title = $db->setQuery($query)->loadResult();	
				$db->query();			
				$summaryItems[$i]['title'] = $title;
			}
			
			return $summaryItems;
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
			$query->select('p.id AS id, p.created_time AS created_time, p.user AS user, p.transaction_id AS transaction_id, p.orderID AS orderID, p.amount AS amount,' . 
							'p.currency AS currency, p.params AS params');
			$query->from('#__events_payments AS p');
						
			// Selects the store that is required.
			$store = JRequest::getInt('id');
			
			$query->join('LEFT', '#__events_shop_orders AS so ON p.orderID = so.id');
				
			$query->where('so.store = ' . $store);
			
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
		public function getTable($type = 'Store', $prefix = 'EventsTable', $config = array())
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
					$query->from('#__events_shop_stores AS a');
					
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