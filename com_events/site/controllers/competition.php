<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerCompetition extends JControllerBase
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
			
			// Sets the model to competition
			$model = new EventsModelsCompetition();
				
			// if calling from the event view.if($type == 'showteamleader')
			if($type == 'showteamdetails')
			{
				$return['success'] = true;
				$renderView = EventsHelpersView::load('competition','_details','phtml');
				$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
			}
			else if($type == 'showentrants')
			{
				$return['success'] = true;
				$renderView = EventsHelpersView::load('competition','_entrants','phtml');
				$renderButtons = EventsHelpersView::load('competition','_buttons','phtml');
			}
			
			
			ob_start();
			echo $renderView->render();
			$html = ob_get_contents();
			ob_clean();
			 
			$return['html'] = $html;
			
			
			echo $renderButtons->render();
			$html = ob_get_contents();
			ob_clean();
			 
			$return['buttons'] = $html;
				
			echo json_encode($return);
		}
	}