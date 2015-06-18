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
			return parent::render();
		}
	}
?>