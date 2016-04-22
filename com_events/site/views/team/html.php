<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file

	/**
	 * Event Html View.
	 *
	 * @package  COM_EVENTS
	 *
	 * @since   12.1
	 */
	class EventsViewsTeamHtml extends JViewHtml
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
			
			
			$this->editor = JFactory::getEditor();
						
			$this->params = JComponentHelper::getParams('com_events');
			
			// if team details are set, ie. team exists
			if(isset($id) && $id > 0)
			{
				// Gets Competition Details
				$this->team = $this->model->getTeam($id);
				
				// Gets user base information
				$this->users = $this->model->getUsers($id);
				
				// Gets the current user that is logged in
				$this->currentUser = $this->model->getCurrentUser();
			}
			
			
			if ((int) $this->team->published <= 0)
			{
				JError::raiseError(404, JText::_('COM_EVENTS_ERROR_TEAM_NOT_FOUND'));
			}
			
			// If in the access level that is allowed to view this event, otherwise 403 error		
			if(!(in_array($this->team->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id))))
			{
				JError::raiseError(403, JText::_('COM_EVENTS_ERROR_TEAM_FOBBIDDEN'));
			}
						
			//display
			return parent::render();
		}
	}
?>