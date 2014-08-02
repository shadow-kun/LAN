<?php
	// No direct access to this file
	defined('_JEXEC') or die('Restricted access');
	 
	// import Joomla controller library
	jimport('joomla.application.component.controller');
	 
	/**
	 * Hello World Component Controller
	 */
	class LANController extends JControllerLegacy
	{
		public function register($cachable = false, $urlparams = null)
		{
			JSession::checkToken() or die( 'Invalid Token' );
			$selection = JRequest::getVar('selection');
			
				
			switch($selection)
			{
				case 'register_player_competition':
				{
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
						$params = "'" . json_encode(array('status' => 'Signed Up')) . "'";
						
						
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
					break;
				}
				case 'unregister_player_competition':
				{
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
					break;
				}
				
			}
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
					
					// Sets the conditions of the delete of the user with the team
					if(isset($data[1])) :
					{
						$conditions = array($db->quoteName('team') . ' = ' . $id, $db->quoteName('id') . ' = ' . (int) $data[1]);
					}
					else :
					{
						$conditions = array($db->quoteName('team') . ' = ' . $id, $db->quoteName('user') . ' = ' .  $user->id);
					}
					endif;
					
					
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
			}
			
			parent::display($cachable, $urlparams);
		}
		
	}
?>