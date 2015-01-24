<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerDelete extends JControllerBase
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
			if($view == 'team')
			{
				$model = new EventsModelsTeam();
				
				$team = JRequest::getInt('id');
				
				// Gets the current user that is logged in
				//$team = $model->getTeam();
				$currentUser = $model->getCurrentUser();
					
				// If the user has signed up for the event and isn't paid then allow it to be removed.
				//if(isset($currentUser->status) && ((int) $currentUser->status == 4) 
				{
					if($model->deleteTeam($team))
					{
						
						$url = JRoute::_('index.php?option=com_events&view=teams', false); 
						$app->redirect($url);
						$return['success']=true;
					}
				}
			}
			/*ob_start();
			echo $eventView->render();
			$html = ob_get_contents();
			ob_clean();
			 
			$return['html'] = $html;*/
				
			echo json_encode($return);
		}
	}