<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file

	/**
	 * Event Html View.
	 *
	 * @package  COM_EVENTS
	 *
	 * @since   12.1
	 */
	class EventsViewsEventHtml extends JViewHtml
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
			
			// Gets Event Details
			$this->event = $this->model->getEvent($id);
			
			// Gets user base information
			$this->users = $this->model->getUsers($id);
			
			// Gets the current user that is logged in
			$this->currentUser = $this->model->getCurrentUser();
			
			// Sets PHtml Items
			$this->_terms = EventsHelpersView::load('event','_terms','phtml');
			$this->_terms->event = $id;
			
			// Sets PHtml Items
			$this->_prepay = EventsHelpersView::load('event','_prepay_paypal','phtml');
			$this->_prepay->event = $id;
			
			//display
			return parent::render();
		}
	}

	
	
?>