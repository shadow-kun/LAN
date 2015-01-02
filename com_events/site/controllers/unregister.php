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
			
			$model = null
			$eventView = null;
			
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
						$eventView = EventsHelpersView::load('event','_result-unregister-success','phtml');
					}
					else
					{
						$return['success'] = false;
						$eventView = EventsHelpersView::load('event','_result-unregister-failure','phtml');
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