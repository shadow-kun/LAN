
<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	
	jimport('joomla.application.component.controllerform');
	
	/**
	 * Event Sub-Controller
	 *
	 * @package			LAN
	 * @subpackage		com_lan
	 * @since			0.0
	 */
	 
	class LANControllerCheckin extends JControllerLegacy
	{
		public function checkIn($cachable = false, $urlparams = null)
		{
			
			JSession::checkToken() or die( 'Invalid Token' );
			$app = JFactory::getApplication();
			
			// Gets competition id
			$id 	= JRequest::getVar('id');
			
			// Get model
			$model = JController::getModel('Checkin');
			
			// Gets database connection
			$db		= $this->getDbo();
			$query	= $db->getQuery(true);
		
			$group -> $this->get('CheckedInGroup');
			
			$app->enqueueMessage('Test Valid', 'warning');
			
			$query->select('*');
			$query->from('#__lan_competition_playerss');
			
			
			// Runs query
			$result = $db->setQuery($query)->loadObject();
			$db->query();
			
			
			parent::display($cachable, $urlparams);
		}
	}
	?>