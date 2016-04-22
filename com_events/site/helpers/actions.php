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
	
	class EventsHelpersActions
	{
		/**
		 * Gets a list of actions that can performed.
		 *
		 * @return	JObject
		 * @since	0.0
		 */
		 
		public static function load($type = null, $id = 0)
		{
			jimport('jooma.access.access');
			$user 	= JFactory::getUser();
			$result	= new JObject;
			
			// Global Settings
			if($type == 'event' && $id > 0)
			{
				$assetName = 'com_events.event.' . (int) $id;
			}
			else 
			{
				$assetName = 'com_events';
			}
			
			$actions = JAccess::getActions('com_events', 'component');
			
			foreach ($actions as $action)
			{
				$result->set($action->name, $user->authorise($action->name, $assetName));
			}
			
			
			
			/*else
			{
				$actions = array(
					'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete');
					
				foreach ($actions as $action)
				{
					$result->set($action, $user->authorise($action, 'com_events'));
				}
			}*/
			
			
			
			
			return $result;
		}
		
		function check()
		{
			jimport('joomla.filter.output');
			if (empty($this->alias))
			{
				$this->alias = $this->title;
			}
			$this->alias = JFilterOutput::stringURLSafe($this->alias);
		 
			/* All your other checks */
			return true;
		}
	}
?>