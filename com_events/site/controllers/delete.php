<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerDelete extends JControllerBase
	{
		/**
		 * Method to execute the controller.
		 *
		 * @return  void
		 *
		 * @since   12.1
		 * @throws  RuntimeException
		 */
		 
		public function execute()
		{
			$app = JFactory::getApplication();
			
			$return = array("success" => false);
			
			// Gets operation required
			$action = JRequest::getVar('action');
			$layout = JRequest::getVar('layout');
			$view = JRequest::getVar('view');
			$renderView = null;
			$renderButtons = null;
			
			// Gets current view.
			$type = $app->input->get('type', 'event');
			$eventView = null;
			
			switch($view)
			{
				case 'team':
					// Sets the model to team
					$model = new EventsModelsTeam();
					
					switch($layout)
					{
						case 'delete':
							/*if($action != 'update')
							{
								return JError::raiseError(403, JText::_('COM_EVENTS_ERROR_TEAM_ACTION_FORBIDDEN'));
							}*/
							// Gets required variables for update.
							$team = JRequest::getInt('id');
							
							// Does a security check to verify access is allowed to this team
							if(in_array($model->getTeam($team)->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id))) 
							{
							
								if(JFactory::getUser()->guest || empty($team))
								{
									$app->enqueueMessage(JText::_('COM_EVENTS_ERROR_LOGIN_REQUIRED'), 'error');
									$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team . '&layout=delete&action=failure', false));							
								}
								else if($model->deleteTeam($team))
								{
									$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team . '&layout=delete&action=success', false)); 
								}
								else
								{
									$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team . '&layout=delete&action=failure', false)); 
								}
							}
							else
							{
								$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team . '&layout=delete&action=failure', false)); 
							}
							$app->redirect($url);
							break;
					}
					break;
				default:
					break;
			}
				
			return true;
		}
	}