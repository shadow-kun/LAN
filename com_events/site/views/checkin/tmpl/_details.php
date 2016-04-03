
<div id="details">
	<h2>Checkin - <?php time(); ?></h2>
	<h3>Event: <?php echo $this->player->event_name; ?></h3>
	<h3>Player: <?php echo $this->player->username; ?></h3>
	<h3>Status: 
	<?php
		switch(($this->player->status))
		{
			case 0:
				/* Player Not Registered for the event*/
				echo '<font color="red">' . JText::_('COM_EVENTS_ERROR_TICKET_NOT_REGISTERED') . '</font>';
				break;
			case 1: case 2:
				/* Registered but not paid */
				echo '<font color="red">' . JText::_('COM_EVENTS_ERROR_TICKET_NOT_PAID') . '</font>';
				break;
			case 3: case 4:
				/* Paid for the event */
				if(in_array($this->groupCheckin, JAccess::getGroupsByUser($this->player->user, $true)))
				{
					echo '<font color="red">' . JText::_('COM_EVENTS_ERROR_TICKET_CHECKED_IN') . '</font>';
				}
				else
				{
					echo '<font color="green">' . JText::_('COM_EVENTS_CHECKIN_ALLOWED') . '</font>';
					//echo '<br /><a name="selection" class="btn btn-primary" value="" onclick="eventCheckIn()" >' . JText::_('COM_EVENTS_CHECKIN_USER_LABEL') . '</button>'
					//echo '</br><a href="javascript:void(0);" onclick="checkinUser(' . (int) $this->player->id . ')" class="btn btn-primary" >' . JText::_('COM_EVENTS_CHECKIN_USER_LABEL', true) . '</a>';
					echo '</br><a class="btn" href="' . JRoute::_('index.php?option=com_events&view=checkin&layout=confirm&id=' . $this->player->id, false) . '" >'. JText::_('COM_EVENTS_CHECKIN_USER_LABEL', true) . '</a> ';
				}
				break;
			default:
				/* Can't find registration */
				echo '<font color="red">' . JText::_('COM_EVENTS_ERROR_TICKET_NOT_FOUND') . '</font>';
		} ?> </h3>
</div>