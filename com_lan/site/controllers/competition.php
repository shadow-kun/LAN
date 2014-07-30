<?php
	// No direct access to this file
	defined('_JEXEC') or die('Restricted access');
	 
	// import Joomla controller library
	jimport('joomla.application.component.controller');
	
	class LANControllerCompetition extends JControllerLegacy
	{
		
		public function execute($task)
		{
			$app = JFactory::getApplication();
			/*
			if($task == 'competition')
			{
				$this->setRedirect(JRoute::_('index.php?com_lan&view=events', true));
			}
			else 
			{
				$this->setRedirect(JRoute::_('index.php?com_lan&view=' & $task, true));
			}*/
			echo '<p>xfgxfggf' . $task . '</p>';
			parent::execute($task);
		}
		
		
	}
?>