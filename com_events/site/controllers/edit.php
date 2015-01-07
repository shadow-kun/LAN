<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerEdit extends JControllerBase
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
			//JSession::checkToken() or die( 'Invalid Token' );
			
			$app = JFactory::getApplication();
			
			$return = array("success" => false);
			
			// Gets operation required
			$type = JRequest::getVar('type');
			$renderView = null;
			$renderButtons = null;
			
			
			
			// if calling from the event view.
			if($type == 'updateteamdetails')
			{
				// Sets the model to team
				$model = new EventsModelsTeam();
				
				$team = JRequest::getInt('id');
				$body = JRequest::getVar('body');
				$model->setTeamDetails($team, $body);
				
				$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team, false)); 
			}
			/*ob_start();
			echo $renderView->render();
			$html = ob_get_contents();
			ob_clean();
			 
			$return['html'] = $html;
				
			echo json_encode($return);*/
			
			$app->redirect($url);
		}
	}