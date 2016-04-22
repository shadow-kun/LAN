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
					
			// If this event is viewable show, else 404 error
			if (empty($this->currentUser->status) && (int) $this->event->published == -1)
			{
				JError::raiseError(404, JText::_('COM_EVENTS_ERROR_EVENT_NOT_FOUND'));
			}	
			if ((int) $this->event->published == 0 || (int) $this->event->published == -2)
			{
				JError::raiseError(404, JText::_('COM_EVENTS_ERROR_EVENT_NOT_FOUND'));
			}
			
			// If in the access level that is allowed to view this event, otherwise 403 error		
			if(!(in_array($this->event->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id))))
			{
				JError::raiseError(403, JText::_('COM_EVENTS_ERROR_EVENT_FOBBIDDEN'));
			}
			
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