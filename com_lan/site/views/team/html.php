<?php defined( '_JEXEC' ) or die( 'Restricted access' );
 
	class LANViewsTeamHtml extends JViewHtml
	{
		function render()
		{
			$app = JFactory::getApplication();
			$layout = $app->input->get('layout');
			$params = $app->getParams();
			
			/*$id = $app->input->get('id');
			$view = $app->input->get('view');*/
 
			//retrieve task list from model
			$teamsModel = new LANModelsTeam();
			
			switch($layout)
			{	
				case "create":
				
					/*$state = $this->get('State');
					$item = $this->getItem();*/
					$this->form = $app->input->get('Form');
					
					break;
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