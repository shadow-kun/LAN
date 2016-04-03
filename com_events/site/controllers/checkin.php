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
			//JSession::checkToken() or die( 'Invalid Token' );
			
			$app = JFactory::getApplication();
						
			// Gets operation required
			$action = JRequest::getVar('action');
			$layout = JRequest::getVar('layout');
			
			// Sets the model to team
			$model = new EventsModelsCheckin();
				
			// if calling from the event view.if($type == 'showteamleader')
			
			switch($layout)
			{
				case 'search':
					
					switch($action)
					{
						case 'barcode': 
							$id = JRequest::getInt('id');
							$url = (JRoute::_('index.php?option=com_events&view=checkin&id=' . (int) $id, false)); 
							$app->redirect($url);
							break;
						case 'registration': 
							$id = JRequest::getInt('id');
							$url = (JRoute::_('index.php?option=com_events&view=checkin&id=' . (int) $id, false)); 
							$app->redirect($url);
							break;
						case 'user': 
							// Resolves player registration id
							$userid = $model->getUserName(JRequest::getVar('user'));
							
							if($userid == 0)
							{
								$app->enqueueMessage(JText::_('COM_EVENTS_CHECKIN_USER_NOT_FOUND'), 'warning');
								$url = (JRoute::_('index.php?option=com_events&view=checkin', false));
							}
							else
							{
								$id = $model->getPlayerID($userid, JRequest::getInt('event'));
							
								$url = (JRoute::_('index.php?option=com_events&view=checkin&id=' . (int) $id , false)); 
							}
							$app->redirect($url);
							break;
					}		
					break;
				// Paying user but not checking in
				case 'payonly':
					$url = JRoute::_('index.php?option=com_events&view=checkin' , false);
					
					$app->redirect($url);
					break;
				// Checking in users
				case 'confirm':
				
					$id = JRequest::getInt('id',NULL);
					
					if($model->setCheckinUser($id))
					{
						$app->enqueueMessage(JText::_('COM_EVENTS_CHECKIN_CONFIRM_SUCCESSFUL'), 'message');
						
						$url = JRoute::_('index.php?option=com_events&view=checkin' , false);
					}
					else
					{
						$app->enqueueMessage(JText::_('COM_EVENTS_CHECKIN_CONFIRM_FAILURE'), 'error');
						
						$url = JRoute::_('index.php?option=com_events&view=checkin&id=' . $id , false);
					}
								
					
					$app->redirect($url);
					break;
				default:
					$app->enqueueMessage(JText::_('COM_EVENTS_CHECKIN_ERROR'), 'warning');
								
					$url = JRoute::_('index.php?option=com_events&view=checkin' , false);
					
					$app->redirect($url);
					break;
			}
			
			
			/*$app = JFactory::getApplication();
			
			$return = array("success" => false);
			
			// Gets current view.
			$eventView = null;
			
			///// Needs permission checks
			/*{
				$model = new EventsModelsCheckin();
					
				// Gets the current user that is logged in
				//$player = $model->getPlayer();
				//$groupCheckin = $this->model->getCheckinGroup($player);
				//$groupCheckin = $this->model->getCheckinGroup($id);
				//$currentUser = $model->getCurrentUser();
					
				// If the user has signed up for the event and isn't paid then allow it to be removed.
				//if(isset($currentUser->status) && ((int) $currentUser->status == 1)) 
				{
					$search = JRequest::getVar('search',NULL);
					
					if(isset($search) == true)
					{
						
						
						$eventView = EventsHelpersView::load('checkin', '_details', 'phtml');
						$eventPayments = EventsHelpersView::load('checkin', '_payments', 'phtml');
						
						ob_start();
						echo $eventPayments->render();
						$html = ob_get_contents();
						ob_clean();
						 
						$return['payments'] = $html;
						
						$return['success'] = true;
						
					}
					// If adding to the event is successful
					else
					{
						
						$id = JRequest::getVar('id',NULL);
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
			}
				
			ob_start();
			echo $eventView->render();
			$html = ob_get_contents();
			ob_clean();
			 
			$return['html'] = $html;
			echo json_encode($return);*/
		}
	}
?>