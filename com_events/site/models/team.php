<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	/**
	 * Events getEvent Model model.
	 *
	 * @pacakge  Examples
	 *
	 * @since   12.1
	 */
	class EventsModelsTeam extends EventsModelsDefault
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
		
		public function getTeam($pk = null)
		{
			$user	= JFactory::getUser();

			$pk = (!empty($pk)) ? $pk : (int) $this->getState('teams.id');

			if ($this->_item === null)
			{
				$this->_item = array();
			}

			if (!isset($this->_item[$pk]))
			{
				try
				{
					$db = $this->getDb();
					$query = $db->getQuery(true)
					->select(
						$this->getState(
							'item.select', 'a.id, a.title, a.alias, a.category_id, a.body, a.published, a.language, a.params, ' .
							'a.created_user_id, a.created_time'
						)
					);
					
					$query->from('#__events_teams AS a');
					
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

					if (empty($data) and $pk != null)
					{
						return JError::raiseError(404, JText::_('COM_EVENTS_ERROR_TEAM_NOT_FOUND'));
					}
					
					if($pk != null)
					{
						// Convert parameter fields to objects.
						
						$data->params = json_decode($data->params);
					}
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
			$query->select('p.id AS id, p.team, p.status, p.params');
			$query->from('#__events_team_players AS p');
						
			// Selects the team that is required.
			$query->where('p.team = ' . JRequest::getVar('id',NULL));
			
			// Selects current user.
			$query->where('p.user = ' . JFactory::getUser()->id);
			
			// Selects only non cancelled entries. (Inactive as of current)
			
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
			
			return $result;
		}
		
		public function getUsers($pk = null)
		{			
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.team, p.status, p.params as params, p.user as userid');
			$query->from('#__events_team_players AS p');
			
			//Join over the users.
			$query->select('u.username AS username');
			$query->join('LEFT', '#__users AS u ON u.id = p.user');
			
			// Selects the team that is required.
			$query->where('p.team = ' . JRequest::getVar('id'));
			
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
		
		/* Rework from this point onwards */
		
		public function setTeamDetails($team, $title, $body)
		{			
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Creates team alias
			jimport('joomla.filter.output');
			$alias = $title;
			$alias = JFilterOutput::stringURLSafe($alias);
		 			
			// Gets data to update
			$fields = array($db->quoteName('body') . ' = ' . $db->quote($body), $db->quoteName('title') . ' = ' . $db->quote($title), $db->quoteName('alias') . ' = ' . $db->quote($alias));
			
			// Sets the conditions of which event and which player to update
			$conditions = array($db->quoteName('id') . ' = ' . ((int) $team));
			
			// Executes Query
			$query->update($db->quoteName('#__events_teams'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			return true;
		}
		
		public function setTeamMemberStatus($team, $user, $status = 1)
		{			
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Gets data to update
			$fields = $db->quoteName('status') . ' = ' . ((int) $status);
			
			// Sets the conditions of which event and which player to update
			$conditions = array($db->quoteName('team') . ' = ' . ((int) $team), $db->quoteName('user') . ' = ' . ((int) $user));
			
			// Executes Query
			$query->update($db->quoteName('#__events_team_players'));
			$query->set($fields);
			$query->where($conditions);
			
			$db->setQuery($query);
			
			$db->query();
			
			return true;
		}
		
		public function storeTeam($title, $body)
		{
			if(empty($title))
			{
				// If title is empty do not create team
				$return['redirect'] = JRoute::_('index.php?option=com_events&view=teams', false);
				$return['success'] = true;
			}
			else
			{
				// Gets database connection
				$db		= $this->getDb();
				$query	= $db->getQuery(true);
				
				// Gets current user info
				$user	= JFactory::getUser();
				$date = new JDate(time());
				
				// Creates team alias
				jimport('joomla.filter.output');
				$alias = $title;
				$alias = JFilterOutput::stringURLSafe($alias);
			
				// Sets columns
				$colums = array('id', 'title', 'body', 'published', 'access', 'language', 'created_user_id', 'created_time', 'params', 'alias');
				
				// Sets values
				$values = array('null', $db->quote($title), $db->quote($body), 1, 1, $db->quote('*'), $user->id, $db->quote($date->tosql(true)), 'null', $db->quote($alias));
				
				// Prepare Insert Query $db->quoteName('unconfirmed')
				$query  ->insert($db->quoteName('#__events_teams'))
						->columns($db->quoteName($colums))
						->values(implode(',', $values));
				
				// Set the query and execute item
				$db->setQuery($query);
				$db->query();
				
				$query	= $db->getQuery(true);
				
				// Select the required fields from the table.
				$query->select('a.id AS id');
				$query->from('#__events_teams AS a');
							
				// Selects current user.
				$query->where('a.created_user_id = ' . JFactory::getUser()->id);
				
				// Selects team created timestamp.
				$query->where('a.created_time = ' . $db->quote($date->tosql(true)));
				
				// Selects only non cancelled entries. (Inactive as of current)
				
				// Runs query
				$result = $db->setQuery($query)->loadObject();
				$db->query();
				
				
				$query	= $db->getQuery(true);
				$id = $result->id;
				
				// Sets columns
				$colums = NULL;
				$colums = array('id', 'team', 'status', 'user', 'params');
				
				// Sets values
				$values = NULL;
				$values = array('NULL', $id, 4, $user->id, 'NULL');
				
				// Prepare Insert Query $db->quoteName('unconfirmed')
				$query  ->insert($db->quoteName('#__events_team_players'))
						->columns($db->quoteName($colums))
						->values(implode(',', $values));
				
				// Set the query and execute item
				$db->setQuery($query);
				$db->query();
				
				$return['redirect'] = JRoute::_('index.php?option=com_events&view=team&id=' . $id, false);
				$return['success'] = true;
			}
			
			return $return;
		}
		
		public function storeTeamMember($team, $user, $status = 0)
		{
			
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			// Sets columns
			$colums = array('id', 'team', 'status', 'user', 'params');
			
			// Sets values
			$values = array('NULL',(int) $team, (int) $status, (int) $user, 'NULL');
			
			// Prepare Insert Query $db->quoteName('unconfirmed')
			$query  ->insert($db->quoteName('#__events_team_players'))
					->columns($db->quoteName($colums))
					->values(implode(',', $values));
			
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			return true;
		}
		
		public function deleteTeamMember($team, $user)
		{
			// Gets database connection
			$db		= $this->getDb();
			$query	= $db->getQuery(true);
			
			$conditions = array($db->quoteName('team') . ' = ' . (int) $team, $db->quoteName('user') . ' = ' .  (int) $user);
			
			$query->delete($db->quoteName('#__events_team_players'));
			$query->where($conditions);
									
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			
			return true;
		}
		
		public function deleteTeam($team)
		{
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('a.id AS id, a.status AS status');
			$query->from('#__events_team_players AS a');
						
			// Selects current user.
			$query->where('a.user = ' . JFactory::getUser()->id);
			
			// Selects team created timestamp.
			$query->where('a.team = ' . $team);
						
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
			
			if($result->status == 4)
			{
				
				$query	= $db->getQuery(true);
			
				// Gets data to update
				$fields = $db->quoteName('published') . ' = -2';
				
				// Sets the conditions of which event and which player to update
				$conditions = array($db->quoteName('id') . ' = ' . (int) $team);
				
				// Executes Query
				$query->update($db->quoteName('#__events_teams'));
				$query->set($fields);
				$query->where($conditions);
				
				$db->setQuery($query);
				
				$db->query();
				
				//$this->setRedirect(JRoute::_('index.php?option=com_lan&view=teams', false));
				
				return true;
			}
			else 
			{
				return JError::raiseError(403, JText::_('COM_LAN_ERROR_FOBBIDEN'));
			}
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
			$query->from('#__events_players AS p');
						
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
	}
	
?>