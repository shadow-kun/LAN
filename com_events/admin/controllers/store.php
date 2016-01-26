<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		Events Party!
	* @subpackage	com_events
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	
	jimport('joomla.application.component.controllerform');
	
	/**
	 * Event Sub-Controller
	 *
	 * @package			Events Party!
	 * @subpackage		com_events
	 * @since			0.0
	 */
	 
	class EventsControllerStore extends JControllerForm
	{
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
			}
			
			
			return $result;
		}
		
		public function removeGroup ($key = null, $urlVar = null)
		{
			$app            = JFactory::getApplication();
			$db				= JFactory::getDbo();
			$group			= JRequest::getInt('removeGroup');
			
			// Removes all associated group items
			$query	= $db->getQuery(true);
			
			// Sets table for which the deletion will occur
			$query->delete($db->quoteName('#__events_shop_store_group_items'));
						
			// Sets conditions for the deletion.
			$query->where($db->quoteName('group') . ' = ' . $group);
			
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			// Removes group
			$query	= $db->getQuery(true);
			
			// Sets table for which the deletion will occur
			$query->delete($db->quoteName('#__events_shop_store_groups'));
						
			// Sets conditions for the deletion.
			$query->where($db->quoteName('id') . ' = ' . $group);
			
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			$app->enqueueMessage(JText::_('COM_EVENTS_SHOP_STORE_MSG_GROUP_REMOVED') . $item[0] . $item[1] , 'notice');
			
			//return parent::cancel($key);
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_events&view=store&layout=edit&id=' . JRequest::getInt('id'), false));
		}
		
		public function removeItem ($key = null, $urlVar = null)
		{
			$app            = JFactory::getApplication();
			$db				= JFactory::getDbo();
			$item			= explode('-', JRequest::getVar('removeItem'));
			
			$query	= $db->getQuery(true);
			
			// Sets table for which the deletion will occur
			$query->delete($db->quoteName('#__events_shop_store_group_items'));
						
			// Sets conditions for the deletion.
			$query->where($db->quoteName('group') . ' = ' . $item[0]);
			$query->where($db->quoteName('item') . ' = ' . $item[1]);
			
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			$app->enqueueMessage(JText::_('COM_EVENTS_SHOP_STORE_MSG_ITEM_REMOVED') . $item[0] . $item[1] , 'notice');
			
			
			//return parent::cancel($key);
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_events&view=store&layout=edit&id=' . JRequest::getInt('id'), false));
		}
		
		public function setStoreParameters($key = null, $urlVar = null)
		{
			$app            = JFactory::getApplication();
			$db				= JFactory::getDbo();
			$data           = JRequest::getVar('jform', array(), 'post', 'array');
			
			
			//$app->setUserState('com_events.store.orders.start_date', $data['params']['filter_start_date']);
			$app->setUserState('com_events.store.orders.start_date', JRequest::getVar('orders_start_date'));
			$app->setUserState('com_events.store.orders.end_date', JRequest::getVar('orders_end_date'));
			
			$app->setUserState('com_events.store.orders.status', JRequest::getInt('orders_status'));
			//$app->setUserState('com_events.store.orders.end_date', $data['params']['filter_end_date']);			
			// Reloads Page
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_events&view=store&layout=edit&id=' . JRequest::getInt('id') . '#orders', false));
		}
		
		public function save ($key = null, $urlVar = null)
		{
			$app            = JFactory::getApplication();
			$model          = $this->getModel();
			$table          = $model->getTable();
			$data           = JRequest::getVar('jform', array(), 'post', 'array');
			$db		= JFactory::getDbo();
				
			if(!empty($data['add_group']))
			{
				$query	= $db->getQuery(true);
				// Sets columns
				$colums = array('id', 'title', 'store', 'params', 'items');
				
				// Sets values
				$values = array('NULL', $db->quote($data['add_group']), JRequest::getInt('id'), 'NULL', 'NULL');
				
				// Prepare Insert Query $db->quoteName('unconfirmed')
				$query  ->insert($db->quoteName('#__events_shop_store_groups'))
						->columns($db->quoteName($colums))
						->values(implode(',', $values));
				
				// Set the query and execute item
				$db->setQuery($query);
				$db->query();
				
				// Returns a notice message stating user has been added to the team.
				$app->enqueueMessage(JText::_('COM_EVENTS_SHOP_STORE_MSG_GROUP_ADDED'), 'notice');
			}

			if(empty($data['add_item']) && !empty($data['add_item_group']))
			{
				// Returns a warning message about missing group				
				$app->enqueueMessage(JText::_('COM_EVENTS_SHOP_STORE_MSG_ITEM_MISSING'), 'warning');
			}
			elseif(!empty($data['add_item']) && empty($data['add_item_group']))
			{
				// Returns a warning message about missing the item				
				$app->enqueueMessage(JText::_('COM_EVENTS_SHOP_STORE_MSG_GROUP_MISSING'), 'warning');
			}
			elseif(!empty($data['add_item']) && !empty($data['add_item_group']))
			{
				$query	= $db->getQuery(true);
				
				$query->select('a.group, a.item');
				$query->from('#__events_shop_store_group_items AS a');
							
				// Selects the team that is required.
				$query->where('a.group = ' . $db->quote($data['add_item_group']));
				
				// Selects current user.
				$query->where('a.item = ' . $db->quote($data['add_item']));
				
				// Selects only non cancelled entries. (Inactive as of current)
				
				// Runs query
				$result = $db->setQuery($query)->loadObject();
				$db->query();
					
				// Checks to see if already registered for this team
				if(!(isset($result)))
				{	
					$query	= $db->getQuery(true);
					// Sets columns
					$colums = array('group', 'item');
					
					// Sets values
					$values = array($db->quote($data['add_item_group']), $db->quote($data['add_item']));
					
					// Prepare Insert Query $db->quoteName('unconfirmed')
					$query  ->insert($db->quoteName('#__events_shop_store_group_items'))
							->columns($db->quoteName($colums))
							->values(implode(',', $values));
					
					// Set the query and execute item
					$db->setQuery($query);
					$db->query();
					
					// Returns a notice message stating user has been added to the team.
					$app->enqueueMessage(JText::_('COM_EVENTS_SHOP_STORE_MSG_GROUP_ITEM_ADDED'), 'notice');
				}
				else
				{
					// Returns a warning message stating that the entry already exists.
					$app->enqueueMessage(JText::_('COM_EVENTS_SHOP_STORE_MSG_ITEM_DUPLICATE'), 'warning');
				}
			}
			
			$store = intval(JRequest::getInt('id'));
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('o.id AS id, o.store, o.status, o.amount');
			$query->from('#__events_shop_orders AS o');
						
			// Selects the team that is required.
			$query->where('o.store = ' . $store);
						
			// Runs query
			$result = $db->setQuery($query)->loadObjectList();
			$db->query();
			
			foreach ($result as $o => $order) :
			{
				$status = JRequest::getVar('order_status_change#' . $order->id);
				
				if($order->status != $status)
				{
					
					
					// If status is to remove the user 
					if($status == -2)
					{
						$query	= $db->getQuery(true);
						
						$query	= $db->getQuery(true);
						
						// Sets delete statement and clauses
						$query->delete($db->quoteName('#__events_shop_orders'));
						
						// Sets conditions for a single order.
						$query->where($db->quoteName('store') . ' = ' . $store);
						$query->where($db->quoteName('id') . ' = ' . $order->id);
						
						// Set the query and execute item
						$db->setQuery($query);
						$db->query();
						
						
						$query	= $db->getQuery(true);
						
						$db->query();
						
						// If a payment has been made, record the removal of the payment for logs and auditing purposes.
						if($order->status > 1)
						{
							$amount = -($order->amount);
							
							$query	= $db->getQuery(true);
							
							// Sets columns
							$colums = array('user', 'orderID', 'amount', 'params');
							
							// Sets values
							$values = array(JFactory::getUser()->id, $order->id, $amount, "'" . json_encode(array("payment_method" => "ACP Override", "payment_status" => "Deleted By User")) . "'" );
							//$values = array($db->quote($data['add_item_group']), $db->quote($data['add_item']));
							
							// Prepare Insert Query $db->quoteName('unconfirmed')
							$query  ->insert($db->quoteName('#__events_payments'))
									->columns($db->quoteName($colums))
									->values(implode(',', $values));
							
							// Set the query and execute item
							$db->setQuery($query);
							$db->query();
						}
					}
					else
					{
						$query	= $db->getQuery(true);
						
						// Sets data to be updated
						$query->set($db->quoteName('status') . ' = ' . (int) $status);
						
						// Sets conditions to change the status for an event.
						$query->where($db->quoteName('store') . ' = ' . $store);
						$query->where($db->quoteName('id') . ' = ' . $order->id);
						
						// Executes Query
						$query->update($db->quoteName('#__events_shop_orders'));
						
						// Set the query and execute item
						$db->setQuery($query);							
						$db->query();
						
						// If there is a monetary amount changed for the order, records that in the payment logs
						if(($status < 2 && $order->status > 1) || ($status > 1 && $order->status < 2))
						{
							$amount = 0;
							if($status < 2)
							{
								$amount = -($order->amount);
							}
							else
							{
								$amount = $order->amount;
							}
							$query	= $db->getQuery(true);
							
							// Sets columns
							$colums = array('user', 'orderID', 'amount', 'params');
							
							// Sets values
							$values = array(JFactory::getUser()->id, $order->id, $amount, "'" . json_encode(array("payment_method" => "ACP Override", "payment_status" => "")) . "'" );
							//$values = array($db->quote($data['add_item_group']), $db->quote($data['add_item']));
							
							// Prepare Insert Query $db->quoteName('unconfirmed')
							$query  ->insert($db->quoteName('#__events_payments'))
									->columns($db->quoteName($colums))
									->values(implode(',', $values));
							
							// Set the query and execute item
							$db->setQuery($query);
							$db->query();
						}
					}
				}
								
			}			
			endforeach;
			
			parent::save($key, $urlVar);
		}
		
		
	}
?>