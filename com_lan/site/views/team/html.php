<?php defined( '_JEXEC' ) or die( 'Restricted access' );
 
	class LANViewsTeamHtml extends JViewHtml
	{
		function render()
		{
			$app = JFactory::getApplication();
			$layout = $app->input->get('layout');
			
			/*$id = $app->input->get('id');
			$view = $app->input->get('view');*/
 
			//retrieve task list from model
			$teamsModel = new LANModelsTeam();
			
			switch($layout)
			{	
				case "list":
				default:
					//$this->teams = $teamsModel->listItems();
					
					//$this->_teamsListView = LendrHelpersView::load('Teams','_entry','phtml');
				break;
			}
			
			//display
			return parent::render();
		}
	}
?>