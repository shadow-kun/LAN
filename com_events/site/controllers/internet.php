<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerOrder extends JControllerBase
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
			
			$model = null;
			$renderView = null;
			$renderButtons = null;
			
			// Gets current view.
			$view = $app->input->get('view', 'store');
			
			// if calling from the event view.
			if($view == 'addmachine')
			{
				$model = new EventsModelsInternet();
				
				if($model->storeMachine())
				{
					$return['success'] = true;
					$renderView = EventsHelpersView::load('store','_result-neworder-success','phtml');
					$renderButtons = '';
				}
				else
				{
					$return['success'] = false;
					$renderView = EventsHelpersView::load('store','_result-neworder-failure','phtml');
					$renderButtons = '';
				}
			}
			
			ob_start();
			echo $renderView->render();
			$html = ob_get_contents();
			ob_clean();
			 
			$return['html'] = $html;
			
			if(!empty($renderButtons))
			{
				echo $renderButtons->render();
				$html = ob_get_contents();
				ob_clean();
			}
			 
			$return['buttons'] = $html;
			
			echo json_encode($return);
		}
	}