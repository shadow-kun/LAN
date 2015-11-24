<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerPayment extends JControllerBase
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
				$paymentModel = new EventsModelsPayment();
				$checkinModel = new EventsModelsCheckin();
					
				{
					// Get variables
					$type = JRequest::getVar('type',NULL);
					$id = JRequest::getInt('id',NULL);
					
					// If variables are set
					if(isset($type) == true && isset($id) == true)
					{
						// Get player details
						$player = $checkinModel->getPlayer($id);
						
						// If player hasn't paid already
						if($player->status < 3)
						{
							// Store payment
							$paymentModel->storePayment($id, floatval($this->player->event_params->cost_event), $type, intval($player->id));
						}
						
						// refresh pages
						$eventView = EventsHelpersView::load('checkin', '_details', 'phtml');
						$eventPayments = EventsHelpersView::load('checkin', '_payments', 'phtml');
						
						$return['success'] = true;
					}
				}
			}
			
			// loads html for pages
			ob_start();
			echo $eventPayments->render();
			$html = ob_get_contents();
			ob_clean();
			
			$return['payments'] = $html;
				
			ob_start();
			echo $eventView->render();
			$html = ob_get_contents();
			ob_clean();
			 
			$return['html'] = $html;
			echo json_encode($return);
		}
	}
?>