<?php
	// no direct access
	defined( '_JEXEC' ) or die( 'Restricted access' );
	
	//Display partial views
	class EventsViewsCheckinPhtml extends JViewHTML
	{
		function render()
		{		
			$this->params = JComponentHelper::getParams('com_events');
			
			$search = JRequest::getVar('search',NULL);
			$id = JRequest::getInt('id',NULL);
					
			if(isset($search) == true)
			{
				if(empty($id) )
				{
					$id = JRequest::getInt('barcode',NULL);
					if(empty($id))
					{
						$user = JRequest::getVar('username',NULL);
						if(!empty($user))
						{
							$user = JUserHelper::getUserId(JRequest::getVar('username',NULL));	
							$event = JRequest::getInt('eventid',NULL);
							$id = $this->model->getPlayerID($user, $event);
						}
					}
				}
				
			}
			if(!empty($id))
			{
				$this->player = $this->model->getPlayer($id);
				
				$this->groupCheckin = $this->model->getCheckinGroup($id);
			}
			// Gets Event Details
			//$this->event = $this->model->getEvent($this->player->event);
			
			// Gets user base information
			//$this->users = $this->model->getUsers($id);
			
			// Gets the current user that is logged in
			//$this->currentUser = $this->model->getCurrentUser();
			
			return parent::render();
		}
	}
?>