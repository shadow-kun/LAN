<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file

	/**
	 * Event Html View.
	 *
	 * @package  COM_EVENTS
	 *
	 * @since   12.1
	 */
	class EventsViewsStoreHtml extends JViewHtml
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
			$id = JRequest::getInt('id');
			$app = JFactory::getApplication();
						
			$this->params = JComponentHelper::getParams('com_events');
			
			// Gets Store Details
			if(strcasecmp(JRequest::getVar('layout'), 'orders') == 0 || strcasecmp(JRequest::getVar('layout'), 'payments') == 0)
			{
				if(!empty($id))
				{
					$this->order = $this->model->getOrders(null, $id)[0];
					$this->store = $this->model->getStore(intval($this->order->store));
				}
				else
				{
					$this->orders = $this->model->getOrders();
				}
			}
			elseif(strcasecmp(JRequest::getVar('layout'), 'adminstore') == 0)
			{
				if(JFactory::getUser()->authorise('core.edit.state','com_events'))
				{
					$this->orders = $this->model->getOrders($id, null);
				}
				else
				{
					JError::raiseError(403, JText::_('COM_EVENTS_ERROR_STORE_FOBBIDDEN'));
				}
			}
			else
			{
				$this->store = $this->model->getStore($id);
				$this->groups = $this->model->getGroups($id);
			}
			
			// If this event is viewable show, else 404 error
			if ((int) $this->store->published <= 0)
			{
				JError::raiseError(404, JText::_('COM_EVENTS_ERROR_STORE_NOT_FOUND'));
			}
			
			// If in the access level that is allowed to view this event, otherwise 403 error		
			if(!(in_array($this->store->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id))))
			{
				JError::raiseError(403, JText::_('COM_EVENTS_ERROR_STORE_FOBBIDDEN'));
			}
			
			//display
			return parent::render();
		}
	}

	
	
?>