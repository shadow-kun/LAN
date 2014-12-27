<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file

	/**
	 * Event Html View.
	 *
	 * @package  COM_EVENTS
	 *
	 * @since   12.1
	 */
	class EventsViewsEventsHtml extends JViewHtml
	{
		/**
		 * Render some data
		 *
		 * @return  string  The rendered view.
		 *
		 * @since   12.1
		 * @throws  RuntimeException on database error.
		 */
		 
		
		public function render()
		{
			$id = (int) JRequest::getInt('id');
			$app = JFactory::getApplication();
						
			$this->params = JComponentHelper::getParams('com_events');
			
			// Gets Event Details
			$this->events = $this->model->listEvents();
			
			// Gets Current User
			$this->user	= JFactory::getUser();
									
			//display
			return parent::render();
		}
	}

	
	
?>