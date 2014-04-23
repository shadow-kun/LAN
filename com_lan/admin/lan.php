<?php defined( '_JEXEC' ) or die( 'Restricted access' );

   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	
	// Access Verification Check
	if(!JFactory::getUser()->authorise('core.manage', 'com_lan'))
	{
		return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	}
	//application
    $app = JFactory::getApplication();
	
	if($controller = $app->input->get('controller','default')) {
		require_once (JPATH_COMPONENT.'/controllers/'.$controller.'.php');
    }
	
	jimport('joomla.application.component.controller');
		
	// Get an instance of the controller prefixed by <name>
	$controller = JControllerLegacy::getInstance('lan');
 
	// Perform the Request task
	$controller->execute(JFactory::getApplication()->input->getCmd('task'));
 
	// Redirect if set by the controller
	$controller->redirect();
?>