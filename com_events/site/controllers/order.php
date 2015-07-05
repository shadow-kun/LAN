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
			if($view == 'store')
			{
				$model = new EventsModelsStore();
				
				$store = intval(JRequest::getVar('id'));
				// If the user has signed up for the event and isn't paid then allow it to be removed.
				
				if($model->storeOrder($store))
				{
					$return['success'] = true;
					$renderView = EventsHelpersView::load('store','_result-neworder-success','phtml');
				}
				else
				{
					$return['success'] = false;
					$renderView = EventsHelpersView::load('store','_result-neworder-failure','phtml');
				}
			}
			if($view == 'adminstore')
			{
				$model = new EventsModelsStore();
				
				$order = intval(JRequest::getInt('id'));
				$status = intval(JRequest::getInt('status'));
				// If the user has signed up for the event and isn't paid then allow it to be removed.
				
				if(JFactory::getUser()->authorise('core.edit.state','com_events'))
				{
					if($model->updateOrder($order, $status))
					{
						$return['success'] = true;
					}
					else
					{
						
						$return['success'] = false;
					}
				}
				else
				{
					$return['success'] = false;
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
			 
			//$return['buttons'] = $html;
			
			echo json_encode($return);
		}
	}