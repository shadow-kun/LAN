<div id="payments">
	<h2>Payments - <?php echo time(); ?></h2>
	<h3>Event: <?php echo $this->player->event_name; ?></h3>
	<h3>Player: <?php echo $this->player->username; ?></h3>
	<h3>Status: 
		<?php echo '</br><a href="javascript:void(0);" onclick="checkinUserPayment(' . (int) $this->player->id . ')" class="btn btn-primary" >' . JText::_('COM_EVENTS_CHECKIN_USER_LABEL', true) . '</a>';
				 ?> </h3>
</div>