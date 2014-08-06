<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	
   /**
	* LAN Party Component Controller
	*
	* @package		LAN
	* @subpackage	com_lan
	*/
	
	class LANController extends JControllerLegacy
	{
		/**
		 * @var		String		Sets the default view for the component.
		 * @since	0.0
		 */
		 
		 protected $default_view = 'events';
		 
		/**
		 * Override the display method for the controller   $cachable = false, $urlparams = Array
		 *
		 * @return	void
		 * @since 	0.0
		 */  
		 
		function display ($cachable = false, $urlparams = Array())
		{
			// Load the component helper.
			require_once (JPATH_COMPONENT.'/helpers/lan.php');
			
			// Display the view
			parent::display();
			
			// Set the submenu
			//LANHelper::addSubmenu('events');
		}
		
		public function team($cachable = false, $urlparams = null)
		{
			JSession::checkToken() or die( 'Invalid Token' );
			
			// Load the component helper.
			require_once (JPATH_COMPONENT.'/helpers/lan.php');
			
			 // Gets competition id
			$id 	= JRequest::getVar('id');
					
			// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);
			
			// Select the required fields from the table.
			$query->select('p.id AS id, p.status AS status, p.params');
			$query->from('#__lan_team_players AS p');
					
			//Join over the users.
			$query->select('u.username AS username');
			$query->join('LEFT', '#__users AS u ON u.id = p.user');
							
			// Selects the team that is required.
			$query->where('p.team = ' . $id);
						
			// Runs query
			$result = $db->setQuery($query)->loadObjectList();
			
			// Clean up query.
			$query	= $db->getQuery(true);
			
			foreach ($result as $player) :
			{
				$status = JRequest::getVar('player_status_change#' . $player->id);
				
				if(!empty($status))
				{
					switch($status)
					{
						case 'remove':
						case 'reject':
						{
							if($player->status == 4)
							{
								JFactory::getApplication()->enqueueMessage(JText::_('COM_LAN_TEAM_ERROR_DELETE_TEAM_LEADER'), 'error' );
								break;
							}
							
							// Sets delete statement and clauses
							$query->delete($db->quoteName('#__lan_team_players'));
							
							// Sets conditions for a single player for that team.
							$query->where($db->quoteName('team') . ' = ' . $id);
							$query->where($db->quoteName('id') . ' = ' . $player->id);
							
							// Set the query and execute item
							$db->setQuery($query);
							$db->query();
							
							//Sends a message to the user stating removal.
							JFactory::getApplication()->enqueueMessage($player->username . JText::_('COM_LAN_TEAM_MSG_PLAYER_REMOVED'));
							
							break;
						}
						case 'approve':
						case 'member':
						{
							if($player->status == 4)
							{
								JFactory::getApplication()->enqueueMessage(JText::_('COM_LAN_TEAM_ERROR_DEMOTE_TEAM_LEADER'), 'error' );
								break;
							}
						
							// Sets data to be updated
							$query->set($db->quoteName('status') . ' = 1');
							
							// Sets conditions for a single player for that team.
							$query->where($db->quoteName('team') . ' = ' . $id);
							$query->where($db->quoteName('id') . ' = ' . $player->id);
							
							// Executes Query
							$query->update($db->quoteName('#__lan_team_players'));
							
							// Set the query and execute item
							$db->setQuery($query);							
							$db->query();
							
							//Sends a message to the user now a member.
							JFactory::getApplication()->enqueueMessage($player->username . JText::_('COM_LAN_TEAM_MSG_PLAYER_MEMBER'));
							break;
						}
						case 'moderator':
						{
							if($player->status == 4)
							{
								JFactory::getApplication()->enqueueMessage(JText::_('COM_LAN_TEAM_ERROR_DEMOTE_TEAM_LEADER'), 'error' );
								break;
							}
						
							// Sets data to be updated
							$query->set($db->quoteName('status') . ' = 2');
							
							// Sets conditions for a single player for that team.
							$query->where($db->quoteName('team') . ' = ' . $id);
							$query->where($db->quoteName('id') . ' = ' . $player->id);
							
							// Executes Query
							$query->update($db->quoteName('#__lan_team_players'));
							
							// Set the query and execute item
							$db->setQuery($query);							
							$db->query();
							
							//Sends a message to the user now a member.
							JFactory::getApplication()->enqueueMessage($player->username . JText::_('COM_LAN_TEAM_MSG_PLAYER_MODERATOR'));
							break;
						}
						case 'leader':
						{
							// Sets data to be updated
							$query->set($db->quoteName('status') . ' = 1');
							
							// Sets conditions for a single player for that team.
							$query->where($db->quoteName('team') . ' = ' . $id);
							$query->where($db->quoteName('status') . ' = ' . '4');
							
							// Executes Query
							$query->update($db->quoteName('#__lan_team_players'));
							
							// Set the query and execute item
							$db->setQuery($query);							
							$db->query();
							
							// Clears cache
							$query	= $db->getQuery(true);
						
							// Sets data to be updated
							$query->set($db->quoteName('status') . ' = 4');
							
							// Sets conditions for a single player for that team.
							$query->where($db->quoteName('team') . ' = ' . $id);
							$query->where($db->quoteName('id') . ' = ' . $player->id);
							
							// Executes Query
							$query->update($db->quoteName('#__lan_team_players'));
							
							// Set the query and execute item
							$db->setQuery($query);							
							$db->query();
							
							//Sends a message to the user now a member.
							JFactory::getApplication()->enqueueMessage($player->username . JText::_('COM_LAN_TEAM_MSG_PLAYER_LEADER'));
							break;
						}
					}
				}
			} endforeach;
			
			
			// Display the view
			parent::display();
			
			
		}
	}
?>