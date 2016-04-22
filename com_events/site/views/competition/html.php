<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file

	/**
	 * Event Html View.
	 *
	 * @package  COM_EVENTS
	 *
	 * @since   12.1
	 */
	class EventsViewsCompetitionHtml extends JViewHtml
	{
		/**
		 * Render some data
		 *
		 * @return  string  The rendered view.
		 *
		 * @since   12.1
		 * @throws  RuntimeException on database error.
		 */
		 
		 
		//protected $model;
		
		public function render()
		{
			$id = (int) JRequest::getInt('id');
			$app = JFactory::getApplication();
						
			$this->params = JComponentHelper::getParams('com_events');
			
			// Gets Competition Details
			$this->competition = $this->model->getCompetition($id);
			
			// Gets user base information
			$this->users = $this->model->getUsers($id);
			
			// Gets team based information
			$this->teams = $this->model->getTeams($id);
			
			$this->canRegister = $this->model->canRegister($id);
			
			// Gets the current user that is logged in
			$this->currentUser = $this->model->getCurrentUser();
			
			// Gets the current user that is logged in
			$this->currentTeams = $this->model->getCurrentTeams();
			
			// Sets PHtml Items
			//$this->_terms = EventsHelpersView::load('event','_terms','phtml');
			//$this->_terms->event = $id;
			
			
			if ((int) $this->competition->published <= 0)
			{
				JError::raiseError(404, JText::_('COM_EVENTS_ERROR_COMPETITION_NOT_FOUND'));
			}
			
			// If in the access level that is allowed to view this event, otherwise 403 error		
			if(!(in_array($this->competition->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id))))
			{
				JError::raiseError(403, JText::_('COM_EVENTS_ERROR_COMPETITION_FOBBIDDEN'));
			}
			
			//display
			return parent::render();
		}
	}

	
	
?>