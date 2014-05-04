<?php defined( '_JEXEC' ) or die( 'Restricted access' );
 
	class LANViewsTeamHtml extends JViewHtml
	{
		function render()
		{
			$app = JFactory::getApplication();
			$id = $app->input->get('id');
			$layout = $app->input->get('layout');
			$params = $app->getParams();
			
 
			//retrieve task list from model
			$teamsModel = new LANModelsTeam();
			
			switch($layout)
			{
				case "team":
					$this->_teamsListView = LANHelpersView::load('Team','_entry','phtml');
					//$this->team = getTeam($id,$view,FALSE);
					
					
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