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
					// If not logged in, fail with login error
					if($app->getUser()->guest)
					{
						$renderView = EventsHelpersView::load('teams','result_failure','html');
					}
					else
					{
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
					}
					ob_start();
					echo $renderView->render();
					$html = ob_get_contents();
					ob_clean();
					 
					$return['html'] = $html;
					break;
				default: 
					break;
			}
		}
	}