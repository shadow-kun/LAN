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
			
						
			//display
			return parent::render();
		}
	}
?>