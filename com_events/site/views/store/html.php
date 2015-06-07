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
			if(strcasecmp(JRequest::getVar('layout'), 'orders') == 0)
			{
				if(!empty($id))
				{
					$this->orders = $this->model->getOrders($id);
				}
				else
				{
					$this->orders = $this->model->getOrders();
				}
			}
			else
			{
				$this->store = $this->model->getStore($id);
				$this->groups = $this->model->getGroups($id);
			}
			
			//display
			return parent::render();
		}
	}

	
	
?>