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
							'item.select', 'a.id, a.title, a.alias, a.category_id, a.body, a.terms, a.published, a.language, a.params, a.details, a.players_max, a.players_current, ' .
							'a.players_confirmed, a.created_user_id, a.created_time, a.players_prepaid, a.players_prepay, a.event_start_time, a.event_end_time, a.details'
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
					$registry->loadString($data->params);

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
		
		public function getPlayers($pk = null)
		{			
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.event, p.status AS status, p.params');
			$query->from('#__lan_players AS p');
			
			//Join over the users.
			$query->select('u.username AS username');
			$query->join('LEFT', '#__users AS u ON u.id = p.user');
			
			// Selects the event that is required.
			$query->where('p.event = ' . JRequest::getVar('id'));
			
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
			$result = $db->setQuery($query)->loadObjectList();
			
			return $result;
		}
		
		public function getCurrentPlayer($pk = null)
		{
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
						
			// Select the required fields from the table.
			$query->select('p.id AS id, p.event, p.status AS status, p.params');
			$query->from('#__lan_players AS p');
						
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
		
		public function getSavePlayerEvent()
		{
			
			{
				// Gets current user info
				$user	= JFactory::getUser();
				
				// Gets database connection
				$db		= $this->getDbo();
				$query	= $db->getQuery(true);
				
				// Sets columns
				$colums = array('id', 'event', 'user', 'status', 'params');
				
				// Sets values
				$values = array('NULL',JRequest::getVar('id',NULL,'GET'), $user->id, '1', 'NULL');
				
				// Prepare Insert Query $db->quoteName('unconfirmed')
				$query  ->insert($db->quoteName('#__lan_players'))
						->columns($db->quoteName($colums))
						->values(implode(',', $values));
				
				// Set the query and execute item
				$db->setQuery($query);
				$db->query();
				
				$query	= $db->getQuery(true);
				
				$currentPlayers = $this->items->a.players_current;
				
				$fields = 'players_current' . ' = ' . $currentPlayers . ' + 1';

				$conditions = array($db->quoteName('id') . ' = ' . JRequest::getVar('id',NULL,'GET'));
				
				$query->update($db->quoteName('#__lan_events'));
				$query->set($fields);
				$query->where($conditions);
				
				$db->setQuery($query);
				
				$db->query();
			}
		}
		
		
		public function getConfirmPlayerEvent()
		{
			
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			// Gets data to update
			$fields = $db->quoteName('status') . ' = 2';
			
			// Sets the conditions of which event and which player to update
			$conditions = array($db->quoteName('event') . ' = ' . JRequest::getVar('id',NULL,'GET'), $db->quoteName('user') . ' = ' . $user->id);
			
			// Executes Query
			$query->update($db->quoteName('#__lan_players'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			/************************************************/
			
			$query	= $db->getQuery(true);
			
			$confirmedPlayers = $this->items->a.players_confirmed;
			
			$fields = 'players_confirmed' . ' = ' . $confirmedPlayers . ' + 1';

			$conditions = array($db->quoteName('id') . ' = ' . JRequest::getVar('id',NULL,'GET'));
			
			$query->update($db->quoteName('#__lan_events'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
		}
		
		public function getUnconfirmPlayerEvent()
		{
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			$query	= $db->getQuery(true);
			
			$confirmedPlayers = $this->items->a.players_confirmed;
			$fields = 'players_confirmed' . ' = ' . $confirmedPlayers . ' - 1';

			$conditions = array($db->quoteName('id') . ' = ' . JRequest::getVar('id',NULL,'GET'));
			
			$query->update($db->quoteName('#__lan_events'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
		}
		
		public function getDeletePlayerEvent()
		{
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
			
			$currentStatus = $this->currentPlayer->status;
			
			// Sets the conditions of the delete of the user with the event
			$conditions = array($db->quoteName('event') . ' = ' . JRequest::getVar('id',NULL,'GET'), $db->quoteName('user') . ' = ' .  $user->id);
			
			$query->delete($db->quoteName('#__lan_players'));
			$query->where($conditions);
						
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			$query	= $db->getQuery(true);
			
			$currentPlayers = $this->items->a.players_current;
			$fields = 'players_current' . ' = ' . $currentPlayers . ' - 1';

			$conditions = array($db->quoteName('id') . ' = ' . JRequest::getVar('id',NULL,'GET'));
			
			$query->update($db->quoteName('#__lan_events'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
		}
		
		public function getSendTicket()
		{
			
			$mailer = JFactory::getMailer();
			$config = JFactory::getConfig();
			$user = JFactory::getUser();
						
			$app = JFactory::getApplication();
			
			// Gets User Data
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
						
			// Select the required fields from the table.
			$query->select('p.id AS id, p.event, p.status AS status, p.params');
			$query->from('#__lan_players AS p');
						
			// Selects the event that is required.
			$query->where('p.event = ' . JRequest::getVar('id',NULL));
			
			// Selects current user.
			$query->where('p.user = ' . JFactory::getUser()->id);
			
			// Selects only non cancelled entries. (Innactive as of current)
			
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
			
			// Adds external classes
			include('qrcode.php');
			include('barcode.php');  
				
			// Get event details
			$item = $this->getItem();
			
			// Gathers sender information from joomla
			$sender = array( 
				$config->get('config.mailfrom'),
				$config->get('config.fromname'));
			 
			$mailer->setSender($sender);
			
			// Sends email to the users address
			$recipient = $user->email;
 
			$mailer->addRecipient($recipient);
			
			// Subject of the email
			$mailer->setSubject($db->escape($item->title) . ' - Registration Ticket');
			// Body of the email
			QRcode::png(JURI::root() . '/?option=com_lan&view=checkin&layout=qrcode&id=' . $result->id , JPATH_COMPONENT . '/images/qrcodes/ticket' . $result->id .'.png');
			
			$im     = imagecreatetruecolor(200, 100);  
			$black  = ImageColorAllocate($im,0x00,0x00,0x00);  
			$white  = ImageColorAllocate($im,0xff,0xff,0xff);  
			imagefilledrectangle($im, 0, 0, 200, 100, $white);  
			$data 	= Barcode::gd($im, $black, 100, 50, 0, "code128", $result->id, 2, 50);

			// Output the image to browser
			header('Content-Type: image/gif');

			imagegif($im, JPATH_COMPONENT . '/images/barcodes/ticket' . $result->id . '.gif');
			imagedestroy($im);
			
			$body = $app->getParams('com_lan')->get('emailregistration');
				
			$body = $body . '<br />' . '<h2>' . $db->escape($item->title) . ' - Event Registration Ticket</h2>'
					. '<div><p><strong>Username: </strong>' . JFactory::getUser()->username . '<br />' 
					. '<strong>Name: </strong>' . JFactory::getUser()->name . '<br />'
					. '<strong>Event Name: </strong>' . $db->escape($item->title) . '<br />'
					. '<strong>Ticket ID: </strong>' . $result->id . '<br /></p> '
					. '<p><img src="components/com_lan/images/qrcodes/ticket' . $result->id . '.png" />'
					. '<img src="components/com_lan/images/barcodes/ticket' . $result->id . '.gif" /></p></div>';
					
			/* Needs to re-code images to ensure a full unc path */
			$body = str_ireplace('src="', 'src="' . JURI::root() . '/', $body);
			
			/* Replaces braketed wildcards with appropriate text */
			$body = str_ireplace('{name}', $user->name, $body);
			$body = str_ireplace('{event}', $db->escape($item->title), $body);
			
			
			$mailer->isHTML(true);
			$mailer->Encoding = 'base64';
			$mailer->setBody($body);
			
			
			// Sends the email
			$send = $mailer->Send();
			if ( $send !== true ) {
				echo 'Error sending email: ' . $send->__toString();
			} else {
				echo 'Mail sent';
			}
		}
		
	}