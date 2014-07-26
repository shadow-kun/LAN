<?php
	// No direct access to this file
	defined('_JEXEC') or die('Restricted access');
	 
	// import Joomla controller library
	jimport('joomla.application.component.controller');
	 
	 
	class LANControllerCompetition extends JControllerLegacy
	{
		public function submit($id = null)
		{
			JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
			
			echo 'yo!';
			return true;
		}
	}
?>