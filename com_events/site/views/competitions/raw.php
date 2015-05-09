<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	class EventsViewCompetitionsRaw extends JViewHtml
	{
		function render()
		{
			$app = JFactory::getApplication();
			$type = $app->input->get('type');
			$id = $app->input->get('id');
			$view = $app->input->get('view');
			
			//retrieve task list from model
			$model = new EventsModelsCompetitions();
			$this->competitions = $model->listCompetitions($id,$view,FALSE);
			
			//display
			echo $this->competitions;
		}
	}
?>