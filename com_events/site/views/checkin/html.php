<?php defined('_JEXEC') or die('Restricted access');
	// No direct access to this file

	/**
	 * Event Html View.
	 *
	 * @package  COM_EVENTS
	 *
	 * @since   12.1
	 */
	class EventsViewsCheckinHtml extends JViewHtml
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
			
			JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
			
			$this->editor = JFactory::getEditor();
						
			$this->params = JComponentHelper::getParams('com_events');
			
			// if team details are set, ie. team exists
			if(isset($id) && $id > 0)
			{
				// Gets Competition Details
				$this->player = $this->model->getPlayer($id);
								
				// Gets the current user that is logged in
				//$this->currentUser = $this->model->getCurrentUser();
				
				
				$this->groupCheckin = $this->model->getCheckinGroup($id);
			}
			
						
			//display
			return parent::render();
		}
	}
?>