<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerCheckin extends JControllerBase
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
			$eventView = null;
			
			///// Needs permission checks
			{
				$model = new EventsModelsCheckin();
					
				// Gets the current user that is logged in
				$player = $model->getPlayer();
				//$groupCheckin = $this->model->getCheckinGroup($id);
				//$groupCheckin = $this->model->getCheckinGroup($id);
				//$currentUser = $model->getCurrentUser();
					
				// If the user has signed up for the event and isn't paid then allow it to be removed.
				//if(isset($currentUser->status) && ((int) $currentUser->status == 1) 
				{
					$id = JRequest::getInt('id',NULL);
					// If adding to the event is successful
					if($model->setCheckinUser($id))
					{
						 
						$return['success'] = true;
						$eventView = EventsHelpersView::load('checkin','_result-checkin-success','phtml');
					}
					else
					{
						$return['success'] = false;
						$eventView = EventsHelpersView::load('checkin','_result-checkin-failure','phtml');
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
?>