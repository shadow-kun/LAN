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
	 
	class EventsControllerTeam extends JControllerForm
	{
		public function save ($key = null, $urlVar = null)
		{
			$app            = JFactory::getApplication();
			$model          = $this->getModel();
			$table          = $model->getTable();
			$team			= JRequest::getVar('id');
			$data           = JRequest::getVar('jform', array(), 'post', 'array');
						
			
			if(isset($data['add_user']))
			{
				// Gets database connection
				$db		= JFactory::getDbo();
				$query	= $db->getQuery(true);
				
				// Select the required fields from the table.
				$query->select('p.id AS id, p.team, p.status, p.params');
				$query->from('#__lan_team_players AS p');
							
				// Selects the team that is required.
				$query->where('p.team = ' . $team);
				
				// Selects current user.
				$query->where('p.user = ' . $data['add_user']);
				
				// Selects only non cancelled entries. (Inactive as of current)
				
				// Runs query
				$result = $db->setQuery($query)->loadObject();
				$db->query();
					
				// Checks to see if already registered for this team
				if(!(isset($result)))
				{	
					// Sets columns
					$colums = array('id', 'team', 'status', 'user', 'params');
					
					// Sets values
					$values = array('NULL', $team, 1, $data['add_user'], 'NULL');
					
					// Prepare Insert Query $db->quoteName('unconfirmed')
					$query  ->insert($db->quoteName('#__lan_team_players'))
							->columns($db->quoteName($colums))
							->values(implode(',', $values));
					
					// Set the query and execute item
					$db->setQuery($query);
					$db->query();
					
					// Returns a notice message stating user has been added to the team.
					$app->enqueueMessage(JFactory::getUser($data['add_user'])->username . JText::_('COM_EVENTS_TEAM_MSG_PLAYER_ADDED'), 'notice');
				}
				else
				{
					// Returns an error message stating user already in the team.
					$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_ERROR_USER_ALREADY_IN_TEAM'), 'error');
				}
			}
			
			parent::save($key, $urlVar);
		}
	}
?>