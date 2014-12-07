<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	 jimport( 'joomla.access.access' );
	 
	 $user = JFactory::getUser($this->escape($this->item->user));
	 
?>
<h2>QR Code Checkin</h2>
<h3>Event: <?php echo $this->item->event_name; ?></h3>
<h3>Player: <?php echo $user->name; ?></h3>
<h3>Status: 
<?php
	switch($this->escape($this->item->status))
	{
		case 0:
			/* Player Not Registered for the event*/
			echo '<font color="red">' . JText::_('COM_LAN_ERROR_TICKET_NOT_REGISTERED') . '</font>';
			break;
		case 1: case 2:
			/* Registered but not paid */
			echo '<font color="red">' . JText::_('COM_LAN_ERROR_TICKET_NOT_PAID') . '</font>';
			break;
		case 3: case 4:
			/* Paid for the event */
			if(in_array($this->groupCheckedIn, JAccess::getGroupsByUser($user->id, $true)))
			{
				echo '<font color="red">' . JText::_('COM_LAN_ERROR_TICKET_CHECKED_IN') . '</font>';
			}
			else
			{
				echo '<font color="green">' . JText::_('COM_LAN_CHECKIN_ALLOWED') . '</font>';
			}
			break;
		default:
			/* Can't find registration */
			echo '<font color="red">' . JText::_('COM_LAN_ERROR_TICKET_NOT_FOUND') . '</font>';
	} ?> </h3>