<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerConfirmation extends JControllerBase
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
			$eventView = null;
			// if calling from the event view.
			if(strcasecmp($view, 'event') == 0)
			{
				$model = new EventsModelsEvent();
				$id = JRequest::getInt('id');
				
				// Gets the current user that is logged in
				$event = $model->getEvent($id);
				$currentUser = $model->getCurrentUser();
				
				if(in_array($model->getEvent($id)->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id))) 				
				{
					// If the user has signed up for the event and isn't paid then allow it to be removed.
					if(isset($currentUser->status) && ((int) $currentUser->status == 1)) 
					{
						// If adding to the event is successful
						if($model->setConfirmAttendee())
						{
							$return['success'] = true;
							$eventView = EventsHelpersView::load('event','result_success','html');
						}
						else
						{
							$return['success'] = false;
							$eventView = EventsHelpersView::load('event','result_failure','html');
						}
					}
					else
					{
						$return['success'] = false;
						$eventView = EventsHelpersView::load('event','result_failure','html');
					}
				}
				else
				{					
					$return['success'] = false;
					$eventView = EventsHelpersView::load('event','result_failure','html');
				}
			}
			else
			{
				$return['success'] = false;
				$eventView = EventsHelpersView::load('event','result_failure','html');
			}
			
			ob_start();
			echo $eventView->render();
			$html = ob_get_contents();
			ob_clean();
			 
			$return['html'] = $html;
				
			echo json_encode($return);
		}
	}