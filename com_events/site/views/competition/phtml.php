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
			
			return parent::render();
		}
	}
?>