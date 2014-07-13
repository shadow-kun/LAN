<?php
	// No direct access to this file
	defined('_JEXEC') or die('Restricted access');
	 
	// import Joomla controller library
	jimport('joomla.application.component.controller');
	 
	/**
	 * Hello World Component Controller
	 */
	class LANController extends JControllerLegacy
	{
		public function cancelRegistration($key = null)
		{
			JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

			echo "nay!";
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=event&id=' . $this->item->id , false));
			return true;
		}
		
		public function submit($id = null)
		{
			JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
			
			echo "Booyeh!";
			$this->setRedirect(JRoute::_('index.php'/*?option=' . $this->option . '&view=event&id=' . $this->item->id*/ , false));
			return true;
		}
		
		public function confirmRegistration($id = null)
		{
			JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
			
			echo "Booyeh!";
			$this->setRedirect(JRoute::_('index.php'/*?option=' . $this->option . '&view=event&id=' . $this->item->id*/ , false));
			return true;
		}
	}
?>