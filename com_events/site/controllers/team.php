<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file
	
	class EventsControllerTeam extends JControllerBase
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
			$type = JRequest::getVar('action');
			$layout = JRequest::getVar('layout');
			$renderView = null;
			$renderButtons = null;
			
			// Sets the model to team
			$model = new EventsModelsTeam();
				
			// if calling from the event view.if($type == 'showteamleader')
			
			switch($layout)
			{
				case 'create':
					// returns whole page
					$title = JRequest::getVar('title');
					$body = JRequest::getVar('body');
					$return = $model->storeTeam($title, $body);
					
					if($return === false)
					{
						$renderView = EventsHelpersView::load('teams','result_failure','html');
						
					}
					else
					{
						$vars = (object) ['team' => $return];
						$renderView = EventsHelpersView::load('teams','result_success','html', $vars);
					}
					ob_start();
					echo $renderView->render();
					$html = ob_get_contents();
					ob_clean();
					 
					$return['html'] = $html;
					break;
				default: 
				{	
					// returns details and buttons pages
					if($type == 'showteamdetails')
					{
						$return['success'] = true;
						$renderView = EventsHelpersView::load('team','_details','phtml');
						$renderButtons = EventsHelpersView::load('team','_buttons','phtml');
					}
					else if($type == 'showteamleader')
					{
						$return['success'] = true;
						$renderView = EventsHelpersView::load('team','_leader','phtml');
						$renderButtons = EventsHelpersView::load('team','_buttons','phtml');
					}
					else if($type == 'showteamdelete')
					{
						$return['success'] = true;
						$renderView = EventsHelpersView::load('team','_delete','phtml');
						$renderButtons = EventsHelpersView::load('team','_buttons','phtml');
					}
					else if($type == 'updateteamleader')
					{
						$team		= JRequest::getInt('id');
						$newLeader	= JRequest::getInt('user');
						$user		= JFactory::getUser()->id;
														
						if($model->setTeamMemberStatus($team, $user, 1))
						{
							if($model->setTeamMemberStatus((int) $team, (int) $newLeader, 4))
							{
								$return['success'] = true;
							}
						}
						$renderView = EventsHelpersView::load('team','_players','phtml');
						$renderButtons = EventsHelpersView::load('team','_buttons','phtml');
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
				}
				echo json_encode($return);
				break;
			}
		}
	}