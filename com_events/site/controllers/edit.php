<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerEdit extends JControllerBase
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
			//JSession::checkToken() or die( 'Invalid Token' );
			
			$app = JFactory::getApplication();
			
			$return = array("success" => false);
			
			// Gets operation required
			$action = JRequest::getVar('action');
			$layout = JRequest::getVar('layout');
			$view = JRequest::getVar('view');
			$renderView = null;
			$renderButtons = null;
			
			$renderView = null;
			$renderButtons = null;
			
			switch($view)
			{
				case 'team':
					// Sets the model to team
					$model = new EventsModelsTeam();
					
					switch($layout)
					{
						case 'details':
							/*if($action != 'update')
							{
								return JError::raiseError(403, JText::_('COM_EVENTS_ERROR_TEAM_ACTION_FORBIDDEN'));
							}*/
							
							// Gets required variables for update.
							$team = JRequest::getInt('id');
							$body = JRequest::getVar('body');
							$title = JRequest::getVar('title');

							// If set correctly show 
							if($model->setTeamDetails($team, $title, $body))
							{
								$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team . '&layout=details&action=success', false)); 
							}
							else
							{
								$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team . '&layout=details&action=failure', false)); 
							}
							
							$app->redirect($url);
							break;
					}
					break;
				default:
					break;
			}
			// if calling from the event view.
			/*ob_start();
			echo $renderView->render();
			$html = ob_get_contents();
			ob_clean();
			 
			$return['html'] = $html;
				
			echo json_encode($return);*/
			
		}
	}