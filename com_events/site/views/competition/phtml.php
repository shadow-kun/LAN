<?php
	// no direct access
	defined( '_JEXEC' ) or die( 'Restricted access' );
	
	//Display partial views
	class EventsViewsCompetitionPhtml extends JViewHTML
	{
		function render()
		{
			
			$id = (int) JRequest::getInt('id');
			$this->params = JComponentHelper::getParams('com_events');
			
			
			// Gets Event Details
			$this->competition = $this->model->getCompetition($id);
			
			// Gets user base information
			$this->users = $this->model->getUsers($id);
			
			// Gets team based information
			$this->teams = $this->model->getTeams($id);
			
			
			$this->canRegister = $this->model->canRegister($id);
			
			// Gets the current user that is logged in
			$this->currentUser = $this->model->getCurrentUser();
			
			// Gets the current teams that the user is logged in
			$this->currentTeams = $this->model->getCurrentTeams();
			
			
			if ((int) $this->competition->published <= 0)
			{
				JError::raiseError(404, JText::_('COM_EVENTS_ERROR_COMPETITION_NOT_FOUND'));
			}
			
			// If in the access level that is allowed to view this event, otherwise 403 error		
			if(!(in_array($this->competition->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id))))
			{
				JError::raiseError(403, JText::_('COM_EVENTS_ERROR_COMPETITION_FOBBIDDEN'));
			}
			
			return parent::render();
		}
	}
?>