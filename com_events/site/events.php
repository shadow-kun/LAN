<?php defined( '_JEXEC' ) or die( 'Restricted access' );

   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	
	//sessions
	jimport( 'joomla.session.session' );
	
	//load classes
	JLoader::registerPrefix('Events', JPATH_COMPONENT);
	
	//Load plugins
	JPluginHelper::importPlugin('events');

	//Load styles and javascripts
    EventsHelpersStyle::load();
	
	// Loads user permissions
    EventsHelpersActions::load();
	
	$app = JFactory::getApplication();
	
	if($controller = $app->input->get('controller','default')) {
		require_once (JPATH_COMPONENT.'/controllers/'.$controller.'.php');
    }
	
	
	// Create the controller
	$classname = 'EventsController' . ucwords($controller);
	
	$controller = new $classname();
	
	// Perform the Request task
	$controller->execute();
	
	
?>