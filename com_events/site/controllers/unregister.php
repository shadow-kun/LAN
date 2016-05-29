<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerUnregister extends JControllerBase
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
			
			$model = null;
			$renderView = null;
			$renderButtons = null;
			
			// Gets current view.
			$view = $app->input->get('view', 'event');
			
			// if calling from the event view.
			if($view == 'event')
			{
				$model = new EventsModelsEvent();
				
				$id = JRequest::getInt('id');
				
				// Gets the current user that is logged in
				$currentUser = $model->getCurrentUser();
				
				// If not logged in, fail with login error
				if(JFactory::getUser()->guest)
				{
					
					$return['success'] = false;
					$renderView = EventsHelpersView::load('event','result_failure','html');
					
					$return['msg'] = JText::_('COM_EVENTS_ERROR_LOGIN_REQUIRED');
				}
				else if(in_array($model->getEvent($id)->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id))) 
				{
					// If the user has signed up for the event and isn't paid then allow it to be removed.
					if(isset($currentUser->status) && (int) $currentUser->status <= 2)
					{
						if($model->deleteAttendee())
						{
							$return['success'] = true;
							$renderView = EventsHelpersView::load('event','result_success','html');
						}
						else
						{
							$return['success'] = false;
							$renderView = EventsHelpersView::load('event','result_failure','html');
						}	
					}
					else
					{
						$return['success'] = false;
						$renderView = EventsHelpersView::load('event','result_failure','html');
					}
				}
				else
				{					
					$return['success'] = false;
					$renderView = EventsHelpersView::load('event','result_failure','html');
				}
			}
			else if($view == 'team')
			{
				$model = new EventsModelsTeam();
				
				// Gets current view.
				$team = JRequest::getInt('id');
				$user = (int) JFactory::getUser()->id;				
				
				if(JFactory::getUser()->guest)
				{
					$app->enqueueMessage(JText::_('COM_EVENTS_ERROR_LOGIN_REQUIRED'), 'error');
				}
				else if(in_array($model->getTeam($id)->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id))) 
				{
					// Remove user from team if not the team leader. Team leader must delete the group.
					if($model->getTeamUserStatus($team, JFactory::getUser()->id) != 4 && $model->deleteTeamMember($team, $user))
					{
						$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_UNREGISTER_SUCCESS'), 'message'); 
					}
					else
					{
						$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_UNREGISTER_FAILURE'), 'error');
					}
				}
				else
				{
					$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_UNREGISTER_FAILURE'), 'error');
				}
				$app->redirect(JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team, false));
			}
			else if($view == 'competition')
			{
				$model = new EventsModelsCompetition();
				
				// Gets current view.
				$competition 	= JRequest::getInt('id');
				$team 			= JRequest::getInt('team');
				$type 			= JRequest::getVar('type');
				$user 			= (int) JFactory::getUser()->id;
				
				if(JFactory::getUser()->guest)
				{
					
					$return['success'] = false;
					$renderView = EventsHelpersView::load('competition','result_failure','html');
					
					$return['msg'] = JText::_('COM_EVENTS_ERROR_LOGIN_REQUIRED');
				}
				else if(in_array($model->getCompetition($id)->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id))) 
				{
					if($type == 'team')
					{
						// Removes user from competition signup
						if(strtotime($model->getCompetition($competition)->competition_start) > time())
						{
							if($model->deleteCompetitionTeam($competition, $team))
							{
								$return['success'] = true;
								//$renderView = EventsHelpersView::load('competition','_details','phtml');
								//$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
								$renderView = EventsHelpersView::load('competition','result_success','html');
							}
							else
							{
								$return['success'] = false;
								//$renderView = EventsHelpersView::load('competition','details','phtml');
								//$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
								
								//$return['msg'] = JText::_('COM_EVENTS_COMPETITION_UNREGISTER_FAILURE');
								$renderView = EventsHelpersView::load('competition','result_failure','html');
							}
						}
						else
						{
							// Sets forfeit status for user 
							if($model->setCompetitionTeamStatus($competition, $team, -2))
							{
								$return['success'] = true;
								//$renderView = EventsHelpersView::load('competition','_details','phtml');
								//$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
								$renderView = EventsHelpersView::load('competition','result_success','html');
							}
							else
							{
								$return['success'] = false;
								//$renderView = EventsHelpersView::load('competition','details','phtml');
								//$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
								
								//$return['msg'] = JText::_('COM_EVENTS_COMPETITION_UNREGISTER_FAILURE');
								$renderView = EventsHelpersView::load('competition','result_failure','html');
							}
						}
					}
					else
					{
						// Removes user from competition signup
						if(strtotime($model->getCompetition($competition)->competition_start) > time())
						{
							if($model->deleteCompetitionUser($competition, $user))
							{
								$return['success'] = true;
								//$renderView = EventsHelpersView::load('competition','_details','phtml');
								//$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
								
								$renderView = EventsHelpersView::load('competition','result_success','html');
							}
							else
							{
								$return['success'] = false;
								//$renderView = EventsHelpersView::load('competition','details','phtml');
								//$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
								
								//$return['msg'] = JText::_('COM_EVENTS_COMPETITION_UNREGISTER_FAILURE');
								$renderView = EventsHelpersView::load('competition','result_failure','html');
							}
						}
						else
						{
							// Sets forfeit status for user 
							if($model->setCompetitionEntrantStatus($competition, $user, -2))
							{
								$return['success'] = true;
								$renderView = EventsHelpersView::load('competition','_details','phtml');
								$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
							}
							else
							{
								$return['success'] = false;
								$renderView = EventsHelpersView::load('competition','details','phtml');
								$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
								
								$return['msg'] = JText::_('COM_EVENTS_COMPETITION_UNREGISTER_FAILURE');
							}
						}
					}
				}
				else
				{
					$return['success'] = false;
					$renderView = EventsHelpersView::load('competition','details','phtml');
					$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
					
					$return['msg'] = JText::_('COM_EVENTS_COMPETITION_UNREGISTER_FAILURE');
				}
			}
			ob_start();
			echo $renderView->render();
			$html = ob_get_contents();
			ob_clean();
			 
			$return['html'] = $html;
			
			if(!empty($renderButtons))
			{
				echo $renderButtons->render();
				$html = ob_get_contents();
				ob_clean();
			}
			 
			$return['buttons'] = $html;
			
			echo json_encode($return);
		}
	}