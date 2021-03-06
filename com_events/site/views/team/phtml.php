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
			
			return parent::render();
		}
	}
?>