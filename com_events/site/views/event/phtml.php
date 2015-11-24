<?php
	// no direct access
	defined( '_JEXEC' ) or die( 'Restricted access' );
	
	//Display partial views
	class EventsViewsEventPhtml extends JViewHTML
	{
		function render()
		{
			
			$id = (int) JRequest::getInt('id');
			
			$this->params = JComponentHelper::getParams('com_events');
			
			
			// Gets Event Details
			$this->event = $this->model->getEvent($id);
			
			// Gets user base information
			//$this->users = $this->model->getUsers($id);
			
			// Gets the current user that is logged in
			$this->currentUser = $this->model->getCurrentUser();
			
			// Sets PHtml Items
			$this->_prepay = EventsHelpersView::load('event','_prepay_paypal','phtml');
			$this->_prepay->event = $id;
			
			if (empty($this->currentUser->status) && (int) $this->event->published == -1)
			{
				JError::raiseError(404, JText::_('COM_EVENTS_ERROR_EVENT_NOT_FOUND'));
			}
			
			return parent::render();
		}
	}
?>