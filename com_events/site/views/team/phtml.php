<?php
	// no direct access
	defined( '_JEXEC' ) or die( 'Restricted access' );
	
	//Display partial views
	class EventsViewsTeamPhtml extends JViewHTML
	{
		function render()
		{
			
			$this->editor = JFactory::getEditor();
			
			$id = (int) JRequest::getInt('id');
			
			$this->params = JComponentHelper::getParams('com_events');
			
			// Gets Event Details
			$this->team = $this->model->getTeam($id);
			
			// Gets user base information
			$this->users = $this->model->getUsers($id);
						
			// Gets the current user that is logged in
			$this->currentUser = $this->model->getCurrentUser();
			
			// If team not viewable, error 404
			if ((int) $this->team->published <= 0)
			{
				JError::raiseError(404, JText::_('COM_EVENTS_ERROR_TEAM_NOT_FOUND'));
			}
			
			// If in the access level that is allowed to view this team, otherwise 403 error		
			if(!(in_array($this->team->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id))))
			{
				JError::raiseError(403, JText::_('COM_EVENTS_ERROR_TEAM_FOBBIDDEN'));
			}
			
			return parent::render();
		}
	}
?>