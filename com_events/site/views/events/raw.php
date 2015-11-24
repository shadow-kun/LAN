<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	class EventsViewEventsRaw extends JViewHtml
	{
		function render()
		{
			$app = JFactory::getApplication();
			$type = $app->input->get('type');
			$id = $app->input->get('id');
			$view = $app->input->get('view');
			
			//retrieve task list from model
			$model = new EventsModelsEvents();
			$this->events = $model->listEvents($id,$view,FALSE);
			
			//display
			echo $this->events;
		}
	}
?>