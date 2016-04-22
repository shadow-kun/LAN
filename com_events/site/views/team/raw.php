<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	class EventsViewTeamRaw extends JViewHtml
	{
		function render()
		{
			$app = JFactory::getApplication();
			$type = $app->input->get('type');
			$id = $app->input->get('id');
			$view = $app->input->get('view');
			
			//retrieve task list from model
			$model = new EventsModelsTeam();
			$this->team = $model->getTeam($id,$view,FALSE);
			
			// If in the access level that is allowed to view this team, otherwise 403 error		
			if(!(in_array($this->team->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id))))
			{
				JError::raiseError(403, JText::_('COM_EVENTS_ERROR_TEAM_FOBBIDDEN'));
			}	
			
			//display
			echo $this->team;
		}
	}