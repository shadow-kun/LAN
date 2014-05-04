<?php defined( '_JEXEC' ) or die( 'Restricted access' );
 
	class LANViewsCompetitionHtml extends JViewHtml
	{
		function render()
		{
			$app = JFactory::getApplication();
			$layout = $app->input->get('layout');
			
			/*$id = $app->input->get('id');
			$view = $app->input->get('view');*/
 
			//retrieve task list from model
			$competitionModel = new LANModelsCompetition();
			
			switch($layout)
			{	
				case "list":
				default:
				
				break;
			}
			
			//display
			return parent::render();
		}
	}
?>