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
			if($view == 'event')
			{
				$model = new EventsModelsEvent();
				
				// If adding to the event is successful
				if($model->storeAttendee())
				{
					$return['success'] = true;
					$renderView = EventsHelpersView::load('event','_result-register-success','phtml');
				}
				else
				{
					$return['success'] = false;
					$renderView = EventsHelpersView::load('event','_result-register-failure','phtml');
					
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
			ob_start();
			echo $renderView->render();
			$html = ob_get_contents();
			ob_clean();
			ob_start();
			
			$return['html'] = $html;
			
			echo $renderButtons->render();
			$html = ob_get_contents();
			ob_clean();
			 
			$return['buttons'] = $html;
				
			echo json_encode($return);
		}
	}