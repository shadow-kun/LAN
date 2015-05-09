<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	
   /**
	* LAN Party Component Helper
	*
	* @package		LAN
	* @subpackage	com_lan
	*/
	
	class LANHelper
	{
		/**
		 * Gets a list of actions that can performed.
		 *
		 * @return	JObject
		 * @since	0.0
		 */
		 
		public static function getActions()
		{
			$user 	= JFactory::getUser();
			$result	= new JObject;
			
			$actions = array(
				'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete');
				
			foreach ($actions as $action)
			{
				$result->set($action, $user->authorise($action, 'com_lan'));
			}
			
			return $result;
		}
	}
?>