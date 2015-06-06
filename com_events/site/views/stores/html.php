<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file

	/**
	 * Event Html View.
	 *
	 * @package  COM_EVENTS
	 *
	 * @since   12.1
	 */
	class EventsViewsStoresHtml extends JViewHtml
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
			//$event = (int) JRequest::getInt('event');
			$app = JFactory::getApplication();
						
			$this->params = JComponentHelper::getParams('com_events');
			
			// Gets Event Details
			$this->stores = $this->model->listStores();
			
			// Gets Current User
			$this->user	= JFactory::getUser();
									
			//display
			return parent::render();
		}
	}

	
	
?>