<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	class EventsViewEventRaw extends JViewHtml
	{
		function render()
		{
			$app = JFactory::getApplication();
			$type = $app->input->get('type');
			$id = $app->input->get('id');
			$view = $app->input->get('view');
			
			//retrieve task list from model
			$model = new EventsModelsEvent();
			$this->event = $model->getEvent($id,$view,FALSE);
			
			//display
			echo $this->event;
		}
	}