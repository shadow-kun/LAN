<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	
	jimport('joomla.application.component.controllerform');
	
	/**
	 * Event Sub-Controller
	 *
	 * @package			LAN
	 * @subpackage		com_lan
	 * @since			0.0
	 */
	 
	class EventsControllerCompetition extends JControllerForm
	{
		public function save ($key = null, $urlVar = null)
		{
			parent::save($key, $urlVar);
			
			$app            = JFactory::getApplication();
			$model          = $this->getModel();
			$table          = $model->getTable();
			$competition	= JRequest::getVar('id');
			$data           = JRequest::getVar('jform', array(), 'post', 'array');
			
			// Gets current user info
			$user	= JFactory::getUser();
			
			// Gets database connection
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true);			
						
			if(!(empty($data['add_user'])))
			{
				// Select the required fields from the table.
				$query->select('a.id AS id, a.competition, a.params');
				$query->from('#__lan_competition_players AS a');
							
				// Selects the team that is required.
				$query->where('a.competition = ' . $competition);
				
				// Selects current user.
				$query->where('a.user = ' . $data['add_user']);
				
				// Selects only non cancelled entries. (Inactive as of current)
				
				// Runs query
				$result = $db->setQuery($query)->loadObject();
				$db->query();
				
				// Checks to see if already registered for this team
				if(!(isset($result)))
				{
					// Sets columns
					$colums = array('id', 'competition', 'user', 'params');
					
					// Sets values
					$values = array('NULL',$competition, $data['add_user'], $db->quote(json_encode(array('status' => 1))));
					
					// Prepare Insert Query $db->quoteName('unconfirmed')
					$query  ->insert($db->quoteName('#__lan_competition_players'))
							->columns($db->quoteName($colums))
							->values(implode(',', $values));
					
					// Set the query and execute item
					$db->setQuery($query);
					$db->query();
					
					// Returns a notice message stating user has been added to the team.
					$app->enqueueMessage(JFactory::getUser($data['add_user'])->username . JText::_('COM_EVENTS_COMPETITION_MSG_ADDED'), 'notice');
				}
				else
				{
					// Returns an error message stating user already in the team.
					$app->enqueueMessage(JText::_('COM_EVENTS_COMPETITION_ERROR_USER_ALREADY_IN_COMPETITION'), 'error');
				}
			}
		}
	}
?>