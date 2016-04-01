<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	class EventsViewTeamsRaw extends JViewHtml
	{
		function render()
		{
			$app = JFactory::getApplication();
			$type = $app->input->get('type');
			$id = $app->input->get('id');
			$view = $app->input->get('view');
			
			//retrieve task list from model
			$model = new EventsModelsTeams();
			$this->teams = $model->listTeams($id,$view,FALSE);
			
			
		}
	}
?>