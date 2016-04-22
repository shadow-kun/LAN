<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	class EventsViewCompetitionRaw extends JViewHtml
	{
		function render()
		{
			$app = JFactory::getApplication();
			$type = $app->input->get('type');
			$id = $app->input->get('id');
			$view = $app->input->get('view');
			
			//retrieve task list from model
			$model = new EventsModelsCompetition();
			$this->competition = $model->getCompetition($id,$view,FALSE);
			
			// If in the access level that is allowed to view this event, otherwise 403 error		
			if(!(in_array($this->competition->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id))))
			{
				JError::raiseError(403, JText::_('COM_EVENTS_ERROR_COMPETITION_FOBBIDDEN'));
			}
			
			//display
			echo $this->competition;
		}
	}