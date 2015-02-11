<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	/**
	 * Events getEvent Model model.
	 *
	 * @pacakge  Examples
	 *
	 * @since   12.1
	 */
	class EventsModelsCompetition extends EventsModelsDefault
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
		
		public function getCompetition($pk = null)
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
							'item.select', 'a.id, a.title, a.alias, a.category_id, a.body, a.published, a.language, a.params, ' .
							'a.created_user_id, a.created_time, a.competition_start, a.competition_end'
						)
					);
					
					$query->from('#__lan_competitions AS a');
					
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
						return JError::raiseError(404, JText::_('COM_LAN_ERROR_COMPETITION_NOT_FOUND'));
					}
					
					$data->params = json_decode($data->params);
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
		
		public function getCurrentUser($pk = null)
		{
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
						
			// Select the required fields from the table.
			$query->select('p.id AS id, p.competition, p.params, p.status AS status');
			$query->from('#__lan_competition_players AS p');
						
			// Selects the competition that is required.
			$query->where('p.competition = ' . JRequest::getVar('id',NULL));
			
			// Selects current user.
			$query->where('p.user = ' . JFactory::getUser()->id);
			
			// Selects only non cancelled entries. (Inactive as of current)
			
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
			
			return $result;
		}
		
		public function getCurrentTeams($pk = null)
		{
			/* Function Purpose: To gather teams that the current user has the permissions to act on behalf
			 *
			 * Permission Levels: Team Leader, Team Moderator
			 */
			
			// Get current data on team status on the current user.
			//
			
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			

			// Join over the team names
			$query->select('t.title AS name, t.id AS id')
				->from('#__lan_teams AS t');
			
			// Select the required fields from the table.
			$query->select('ct.id AS entryid')
				->join('LEFT', '#__lan_competition_teams AS ct ON ct.team = t.id');
			
			// Join the user current status in each team
			$query->join('LEFT', '#__lan_team_players AS tp on tp.team = t.id');

			// Selects the competition that is required.
			//$query->where('ct.competition = ' . JRequest::getVar('id',NULL));
			
			// Selects current user.
			$query->where('tp.user = ' . JFactory::getUser()->id);
			
			// Selects only users of moderator status and above.
			$query->where('tp.status >= 2');
			
			// Runs query
			$result = $db->setQuery($query)->loadObjectList();
			$db->query();
			
			$teams = $this->getTeams();
			
			foreach ($result as $t => $team)
			{				
				if(in_array($team->id, $teams))
				{	
					$result['registered'] = $result->registered + 1;
				}
				else
				{
					$result['unregistered'] = $result->unregistered + 1;
					
				}
			}
			
			return $result;
		}
		
		public function getTeams($pk = null)
		{			
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.competition, p.params as params, p.status AS status');
			$query->from('#__lan_competition_teams AS p');
			
			//Join over the users.
			$query->select('t.title AS name');
			$query->join('LEFT', '#__lan_teams AS t ON t.id = p.team');
			
			// Selects the event that is required.
			$query->where('p.competition = ' . JRequest::getInt('id'));
			
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
		
		public function getUsers($pk = null)
		{			
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.competition, p.params as params, p.status AS status');
			$query->from('#__lan_competition_players AS p');
			
			//Join over the users.
			$query->select('u.username AS username');
			$query->join('LEFT', '#__users AS u ON u.id = p.user');
			
			// Selects the event that is required.
			$query->where('p.competition = ' . JRequest::getInt('id'));
			
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
			
			return true;
		}
		
		public function storeAttendee()
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
			$query->from('#__lan_events AS a');
				
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
				
				$query->update($db->quoteName('#__lan_events'));
				$query->set($fields);
				$query->where($conditions);
				
				$db->setQuery($query);
				$db->query();
				
				$query	= $db->getQuery(true);
			}
			
			// Sets the conditions of the delete of the user with the event
			$conditions = array($db->quoteName('event') . ' = ' . JRequest::getVar('id',NULL,'GET'), $db->quoteName('user') . ' = ' .  $user->id);
			
			$query->delete($db->quoteName('#__lan_players'));
			$query->where($conditions);
						
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			$query	= $db->getQuery(true);
			
			$currentPlayers = $this->event->a.players_current;
			$fields = 'players_current' . ' = ' . $currentPlayers . ' - 1';

			$conditions = array($db->quoteName('id') . ' = ' . JRequest::getVar('id',NULL,'GET'));
			
			$query->update($db->quoteName('#__lan_events'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			return true;
		}
		
		public function sendTicket()
		{
			
			$mailer = JFactory::getMailer();
			$config = JFactory::getConfig();
			$user = JFactory::getUser();
						
			$app = JFactory::getApplication();
			
			// Gets User Data
			$db		= $this->getDb();
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
			QRcode::png(JURI::root() . '/?option=com_events&view=checkin&layout=qrcode&id=' . $result->id , JPATH_COMPONENT . '/assets/qrcodes/ticket' . $result->id .'.png');
			
			$im     = imagecreatetruecolor(200, 100);  
			$black  = ImageColorAllocate($im,0x00,0x00,0x00);  
			$white  = ImageColorAllocate($im,0xff,0xff,0xff);  
			imagefilledrectangle($im, 0, 0, 200, 100, $white);  
			$data 	= Barcode::gd($im, $black, 100, 50, 0, "code128", $result->id, 2, 50);

			// Output the image to browser
			header('Content-Type: image/gif');

			imagegif($im, JPATH_COMPONENT . '/assets/barcodes/ticket' . $result->id . '.gif');
			imagedestroy($im);
			
			$body = $app->getParams('com_events')->get('emailregistration');
				
			$body = $body . '<br />' . '<h2>' . $db->escape($item->title) . ' - Event Registration Ticket</h2>'
					. '<div><p><strong>Username: </strong>' . JFactory::getUser()->username . '<br />' 
					. '<strong>Name: </strong>' . JFactory::getUser()->name . '<br />'
					. '<strong>Event Name: </strong>' . $db->escape($item->title) . '<br />'
					. '<strong>Ticket ID: </strong>' . $result->id . '<br /></p> '
					. '<p><img src="components/com_events/assets/qrcodes/ticket' . $result->id . '.png" />'
					. '<img src="components/com_events/assets/barcodes/ticket' . $result->id . '.gif" /></p></div>';
					
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
		
		public function getCompetitionPlayers($competition)
		{
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			$query->select('a.id');
			$query->from('#__lan_competition_players AS a');
			
			$query->where('a.competition = ' . (int) $competition);
			
			$db->setQuery($query)->query();
			return $db->getNumRows();
			
		}
		
		public function getCompetitionTeams($competition)
		{
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			$query->select('a.id');
			$query->from('#__lan_competition_teams AS a');
			
			$query->where('a.competition = ' . (int) $competition);
			
			$db->setQuery($query)->query();
			return $db->getNumRows();
			
		}
		
		public function storeCompetitionUser($competition, $user)
		{
			$app = JFactory::getApplication();
			
			// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.competition, p.params');
			$query->from('#__lan_competition_players AS p');
						
			// Selects the competition that is required.
			$query->where('p.competition = ' . $competition);
			
			// Selects current user.
			$query->where('p.user = ' . $user);
			
			// Selects only non cancelled entries. (Inactive as of current)
			
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
				
			// Checks to see if already registered for this competition
			if(!(isset($result)))
			{					
				//Sets JSON Params data
				$params = $db->quote(json_encode(array('status' => 1)));
				
				// Sets columns
				$colums = array('id', 'competition', 'user', 'params');
				
				// Sets values
				$values = array('NULL', $competition, $user, $params);
				
				// Prepare Insert Query $db->quoteName('unconfirmed')
				$query  ->insert($db->quoteName('#__lan_competition_players'))
						->columns($db->quoteName($colums))
						->values(implode(',', $values));
				
				// Set the query and execute item
				$db->setQuery($query);
				$db->query();
			}
			
			return true;
			
		}
		
		public function deleteCompetitionUser($competition, $user)
		{
			$app = JFactory::getApplication();
			
			// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			// Sets the conditions of the delete of the user with the competition
			$conditions = array($db->quoteName('competition') . ' = ' . $competition, $db->quoteName('user') . ' = ' .  $user);
			
			$query->delete($db->quoteName('#__lan_competition_players'));
			$query->where($conditions);
						
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			return true;
		}
		
		public function setCompetitionEntrantStatus($competition, $user, $status = 0)
		{			
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Gets data to update
			$fields = $db->quoteName('status') . ' = ' . ((int) $status);
			
			// Sets the conditions of which event and which player to update
			$conditions = array($db->quoteName('competition') . ' = ' . ((int) $competition), $db->quoteName('user') . ' = ' . ((int) $user));
			
			// Executes Query
			$query->update($db->quoteName('#__lan_competition_players'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			return true;
		}
		
	}
	
	
	
?>