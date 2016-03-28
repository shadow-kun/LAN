<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerRegister extends JControllerBase
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
			
			// Gets current view.
			$view = $app->input->get('view', 'event');
			$renderView = null;
			$renderButtons = null;
			
			// if calling from the event view.
			if($view == 'mail')
			{
				$model = new EventsModelsEvent();
				
				$id = JRequest::getInt('id');
				
				// If adding to the event is successful
				$model->sendTicket($id);
				$renderView = EventsHelpersView::load('event','_result-register-success','phtml');
			}
			else if($view == 'event')
			{
				$model = new EventsModelsEvent();
				
				$id = JRequest::getInt('id');
				
				// Detects if there is an entry already
				if($model->getCurrentUser())
				{
					
					$return['success'] = false;
					$renderView = EventsHelpersView::load('event','result_failure','html');
					
					$return['msg'] = JText::_('COM_EVENTS_EVENT_REGISTER_DUPLICATE');
				}
				else if($model->storeAttendee($id))
				{
					// If adding to the event is successful
					if(intval($model->getEvent($id)->params->prepay) == 2)
					{
						$return['success'] = true;
						$renderView = EventsHelpersView::load('event','_prepay','phtml');
					}
					else
					{
						if($model->sendTicket($id))
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
				}
				else
				{
					$return['success'] = false;
					$renderView = EventsHelpersView::load('event','result_failure','html');
					
					$return['msg'] = JText::_('COM_EVENTS_EVENT_REGISTER_FAILURE');
				}
			}
			else if($view == 'team')
			{
				$model = new EventsModelsTeam();
				
				// Gets current view.
				$team = JRequest::getInt('id');
				$user	= JFactory::getUser()->id;
			
				// If adding to the event is successful
				if($model->storeTeamMember($team, $user, 0))
				{
					$return['success'] = true;
					$renderView = EventsHelpersView::load('team','_players','phtml');
					$renderButtons = EventsHelpersView::load('team','_buttons','phtml');
				}
				else
				{
					$return['success'] = false;
					$renderView = EventsHelpersView::load('team','_players','phtml');
					
					$return['msg'] = JText::_('COM_EVENTS_TEAM_REGISTER_FAILURE');
				}
			}
			else if($view == 'competition')
			{
				$model = new EventsModelsCompetition();
				
				// Gets current view.
				$competition 	= JRequest::getInt('id');
				$team 			= JRequest::getInt('team');
				$type 			= JRequest::getVar('type');
				$user			= JFactory::getUser()->id;
				
				if($type == 'team')
				{
					// If adding to the competition is successful
					if($model->storeCompetitionTeam($competition, $team))
					{
						$return['success'] = true;
						//$renderView = EventsHelpersView::load('competition','_details','phtml');
						//$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
						$renderView = EventsHelpersView::load('competition','result_success','html');
					}
					else
					{
						$return['success'] = false;
						//$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
						//$renderView = EventsHelpersView::load('competition','_details','phtml');
						
						//$return['msg'] = JText::_('COM_EVENTS_COMPETITION_REGISTER_FAILURE');
						$renderView = EventsHelpersView::load('event','result_failure','html');
					}
				}
				else
				{
					
					// If adding to the competition is successful
					if($model->storeCompetitionUser($competition, $user))
					{
						$return['success'] = true;
						//$renderView = EventsHelpersView::load('competition','_details','phtml');
						//$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
						$renderView = EventsHelpersView::load('competition','result_success','html');
					}
					else
					{
						$return['success'] = false;
						//$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
						//$renderView = EventsHelpersView::load('competition','_details','phtml');
						
						//$return['msg'] = JText::_('COM_EVENTS_COMPETITION_REGISTER_FAILURE');
						$renderView = EventsHelpersView::load('event','result_failure','html');
						
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
				
				ob_start();
				echo $renderButtons->render();
				$html = ob_get_contents();
				ob_clean();
				 
				$return['buttons'] = $html;
			}
			echo json_encode($return);
		}
	}