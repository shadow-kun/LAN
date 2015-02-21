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
				
				// Gets the current user that is logged in
				$currentUser = $model->getCurrentUser();
				
				// If the user has signed up for the event and isn't paid then allow it to be removed.
				if(isset($currentUser->status) && ((int) $currentUser->status == 2 || (int) $currentUser->status == 1)) 
				{
					if($model->deleteAttendee())
					{
						$return['success'] = true;
						$renderView = EventsHelpersView::load('event','_result-unregister-success','phtml');
					}
					else
					{
						$return['success'] = false;
						$renderView = EventsHelpersView::load('event','_result-unregister-failure','phtml');
					}
					
				}	
			}
			else if($view == 'team')
			{
				$model = new EventsModelsTeam();
				
				// Gets current view.
				$team = JRequest::getInt('id');
				$user = (int) JFactory::getUser()->id;
			
				// If adding to the event is successful
				if($model->deleteTeamMember($team, $user))
				{
					$return['success'] = true;
					$renderView = EventsHelpersView::load('team','_players','phtml');
					$renderButtons = EventsHelpersView::load('team','_buttons','phtml');
				}
				else
				{
					$return['success'] = false;
					$renderView = EventsHelpersView::load('team','_players','phtml');
					
					$return['msg'] = JText::_('COM_EVENTS_TEAM_UNREGISTER_FAILURE');
				}
			}
			else if($view == 'competition')
			{
				$model = new EventsModelsCompetition();
				
				// Gets current view.
				$competition 	= JRequest::getInt('id');
				$team 			= JRequest::getInt('team');
				$type 			= JRequest::getVar('type');
				$user 			= (int) JFactory::getUser()->id;
				
				if($type == 'team')
				{
					// Removes user from competition signup
					if(strtotime($model->getCompetition($competition)->competition_start) > time())
					{
						if($model->deleteCompetitionTeam($competition, $team))
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
					else
					{
						// Sets forfeit status for user 
						if($model->setCompetitionTeamStatus($competition, $team, -2))
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
				else
				{
					// Removes user from competition signup
					if(strtotime($model->getCompetition($competition)->competition_start) > time())
					{
						if($model->deleteCompetitionUser($competition, $user))
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