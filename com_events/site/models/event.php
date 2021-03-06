<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	/**
	 * Events getEvent Model model.
	 *
	 * @pacakge  Examples
	 *
	 * @since   12.1
	 */
	class EventsModelsEvent extends EventsModelsDefault
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
		
		public function getEvent($pk = null)
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
							'item.select', 'a.id, a.title, a.alias, a.category_id, a.body, a.terms, a.published, a.language, a.params, a.details, a.players_max, a.players_current, ' .
							'a.players_confirmed, a.created_user_id, a.created_time, a.players_prepaid, a.players_prepay, a.event_start_time, a.event_end_time, a.details'
						)
					);
					
					$query->from('#__events_events AS a');
					
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

					if (empty($data) || ((int) $data->published == -2 || (int) $data->published == 0))
					{
						return JError::raiseError(404, JText::_('COM_EVENTS_ERROR_EVENT_NOT_FOUND'));
					}
					
					
					$data->params = json_decode($data->params);
					$registry = new JRegistry;
					//$registry->loadString($data->metadata);
					$data->metadata = $registry;
					
					$this->_item[$pk] = $data;
				}
				catch (Exception $e)
				{
					if ($e->getCode() == 403)
					{
						// Need to go thru the error handler to allow Redirect to work.
						JError::raiseError(403, $e->getMessage());
						$this->_item[$pk] = false;
					}
					else if ($e->getCode() == 404)
					{
						// Need to go thru the error handler to allow Redirect to work.
						JError::raiseError(404, $e->getMessage());
						$this->_item[$pk] = false;
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
		
		public function getCurrentUser($pk = null)
		{
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
						
			// Select the required fields from the table.
			$query->select('p.id AS id, p.event, p.status AS status, p.params');
			$query->from('#__events_players AS p');
						
			// Selects the event that is required.
			$query->where('p.event = ' . JRequest::getVar('id',NULL));
			
			// Selects current user.
			$query->where('p.user = ' . JFactory::getUser()->id);
			
			// Selects only non cancelled entries. (Innactive as of current)
			
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();			
			
			return $result;
		}
		
		public function getUsers($pk = null, $orderCol = null, $orderDirn = null)
		{			
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			if(empty($orderCol))
			{
				// Prepaid Users
				// Select the required fields from the table.
				$query->select('p.id AS id, p.event, p.status AS status, p.params');
				$query->from('#__events_players AS p');
				
				//Join over the users.
				$query->select('u.username AS username');
				$query->join('LEFT', '#__users AS u ON u.id = p.user');
				
				// Selects the event that is required.
				$query->where('p.event = ' . (int) JRequest::getInt('id'));
				$query->where('p.status = 4');
				
				// Add the list ordering clause.
				$orderCol 		= $this->state->get('list.ordering');
				$orderDirn		= $this->state->get('list.direction');
				/*if ($orderCol == 'p.ordering' || $orderCol == 'id') 
				{
					$orderCol = 'id ' . $orderDirn . ', p.ordering';
				}*/
				//$query->order($db->escape($orderCol . ' ' . $orderDirn));
				
				$query->order('status DESC');
				//echo nl2br(str_replace('#__','joom_',$query));
				$prepaids = $db->setQuery($query)->loadObjectList();
				
				$query	= $db->getQuery(true);
				
				// Non-Prepaid Users
				$query->select('p.id AS id, p.event, p.status AS status, p.params');
				$query->from('#__events_players AS p');
				
				//Join over the users.
				$query->select('u.username AS username');
				$query->join('LEFT', '#__users AS u ON u.id = p.user');
				
				// Selects the event that is required.
				$query->where('p.event = ' . (int) JRequest::getInt('id'));
				$query->where('p.status <> 4');
				
				// Add the list ordering clause.
				$orderCol 		= $this->state->get('list.ordering');
				$orderDirn		= $this->state->get('list.direction');
				/*if ($orderCol == 'p.ordering' || $orderCol == 'id') 
				{
					$orderCol = 'id ' . $orderDirn . ', p.ordering';
				}*/
				//$query->order($db->escape($orderCol . ' ' . $orderDirn));
				
				$query->order('status DESC');
				//echo nl2br(str_replace('#__','joom_',$query));
				$other = $db->setQuery($query)->loadObjectList();
				
				$result = (object)array_merge((array)$prepaids, (array)$other);
			}
			else
			{
				// Non-Prepaid Users
				$query->select('p.id AS id, p.event, p.status AS status, p.params');
				$query->from('#__events_players AS p');
				
				//Join over the users.
				$query->select('u.username AS username');
				$query->join('LEFT', '#__users AS u ON u.id = p.user');
				
				// Selects the event that is required.
				$query->where('p.event = ' . (int) JRequest::getInt('id'));
				
				// Add the list ordering clause.
				$orderCol 		= $this->state->get('list.ordering');
				$orderDirn		= $this->state->get('list.direction');
				if ($orderCol == 'p.ordering' || $orderCol == 'id') 
				{
					$orderCol = 'id ' . $orderDirn . ', p.ordering';
				}
				$query->order($db->escape($orderCol . ' ' . $orderDirn));
				
				$query->order('status DESC');
				//echo nl2br(str_replace('#__','joom_',$query));
				$result = $db->setQuery($query)->loadObjectList();
			}
			return $result;
		}
		
		public function setConfirmAttendee()
		{
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Gets data to update
			$fields = $db->quoteName('status') . ' = 2';
			
			// Sets the conditions of which event and which player to update
			$conditions = array($db->quoteName('event') . ' = ' . JRequest::getVar('id',NULL,'GET'), $db->quoteName('user') . ' = ' . $user->id);
			
			// Executes Query
			$query->update($db->quoteName('#__events_players'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			/************************************************/
			
			$query	= $db->getQuery(true);
			
			$confirmedPlayers = $this->items->a.players_confirmed;
			
			$fields = 'players_confirmed' . ' = ' . $confirmedPlayers . ' + 1';

			$conditions = array($db->quoteName('id') . ' = ' . JRequest::getVar('id',NULL,'GET'));
			
			$query->update($db->quoteName('#__events_events'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			return true;
		}
		
		public function storeAttendee($pk = null)
		{
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Sets columns
			$colums = array('id', 'event', 'user', 'status', 'params');
			
			// Sets values
			$values = array('NULL',JRequest::getVar('id'), $user->id, '1', 'NULL');
			
			// Prepare Insert Query $db->quoteName('unconfirmed')
			$query  ->insert($db->quoteName('#__events_players'))
					->columns($db->quoteName($colums))
					->values(implode(',', $values));
			
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			$query	= $db->getQuery(true);
			
			$currentPlayers = $this->items->a.players_current;
			
			$fields = 'players_current' . ' = ' . $currentPlayers . ' + 1';

			$conditions = array($db->quoteName('id') . ' = ' . intval($pk));
			
			$query->update($db->quoteName('#__events_events'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			return true;
		}
		
		public function deleteAttendee()
		{
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			$query->select('a.players_confirmed', 'a.players_current');
			$query->from('#__events_events AS a');
				
			$query->where('a.id = ' . (int) JRequest::getInt('id',NULL,'GET'));
			$db->setQuery($query);

			$this->event = $db->loadObject();
			
			$query	= $db->getQuery(true);
			
			$currentStatus = $this->currentPlayer->status;
			
			$model = new EventsModelsEvent();
			
			if($model->getCurrentUser()->status == 2)
			{	
				$confirmedPlayers = $this->event->a.players_confirmed;
				$fields = 'players_confirmed' . ' = ' . $confirmedPlayers . ' - 1';

				$conditions = array($db->quoteName('id') . ' = ' . JRequest::getVar('id',NULL,'GET'));
				
				$query->update($db->quoteName('#__events_events'));
				$query->set($fields);
				$query->where($conditions);
				
				$db->setQuery($query);
				$db->query();
				
				$query	= $db->getQuery(true);
			}
			
			// Sets the conditions of the delete of the user with the event
			$conditions = array($db->quoteName('event') . ' = ' . JRequest::getVar('id',NULL,'GET'), $db->quoteName('user') . ' = ' .  $user->id);
			
			$query->delete($db->quoteName('#__events_players'));
			$query->where($conditions);
						
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			$query	= $db->getQuery(true);
			
			$currentPlayers = $this->event->a.players_current;
			$fields = 'players_current' . ' = ' . $currentPlayers . ' - 1';

			$conditions = array($db->quoteName('id') . ' = ' . JRequest::getVar('id',NULL,'GET'));
			
			$query->update($db->quoteName('#__events_events'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			return true;
		}
		
		public function sendTicket($pk = null, $user1 = null)
		{
			
			
			$mailer = JFactory::getMailer();
			$config = JFactory::getConfig();
			$user = JFactory::getUser();
						
			$app = JFactory::getApplication();
			
			$db		= $this->getDb();
			if($user1 == null)
			{
				// Normal Use
				$user1 = JFactory::getUser();
				$body = $app->getParams('com_events')->get('emailregistration');
				$path1 = JPATH_COMPONENT;
				$host1 = JURI::root();
			}
			else
			{
				$path1 = getcwd() . '/..';
				$user1 = intval($user1);
				$user1 = JFactory::getUser($user1);
				$host1 = substr(JURI::root(), 0, stripos(JURI::root(), '/components/com_events'));
				// For IPN User Only - Get params for emailing purposes.
				
				$query	= $db->getQuery(true);
						
				// Select the required fields from the table.
				$query->select('params');
				$query->from('#__extensions AS p');
							
				// Selects the event that is required.
				$query->where('element = ' . $db->quote("com_events"));
				
				// Selects only non cancelled entries. (Innactive as of current)
				// Runs query
				$body = json_decode($db->setQuery($query)->loadResult())->emailregistration;
				$db->query();
			}
			
			// Gets User Data
			$query	= $db->getQuery(true);
						
			// Select the required fields from the table.
			$query->select('p.id AS id, p.event AS event, p.status AS status, p.params');
			$query->from('#__events_players AS p');
						
			// Selects the event that is required.
			$query->where('p.event = ' . $db->quote($pk));
			
			// Selects current user.
			$query->where('p.user = ' . $db->quote($user1->id));
			
			// Selects only non cancelled entries. (Innactive as of current)
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
			
			// Adds external classes
			include('qrcode.php');
			include('barcode.php');  
				
			// Get event details
			$event = $this->getEvent($result->event);
			
			// Gathers sender information from joomla
			$sender = array( 
				$config->get('config.mailfrom'),
				$config->get('config.fromname'));
			 
			$mailer->setSender($sender);
			// Sends email to the users address
			$recipient = $user1->email;
 
			$mailer->addRecipient($recipient);
			
			// Subject of the email
			$mailer->setSubject($db->escape($event->title) . ' - Registration Ticket');
			// Body of the email
			QRcode::png($host1 . '/?option=com_events&view=checkin&layout=qrcode&id=' . $result->id , $path1 . '/assets/images/qrcodes/ticket' . $result->id .'.png');
			
			$im     = imagecreatetruecolor(200, 100);  
			$black  = ImageColorAllocate($im,0x00,0x00,0x00);  
			$white  = ImageColorAllocate($im,0xff,0xff,0xff);  
			imagefilledrectangle($im, 0, 0, 200, 100, $white);  
			$data 	= Barcode::gd($im, $black, 100, 50, 0, "code128", $result->id, 2, 50);

			// Output the image to browser
			//header('Content-Type: image/gif');
			imagegif($im, $path1 . '/assets/images/barcodes/ticket' . $result->id . '.gif');
			imagedestroy($im);
			
			//$body = $app->getParams('com_events')->get('emailregistration');
			
			// Experimental
			//$body = $app->getParams('com_events');
			
			$body = $body . '<br />' . '<h2>' . $db->escape($event->title) . ' - Event Registration Ticket</h2>'
					. '<div><p><strong>Username: </strong>' . $user1->username . '<br />' 
					. '<strong>Name: </strong>' . $user1->name . '<br />'
					. '<strong>Event Name: </strong>' . $db->escape($event->title) . '<br />'
					. '<strong>Ticket ID: </strong>' . $result->id . '<br /></p> '
					. '<p><img src="/components/com_events/assets/images/qrcodes/ticket' . $result->id . '.png" />'
					. '<img src="/components/com_events/assets/images/barcodes/ticket' . $result->id . '.gif" /></p></div>';
					
			/* Needs to re-code images to ensure a full unc path */
			$body = str_ireplace('src="', 'src="' . $host1, $body);
			
			/* Replaces braketed wildcards with appropriate text */
			$body = str_ireplace('{name}', $user1->name, $body);
			$body = str_ireplace('{event}', $db->escape($event->title), $body);
			
			
			$mailer->isHTML(true);
			$mailer->Encoding = 'base64';
			$mailer->setBody($body);
			
			
			// Sends the email
			$send = $mailer->Send();
			if ( $send !== true ) {
				echo 'Error sending email: ' . $send->__toString();
				return false;
			} else {
				//echo 'Mail sent';
				return true;
			}
			
		}
	}
	
?>