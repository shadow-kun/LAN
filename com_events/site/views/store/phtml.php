<?php
	// no direct access
	defined( '_JEXEC' ) or die( 'Restricted access' );
	
	//Display partial views
	class EventsViewsStorePhtml extends JViewHTML
	{
		function render()
		{
			
			$id = (int) JRequest::getInt('id');
			$this->params = JComponentHelper::getParams('com_events');
			
			
			// Gets Event Details
			if(strcasecmp(JRequest::getVar('layout'), 'payments') == 0)
			{
				$this->order = $this->model->getOrders(null, $id)[0];
				$this->store = $this->model->getStore($this->order->store);
			}
			else
			{
				$this->store = $this->model->getStore($id);
			}
			
			// If in the access level that is allowed to view this event, otherwise 403 error		
			if(!(in_array($this->store->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id))))
			{
				JError::raiseError(403, JText::_('COM_EVENTS_ERROR_STORE_FOBBIDDEN'));
			}
			
			return parent::render();
		}
	}
?>