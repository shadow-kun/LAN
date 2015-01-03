<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerTeam extends JControllerBase
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
			JSession::checkToken() or die( 'Invalid Token' );
			
			$app = JFactory::getApplication();
			
			$return = array("success" => false);
			
			// Gets current view.
			$view = $app->input->get('view', 'event');
			$eventView = null;
			
			// Sets the model to team
			$model = new EventsModelsTeam();
				
			// if calling from the event view.
			if($view == 'team')
			{
					
				// Gets the current user that is logged in
				$event = $model->getEvent();
				$currentUser = $model->getCurrentUser();
					
				// If the user has signed up for the event and isn't paid then allow it to be removed.
				if(isset($currentUser->status) && ((int) $currentUser->status == 1) 
				{
					// If adding to the event is successful
					if($model->setConfirmAttendee())
					{
						$return['success'] = true;
						$eventView = EventsHelpersView::load('event','_result-confirmation-success','phtml');
					}
					else
					{
						$return['success'] = false;
						$eventView = EventsHelpersView::load('event','_result-confirmation-failure','phtml');
					}
				}
			}
			ob_start();
			echo $eventView->render();
			$html = ob_get_contents();
			ob_clean();
			 
			$return['html'] = $html;
				
			echo json_encode($return);
		}
	}