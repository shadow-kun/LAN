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
			
			$model = new EventsModelsEvent();
			$eventView = null;
			
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
			ob_start();
			echo $eventView->render();
			$html = ob_get_contents();
			ob_clean();
			 
			$return['html'] = $html;
				
			echo json_encode($return);
		}
	}