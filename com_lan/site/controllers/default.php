<?php
	// No direct access to this file
	defined('_JEXEC') or die('Restricted access');
	 
	// import Joomla controller library
	jimport('joomla.application.component.controller');
	jimport('joomla.utilities.date');
	 
	/**
	 * Hello World Component Controller
	 */
	class LANController extends JControllerLegacy
	{
		public function eventCheckIn($cachable = false, $urlparams = null)
		{
			JSession::checkToken() or die( 'Invalid Token' );
			$app = JFactory::getApplication();
			/*
			// Gets competition id
			$id 	= JRequest::getVar('id');
			
			// Get model
			$model = JController::getModel('Checkin');
			
			// Gets database connection
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
		
			$group -> $this->get('CheckedInGroup');*/
			
			$app->enqueueMessage('Test Valid', 'error');
			/*
			$query->select('*');
			$query->from('#__lan_competition_playerss');
			
			
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();*/
			
			parent::display($cachable, $urlparams);
		}
		
		public function competitionRegisterTeam($cachable = false, $urlparams = null)
		{
			JSession::checkToken() or die( 'Invalid Token' );
			$app = JFactory::getApplication();
			
			// Gets competition id
			$id 	= JRequest::getVar('id');
			
			// Get team ID
			$teamid = JRequest::getVar('registerTeamid');
			
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= JFactory::getDbo();
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
			
			$query->where('tp.team = ' . (int) $teamid );
			
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
			
			if(isset($result->id))
			{
				$query	= $db->getQuery(true);
				
				//Sets JSON Params data
				$params = $db->quote(json_encode(array('status' => 1)));
				
				
				// Sets columns
				$colums = array('id', 'competition', 'team', 'params');
			
				// Sets values
				$values = array('NULL', $id, (int) $teamid, $params);
				
				// Prepare Insert Query $db->quoteName('unconfirmed')
				$query  ->insert($db->quoteName('#__lan_competition_teams'))
						->columns($db->quoteName($colums))
						->values(implode(',', $values));
											
				// Set the query and execute item
				$db->setQuery($query);
				$db->query();
			}
			else
			{
				$app->enqueueMessage('Team Registration Failed', 'error');
			}
			
			parent::display($cachable, $urlparams);
		}
		
		public function competitionUnregisterTeam ($cachable = false, $urlparams = null)
		{
			JSession::checkToken() or die( 'Invalid Token' );
			$app = JFactory::getApplication();
			
			// Gets competition id
			$id 	= JRequest::getVar('id');
			
			// Get team ID
			$teamid = JRequest::getVar('unregisterTeamid');
			
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= JFactory::getDbo();
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
			
			$query->where('tp.team = ' . (int) $teamid );
			
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
			
			if(isset($result->id))
			{
				$query	= $db->getQuery(true);
				
				$conditions = array($db->quoteName('competition') . ' = ' . $id, $db->quoteName('team') . ' = ' .  $teamid);
			
				$query->delete($db->quoteName('#__lan_competition_teams'));
				$query->where($conditions);
											
				// Set the query and execute item
				$db->setQuery($query);
				$db->query();
			}
			else
			{
				$app->enqueueMessage('Team Registration Failed', 'error');
			}
			
			parent::display($cachable, $urlparams);
		}
		
		public function competitionRegisterPlayer($cachable = false, $urlparams = null)
		{
			JSession::checkToken() or die( 'Invalid Token' );
			$app = JFactory::getApplication();
			
			 // Gets competition id
			$id 	= JRequest::getVar('id');
			
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.competition, p.params');
			$query->from('#__lan_competition_players AS p');
						
			// Selects the competition that is required.
			$query->where('p.competition = ' . $id);
			
			// Selects current user.
			$query->where('p.user = ' . JFactory::getUser()->id);
			
			// Selects only non cancelled entries. (Inactive as of current)
			
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
				
			// Checks to see if already registered for this competition
			if(!(isset($result))) :
			{					
				//Sets JSON Params data
				$params = $db->quote(json_encode(array('status' => 1)));
				
				
				// Sets columns
				$colums = array('id', 'competition', 'user', 'params');
				
				// Sets values
				$values = array('NULL', $id, $user->id, $params);
				
				// Prepare Insert Query $db->quoteName('unconfirmed')
				$query  ->insert($db->quoteName('#__lan_competition_players'))
						->columns($db->quoteName($colums))
						->values(implode(',', $values));
				
				// Set the query and execute item
				$db->setQuery($query);
				$db->query();
			} endif;	
			
			parent::display($cachable, $urlparams);
		}
		
		public function competitionUnregisterPlayer($cachable = false, $urlparams = null)
		{
			JSession::checkToken() or die( 'Invalid Token' );
			$app = JFactory::getApplication();
			
			// Gets competition id
			$id 	= JRequest::getVar('id');
			
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			// Sets the conditions of the delete of the user with the competition
			$conditions = array($db->quoteName('competition') . ' = ' . $id, $db->quoteName('user') . ' = ' .  $user->id);
			
			$query->delete($db->quoteName('#__lan_competition_players'));
			$query->where($conditions);
						
			// Set the query and execute item
			$db->setQuery($query);
			$db->query();
			
			parent::display($cachable, $urlparams);
		}
				
		public function team($cachable = false, $urlparams = null)
		{
			JSession::checkToken() or die( 'Invalid Token' );
			$selection = JRequest::getVar('selection');
			
				
			$data = explode("#" , $selection);
			switch($data[0])
			{
				case 'register_player_team':
				{
					 // Gets competition id
					$id 	= JRequest::getVar('id');
					
					// Gets current user info
					$user	= JFactory::getUser();
					
					// Gets database connection
					$db		= JFactory::getDbo();
					$query	= $db->getQuery(true);
					
					// Select the required fields from the table.
					$query->select('p.id AS id, p.team, p.status, p.params');
					$query->from('#__lan_team_players AS p');
								
					// Selects the team that is required.
					$query->where('p.team = ' . $id);
					
					// Selects current user.
					$query->where('p.user = ' . JFactory::getUser()->id);
					
					// Selects only non cancelled entries. (Inactive as of current)
					
					// Runs query
					$result = $db->setQuery($query)->loadObject();
					$db->query();
						
					// Checks to see if already registered for this team
					if(!(isset($result))) :
					{					
						// Sets columns
						$colums = array('id', 'team', 'status', 'user', 'params');
						
						// Sets values
						$values = array('NULL', $id, 0, $user->id, 'NULL');
						
						// Prepare Insert Query $db->quoteName('unconfirmed')
						$query  ->insert($db->quoteName('#__lan_team_players'))
								->columns($db->quoteName($colums))
								->values(implode(',', $values));
						
						// Set the query and execute item
						$db->setQuery($query);
						$db->query();
					} endif;					
					break;
				}
				case 'unregister_player_team':
				case 'team_status_reject': 
				case 'team_status_remove':
				{
					// Gets team id
					$id 	= JRequest::getVar('id');
					
					// Gets current user info
					$user	= JFactory::getUser();
					
					// Gets database connection
					$db		= JFactory::getDbo();
					$query	= $db->getQuery(true);
					
					// Select the required fields from the table.
					$query->select('a.id AS id, a.status AS status');
					$query->from('#__lan_team_players AS a');
								
					// Selects current user.
					$query->where('a.user = ' . JFactory::getUser()->id);
					
					// Selects team created timestamp.
					$query->where('a.team = ' . $id);
								
					// Runs query
					$result = $db->setQuery($query)->loadObject();
					$db->query();
										
					// Sets the conditions of the delete of the user with the team
					if(isset($data[1])) :
					{
						$access = $result->status;
						
						$query	= $db->getQuery(true);
					
						// Select the required fields from the table.
						$query->select('a.id AS id, a.status AS status');
						$query->from('#__lan_team_players AS a');
						
						//Join over the users.
						$query->select('u.username AS username');
						$query->join('LEFT', '#__users AS u ON u.id = p.user');
									
						// Selects current user.
						$query->where('a.id = ' . (int) $data[1]);
						
						// Selects team created timestamp.
						$query->where('a.team = ' . $id);
									
						// Runs query
						$result = $db->setQuery($query)->loadObject();
						$db->query();
						
						if(!(isset($result->status)))
						{
							return JError::raiseError(403, JText::_('COM_LAN_ERROR_PLAYER_NOT_FOUND'));
						}
						// Checks to see if correct this user has the correct permissions to perform this operation.
						if($result->status < 2 || $result->status == 2 && $access == 4)
						{
							$conditions = array($db->quoteName('team') . ' = ' . $id, $db->quoteName('id') . ' = ' . (int) $data[1]);
						}
						else 
						{
							return JError::raiseError(403, JText::_('COM_LAN_ERROR_FOBBIDEN'));
						}
					}
					else :
					{
						$conditions = array($db->quoteName('team') . ' = ' . $id, $db->quoteName('user') . ' = ' .  $user->id);
					}
					endif;
					$query	= $db->getQuery(true);
					
					$query->delete($db->quoteName('#__lan_team_players'));
					$query->where($conditions);
								
					// Set the query and execute item
					$db->setQuery($query);
					$db->query();
					break;
				}
				case 'team_status_approve':
				case 'team_status_member':
				{
					// Gets team id
					$id 	= JRequest::getVar('id');
					
					// Gets current user info
					$user	= JFactory::getUser();
					
					// Gets database connection
					$db		= JFactory::getDbo();
					$query	= $db->getQuery(true);
					
					// Select the required fields from the table.
					$query->select('a.id AS id, a.status AS status');
					$query->from('#__lan_team_players AS a');
								
					// Selects current user.
					$query->where('a.user = ' . JFactory::getUser()->id);
					
					// Selects team created timestamp.
					$query->where('a.team = ' . $id);
								
					// Runs query
					$result = $db->setQuery($query)->loadObject();
					$db->query();
					
					if(($result->status >= 2 && $data[0] = 'team_status_approve') || ($result->status == 4))
					{
						$query	= $db->getQuery(true);
						
						// Gets data to update
						$fields = $db->quoteName('status') . ' = 1';
						
						// Sets the conditions of which event and which player to update
						$conditions = array($db->quoteName('team') . ' = ' . $id, $db->quoteName('id') . ' = ' . (int) $data[1]);
						
						// Executes Query
						$query->update($db->quoteName('#__lan_team_players'));
						$query->set($fields);
						$query->where($conditions);
						
						$db->setQuery($query);
						
						$db->query();
					}
					else 
					{
						return JError::raiseError(403, JText::_('COM_LAN_ERROR_FOBBIDEN'));
					}
					break;
				}
				case 'team_status_moderator':
				{
					// Gets team id
					$id 	= JRequest::getVar('id');
					
					// Gets current user info
					$user	= JFactory::getUser();
					
					// Gets database connection
					$db		= JFactory::getDbo();
					$query	= $db->getQuery(true);
					
					// Gets data to update
					$fields = $db->quoteName('status') . ' = 2';
					
					// Sets the conditions of which event and which player to update
					$conditions = array($db->quoteName('team') . ' = ' . $id, $db->quoteName('id') . ' = ' . (int) $data[1]);
					
					// Executes Query
					$query->update($db->quoteName('#__lan_team_players'));
					$query->set($fields);
					$query->where($conditions);
					
					$db->setQuery($query);
					
					$db->query();
					break;
				}
				case 'team_add':
				{
					// Gets database connection
					$db		= JFactory::getDbo();
					$query	= $db->getQuery(true);
					
					
					// Gets current user info
					$user	= JFactory::getUser();
					$date = new JDate(time());
					
					// Sets columns
					$colums = array('id', 'title', 'body', 'published', 'access', 'language', 'created_user_id', 'created_time', 'params');
					
					// Sets values
					$values = array('null', $db->quote(JRequest::getVar('title')), $db->quote(JRequest::getVar('body')), 1, 1, $db->quote('*'), $user->id, $db->quote($date->tosql(true)), 'null');
					
					// Prepare Insert Query $db->quoteName('unconfirmed')
					$query  ->insert($db->quoteName('#__lan_teams'))
							->columns($db->quoteName($colums))
							->values(implode(',', $values));
					
					// Set the query and execute item
					$db->setQuery($query);
					$db->query();
					
					$query	= $db->getQuery(true);
					
					// Select the required fields from the table.
					$query->select('a.id AS id');
					$query->from('#__lan_teams AS a');
								
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
					$query  ->insert($db->quoteName('#__lan_team_players'))
							->columns($db->quoteName($colums))
							->values(implode(',', $values));
					
					// Set the query and execute item
					$db->setQuery($query);
					$db->query();
					
					$this->setRedirect(JRoute::_('index.php?option=com_lan&view=team&id=' . $id, false));
					break;
				}
				case 'team_delete':
				{
					// Gets team id
					$id 	= JRequest::getVar('id');
					
					// Gets current user info
					$user	= JFactory::getUser();
					
					// Gets database connection
					$db		= JFactory::getDbo();
					$query	= $db->getQuery(true);
					
					// Select the required fields from the table.
					$query->select('a.id AS id, a.status AS status');
					$query->from('#__lan_team_players AS a');
								
					// Selects current user.
					$query->where('a.user = ' . JFactory::getUser()->id);
					
					// Selects team created timestamp.
					$query->where('a.team = ' . $id);
								
					// Runs query
					$result = $db->setQuery($query)->loadObject();
					$db->query();
					
					if($result->status == 4)
					{
						// Gets data to update
						$fields = $db->quoteName('published') . ' = -2';
						
						// Sets the conditions of which event and which player to update
						$conditions = array($db->quoteName('id') . ' = ' . (int) $id);
						
						// Executes Query
						$query->update($db->quoteName('#__lan_teams'));
						$query->set($fields);
						$query->where($conditions);
						
						$db->setQuery($query);
						
						$db->query();
						
						$this->setRedirect(JRoute::_('index.php?option=com_lan&view=teams', false));
					}
					else 
					{
						return JError::raiseError(403, JText::_('COM_LAN_ERROR_FOBBIDEN'));
					}
					break;
				}
				case 'team_edit_details':
				{
					// Gets team id
					$id 	= JRequest::getVar('id');
					
					// Gets current user info
					$user	= JFactory::getUser();
					
					// Gets database connection
					$db		= JFactory::getDbo();
					$query	= $db->getQuery(true);
					
					// Select the required fields from the table.
					$query->select('a.id AS id, a.status AS status');
					$query->from('#__lan_team_players AS a');
								
					// Selects current user.
					$query->where('a.user = ' . JFactory::getUser()->id);
					
					// Selects team created timestamp.
					$query->where('a.team = ' . $id);
								
					// Runs query
					$result = $db->setQuery($query)->loadObject();
					$db->query();
					
					if($result->status == 4)
					{
						$this->setRedirect(JRoute::_('index.php?option=com_lan&view=team&layout=edit&id=' . JRequest::getVar('id'), false));
					}
					else 
					{
						return JError::raiseError(403, JText::_('COM_LAN_ERROR_FOBBIDEN'));
					}
					break;
				}
				case 'team_edit_details_confirm':
				{
					// Gets team id
					$id 	= JRequest::getVar('id');
					
					// Gets current user info
					$user	= JFactory::getUser();
					
					// Gets database connection
					$db		= JFactory::getDbo();
					$query	= $db->getQuery(true);
					
					// Select the required fields from the table.
					$query->select('a.id AS id, a.status AS status');
					$query->from('#__lan_team_players AS a');
								
					// Selects current user.
					$query->where('a.user = ' . JFactory::getUser()->id);
					
					// Selects team created timestamp.
					$query->where('a.team = ' . $id);
								
					// Runs query
					$result = $db->setQuery($query)->loadObject();
					$db->query();
					
					if($result->status == 4)
					{
						$query	= $db->getQuery(true);
					
						// Gets data to update
						$fields = array($db->quoteName('title') . ' = ' . $db->quote(JRequest::getVar('title')), $db->quoteName('body') . ' = ' . $db->quote(JRequest::getVar('body')));
						
						// Sets the conditions of which event and which player to update
						$conditions = array($db->quoteName('id') . ' = ' . $id);
						
						// Executes Query
						$query->update($db->quoteName('#__lan_teams'));
						$query->set($fields);
						$query->where($conditions);
						
						$db->setQuery($query);
						
						$db->query();
						$this->setRedirect(JRoute::_('index.php?option=com_lan&view=team&id=' . JRequest::getVar('id'), false));
					}
					else 
					{
						return JError::raiseError(403, JText::_('COM_LAN_ERROR_FOBBIDEN'));
					}
					break;
				}
				case 'team_edit_leader':
				{
					// Gets team id
					$id 	= JRequest::getVar('id');
					
					// Gets current user info
					$user	= JFactory::getUser();
					
					// Gets database connection
					$db		= JFactory::getDbo();
					$query	= $db->getQuery(true);
					
					// Select the required fields from the table.
					$query->select('a.id AS id, a.status AS status');
					$query->from('#__lan_team_players AS a');
								
					// Selects current user.
					$query->where('a.user = ' . JFactory::getUser()->id);
					
					// Selects team created timestamp.
					$query->where('a.team = ' . $id);
								
					// Runs query
					$result = $db->setQuery($query)->loadObject();
					$db->query();
					
					if($result->status == 4)
					{
						$this->setRedirect(JRoute::_('index.php?option=com_lan&view=team&layout=leader&id=' . JRequest::getVar('id'), false));
					}
					else 
					{
						return JError::raiseError(403, JText::_('COM_LAN_ERROR_FOBBIDEN'));
					}
					break;
				}
				case 'team_new_leader_confirm':
				{
					// Gets new team leader
					$teamLeader = JRequest::getVar('teamLeader');
					
					// Gets team id
					$id 	= JRequest::getVar('id');
					
					// Gets current user info
					$user	= JFactory::getUser();
					
					// Gets database connection
					$db		= JFactory::getDbo();
					$query	= $db->getQuery(true);
					
					// Select the required fields from the table.
					$query->select('a.id AS id, a.status AS status');
					$query->from('#__lan_team_players AS a');
								
					// Selects current user.
					$query->where('a.user = ' . JFactory::getUser()->id);
					
					// Selects team created timestamp.
					$query->where('a.team = ' . $id);
								
					// Runs query
					$result = $db->setQuery($query)->loadObject();
					$db->query();
					
					if($result->status == 4)
					{
						$query	= $db->getQuery(true);
						
						// Gets data to update
						$fields = $db->quoteName('status') . ' = 1';
						
						// Sets the conditions of which event and which player to update
						$conditions = array($db->quoteName('id') . ' = ' . $result->id);
						
						// Executes Query
						$query->update($db->quoteName('#__lan_team_players'));
						$query->set($fields);
						$query->where($conditions);
						
						$db->setQuery($query);
						$db->query();
						
						$query	= $db->getQuery(true);
						
						// Gets data to update
						$fields = $db->quoteName('status') . ' = 4';
						
						// Sets the conditions of which event and which player to update
						$conditions = array($db->quoteName('id') . ' = ' . (int) $teamLeader);
						
						// Executes Query
						$query->update($db->quoteName('#__lan_team_players'));
						$query->set($fields);
						$query->where($conditions);
						
						$db->setQuery($query);
						$db->query();
						
						
						$this->setRedirect(JRoute::_('index.php?option=com_lan&view=team&id=' . $id, false));
					}
					else 
					{
						return JError::raiseError(403, JText::_('COM_LAN_ERROR_FOBBIDEN'));
					}
					break;
				}
				case 'cancel':
				{
					$this->setRedirect(JRoute::_('index.php?option=com_lan&view=teams', false));
					break;
				}
				case 'team_new':
				{
					$this->setRedirect(JRoute::_('index.php?option=com_lan&view=team&layout=add', false));
					break;
				}
			}
			
			parent::display($cachable, $urlparams);
		}
	}
?>