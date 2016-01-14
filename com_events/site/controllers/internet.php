<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerInternet extends JControllerBase
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
			#text "? (192.168.0.26) at bc:ae:c5:12:6e:13 [ether] on eth0"
			$return = array("success" => false);
			
			// Converts line of jibberish to useable data
			$input = $app->input->get('machine');
			//$ip = substr($input, stripos($input, '(') + 1, stripos($input, ')') - stripos($input, '(') - 1);
			//$mac = str_replace(':', '', substr($input, stripos($input, 'at ') + 3, stripos($input, '[') - stripos($input, 'at ') - 5)) ;
			$ip = substr($input, 0, stripos($input, '-'));
			$mac = substr($input, stripos($input, '-') + 1);
			echo $mac;
			
			$model = null;
			$renderView = null;
			$renderButtons = null;
			
			// Gets current view.
			$view = $app->input->get('view', 'internet');
			
			// if calling from the event view.
			if($view == 'addmachine' /*&& JFactory::getUser()->authorise('core.edit.state','com_events')*/)
			{
				$model = new EventsModelsInternet();
				
				if($model->storeMachine($ip, $mac))
				{
					$return['success'] = true;
					$renderView = EventsHelpersView::load('internet','_result-addmachine-success','phtml');
					$renderButtons = '';
				}
				else
				{
					$return['success'] = false;
					$renderView = EventsHelpersView::load('internet','_result-addmachine-failure','phtml');
					$renderButtons = '';
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
			}
			
			elseif($view == 'inprogress')
			{
				$return['success'] = true;
				$renderView = EventsHelpersView::load('internet','_in_progress','phtml');
				
				ob_start();
				echo $renderView->render();
				$html = ob_get_contents();
				ob_clean();
				 
				$return['html'] = $html;
			}
			
			echo json_encode($return);
		}
	}