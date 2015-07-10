<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	mod_events_internet_permissions
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	 
	// Include the syndicate functions only once
	require_once( dirname(__FILE__).'/helper.php' );
	
	
	if(JFactory::getUser()->authorise('core.edit.state','mod_events_internet_permissions'))
	{
		$allusers = modEventsInternetPermissionsHelper::getAllUsers($params);
	}	
		
	$currentuser = modEventsInternetPermissionsHelper::getCurrentUser($params);
	require(JModuleHelper::getLayoutPath('mod_events_internet_permissions'));
?>