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
			$action = JRequest::getVar('action');
			$layout = JRequest::getVar('layout');
			$view = JRequest::getVar('view');
			$renderView = null;
			$renderButtons = null;
			
			$renderView = null;
			$renderButtons = null;
			
			switch($view)
			{
				case 'team':
					// Sets the model to team
					$model = new EventsModelsTeam();
					
					switch($layout)
					{
						case 'details':
							/*if($action != 'update')
							{
								return JError::raiseError(403, JText::_('COM_EVENTS_ERROR_TEAM_ACTION_FORBIDDEN'));
							}*/
							
							// Gets required variables for update.
							$team = JRequest::getInt('id');
							$body = JRequest::getVar('body');
							$title = JRequest::getVar('title');

							// If set correctly show 
							if($model->setTeamDetails($team, $title, $body))
							{
								$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team . '&layout=details&action=success', false)); 
							}
							else
							{
								$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team . '&layout=details&action=failure', false)); 
							}
							
							$app->redirect($url);
							break;
						case 'leader':
							$team		= JRequest::getInt('id');
							$newLeader	= JRequest::getInt('user');
							$user		= JFactory::getUser()->id;
															
							if($model->setTeamMemberStatus((int) $team, $user, 1))
							{
								if($model->setTeamMemberStatus((int) $team, (int) $newLeader, 4))
								{
									$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team . '&layout=leader&action=success', false)); 
								}
								else
								{
									$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team . '&layout=leader&action=failure', false)); 
								}
							}
							else
							{
								$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team . '&layout=leader&action=failure', false)); 
							}
							$app->redirect($url);
							break;
						case 'approve':
							$team		= JRequest::getInt('id');
							$user		= $model->getTeamUserId(JRequest::getInt('user'));
							
							// Checks to see if the member is awaiting to be approved / rejected
							if($model->getTeamUserStatus($team, $user) == 0)
							{
								// Changes status to approve application
								if($model->setTeamMemberStatus($team, $user, 1))
								{
									$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_MEMBER_APPROVAL_SUCCESS'), 'message'); 
								}
								else
								{
									$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_MEMBER_APPROVAL_FAILURE'), 'error');
								}
							}
							else
							{
								$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_MEMBER_APPROVAL_FAILURE'), 'error');
							}
							$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team, false));
							
							$app->redirect($url);
							break;
						case 'reject':
							$team		= JRequest::getInt('id');
							$user		= $model->getTeamUserId(JRequest::getInt('user'));
														
							// Checks to see if the member is awaiting to be approved / rejected
							if($model->getTeamUserStatus($team, $user) == 0)
							{
								// Deletes application
								if($model->deleteTeamMember($team, $user))
								{
									$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_MEMBER_REJECT_SUCCESS'), 'message'); 
								}
								else
								{
									$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_MEMBER_REJECT_FAILURE'), 'error');
								}
							}
							else
							{
								$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_MEMBER_DELETION_FAILURE'), 'error');
							}
							$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team, false));
							
							$app->redirect($url);
							break;
						case 'remove':
							$team		= JRequest::getInt('id');
							$user		= $model->getTeamUserId(JRequest::getInt('user'));
														
							// Checks to see if the member is awaiting to be approved / rejected
							if(($model->getTeamUserStatus($team, $user) == 1 && $model->getTeamUserStatus($team, JFactory::getUser()->id) >= 2) || ($model->getTeamUserStatus($team, $user) == 2 && $model->getTeamUserStatus($team, JFactory::getUser()->id) == 4))
							{
								// Deletes application
								if($model->deleteTeamMember($team, $user))
								{
									$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_MEMBER_REMOVE_SUCCESS'), 'message'); 
								}
								else
								{
									$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_MEMBER_REMOVE_FAILURE'), 'error');
								}
							}
							else
							{
								$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_MEMBER_REMOVE_FAILURE'), 'error');
							}
							$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team, false));
							
							$app->redirect($url);
							break;
						case 'update':
							$team		= JRequest::getInt('id');
							$user		= $model->getTeamUserId(JRequest::getInt('user'));
							$action		= JRequest::getVar('action');
							$status 	= null;
							
							// Sets status
							switch($action)
							{
								case 'member':
									$status = 1;
									break;
								case 'moderator':
									$status = 2;
									break;
								default;
									break;
							}
							
							//Verifies that the user can be removed along with that the user isn't removing the team leader or has permission to do so.
							if($model->getTeamUserStatus($team, JFactory::getUser()->id) >= 3)
							{
								// Sets Status if set
								if($status == 1 || $status == 2)
								{
									if($model->setTeamMemberStatus($team, $user, $status))
									{
										$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_MEMBER_STATUS_CHANGE_SUCCESS'), 'message'); 
									}
									else
									{
										$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_MEMBER_STATUS_CHANGE_FAILURE'), 'error');
									}
								}
								else
								{
									$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_MEMBER_STATUS_CHANGE_FAILURE'), 'error');	
								}
							}
							else
							{
								$app->enqueueMessage(JText::_('COM_EVENTS_TEAM_MEMBER_STATUS_CHANGE_FAILURE'), 'error');
							}
							$url = (JRoute::_('index.php?option=com_events&view=team&id=' . (int) $team, false));
							
							$app->redirect($url);
							break;
					}
					break;
				default:
					break;
			}
			// if calling from the event view.
			/*ob_start();
			echo $renderView->render();
			$html = ob_get_contents();
			ob_clean();
			 
			$return['html'] = $html;
				
			echo json_encode($return);*/
			
		}
	}