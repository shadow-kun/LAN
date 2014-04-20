<?php // No direct access
    defined( '_JEXEC' ) or die( 'Restricted access' );
     
    //sessions
    jimport( 'joomla.session.session' );
     
    //load tables
    JTable::addIncludePath(JPATH_COMPONENT.'/tables');
     
    //load classes
    JLoader::registerPrefix('LAN', JPATH_COMPONENT);
     
    //Load plugins
    JPluginHelper::importPlugin('lan');
	
	//Load styles and javascripts
    LANHelpersStyle::load();
     
    //application
    $app = JFactory::getApplication();
     
    // Require specific controller if requested
    if($controller = $app->input->get('controller','default')) {
    require_once (JPATH_COMPONENT.'/controllers/'.$controller.'.php');
    }
     
    // Create the controller
    $classname = 'LANControllers'.$controller;
    $controller = new $classname();
     
    // Perform the Request task
    $controller->execute();
	
?>