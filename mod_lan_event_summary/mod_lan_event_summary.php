<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		LAN
	 * @subpackage	mod_lan_event_summary
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	 
	// Include the syndicate functions only once
	require_once( dirname(__FILE__).'/helper.php' );
	
	$event = modLanEventSummaryHelper::getEvent( $params );
	$player = modLanEventSummaryHelper::getCurrentPlayer( $params );
	require(JModuleHelper::getLayoutPath('mod_lan_event_summary'));
?>