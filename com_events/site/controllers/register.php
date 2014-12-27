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
			$eventView = null;
			
			// if calling from the event view.
			if($view == 'event')
			{
				$model = new EventsModelsEvent();
				
				// If adding to the event is successful
				if($model->storeAttendee())
				{
					$return['success'] = true;
					$eventView = EventsHelpersView::load('event','_result-register-success','phtml');
				}
				else
				{
					$return['success'] = false;
					$eventView = EventsHelpersView::load('event','_result-register-failure','phtml');
					
					$return['msg'] = JText::_('COM_EVENTS_EVENT_REGISTER_FAILURE');
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