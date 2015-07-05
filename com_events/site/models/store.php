<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	/**
	 * Events getEvent Model model.
	 *
	 * @pacakge  Examples
	 *
	 * @since   12.1
	 */
	class EventsModelsStore extends EventsModelsDefault
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
		
		public function getStore($pk = null)
		{
			$user	= JFactory::getUser();

			$pk = (!empty($pk)) ? $pk : (int) $this->getState('events.id');

			/*if ($this->_item === null)
			{
				$this->_item = array();
			}*/

			if (!isset($this->_item[$pk]))
			{
				try
				{
					$db = $this->getDb();
					$query = $db->getQuery(true)
					->select(
						$this->getState(
							'item.select', 'a.id, a.title, a.alias, a.body, a.published, a.language, a.params, ' .
							'a.created_user_id, a.created_time'
						)
					);
					
					$query->from('#__events_shop_stores AS a');
					

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
						return JError::raiseError(404, JText::_('COM_EVENTS_ERROR_STORE_NOT_FOUND'));
					}
					
					$data->params = json_decode($data->params);
					
					// Loads competition event if linked.
					/*if(!empty($data->params->competition_event))
					{
						$query = $db->getQuery(true);
						$query->select('title');
						$query->from('#__events_shop_stores');
						$query->where('id = ' . $data->params->competition_event);
						$db->setQuery($query);
						$data->event = $db->loadResult();
					}*/
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
						JError::raiseError(500, $e->getMessage());
						$this->_item[$pk] = false;
					}
				}
				
			}

			return $this->_item[$pk];
		}
		
		public function getGroups($pk = null)
		{
			$pk = (!empty($pk)) ? $pk : (int) $this->getState('events.id');

			/*if ($this->_item === null)
			{
				$this->_item = array();
			}*/

			if (isset($pk))
			{
				try
				{
					$db		= $this->getDb();
					$query	= $db->getQuery(true);
					
					// Select the required fields from the table.
					$query->select('g.id AS id, g.store, g.title, g.params, g.items');
					$query->from('#__events_shop_store_groups AS g');
								
					// Selects the store that is required.
					$query->where('g.store = ' . $pk);
					
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
						$result = false;
					}
				}
				
			}

			return $result;
		}
		
		public function getOrders($store = null, $order = null)
		{
			
			/*if ($this->_item === null)
			{
				$this->_item = array();
			}*/

				try
				{
					$db		= $this->getDb();
					$query	= $db->getQuery(true);
					
					// Select the required fields from the table.
					$query->select('o.id, o.user, o.store, o.status, o.amount, o.params, o.items');
					$query->from('#__events_shop_orders AS o');
								
					// Selects the store that is required.
					if(!empty($store))
					{
						$query->where('o.store = ' . $store);
					}
					else 
					{
						if(!empty($order))
						{
							$query->where('o.id = ' . $order);
						}
					
						// Sets user
						$query->where('o.user = ' . JFactory::getUser()->id);
					}
					
					$query->where('o.items NOT LIKE "[]"');
					
					// Sets order as last entry first
					$query->order('o.id DESC');
					
					//echo nl2br(str_replace('#__','joom_',$query));
					$result = $db->setQuery($query)->loadObjectList();
					$db->query();
					
					//$result->items = json_decode($result->items);
					//$result->items = json_decode($result->items);
					foreach($result as $r => $row)
					{
						$result[$r]->items = json_decode($row->items);
					}
					
					foreach($result as $r => $row)
					{
						// Adds items into results
						//$result[$r]->items = json_decode($row->items, true);
						
						foreach($row->items as $i => $item) 
						{
							if($item->id > 0)
							{
								$query	= $db->getQuery(true);
								$query->select('i.title');
								$query->from('#__events_shop_items AS i');
								
								$query->where('i.id = ' . $item->id);
								
								
								$title = $db->setQuery($query)->loadResult();
								$db->query();

								$result[$r]->items[$i]->title = $title;	
							}
						}					
					}
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
						$result = false;
					}
				}
				

			return $result;
		}
		
		public function storeOrder($store = null, $user = null)
		{
			// Gets current user info
			if(!isset($user))
			{
				$user	= JFactory::getUser()->id;
			}
			
			if($user == 0)
			{
				return false;
			}
			
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
				
				$items = array();
				$amount = 0;
			
			// Sets default values
			if(isset($store) && intval($store) > 0)
			{
				
				$groups = $this->getGroups(intval($store));
				$s = 0;
				
				foreach($groups as $g => $group) :
					foreach($group->items as $i => $item) :
						$quantity = JRequest::getInt('item' . $group->id . '-' . $item->id);
						if(isset($quantity) && intval($quantity) > 0)
						{
							$items[$s] = (object) array('id' => $item->id, 'amount' => $item->amount, 'quantity' => $quantity, 'status' => 0);
							$amount += $item->amount * $quantity;
							$s++;
						}
					endforeach;
				endforeach;
			}
			
			// Sets columns
			$colums = array('id', 'user', 'store', 'status', 'amount', 'items', 'params');
			
			// Sets values
			$values = array('NULL', $user, intval($store), '1', $amount, $db->quote(json_encode($items)), 'NULL');
			
			// Prepare Insert Query $db->quoteName('unconfirmed')
			$query  ->insert($db->quoteName('#__events_shop_orders'))
					->columns($db->quoteName($colums))
					->values(implode(',', $values));
			
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			$query	= $db->getQuery(true);
			
			return true;
		}
	}
	
	
	
?>