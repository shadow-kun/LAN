<div id="payments">
	
	<?php if(isset($this->player->status) && $this->player->status < 3)
	{ ?>
		
		<div class="span5 well">
			<h2><?php echo JText::_('COM_EVENTS_CHECKIN_PAYMENT_LABEL', true) . ' - ' . time(); ?></h2>
			<h3><?php echo JText::_('COM_EVENTS_CHECKIN_PAYMENT_COST_LABEL', true) . ': $' . $this->player->event_params->cost_event; ?></h3>
			<?php echo '<h3></br><a href="javascript:void(0);" onclick="checkinUserPayment(' . (int) $this->player->id . ')" class="btn" >' . JText::_('COM_EVENTS_CHECKIN_PAY_ONLY_USER_LABEL', true) . '</a>';
			echo '<a href="javascript:void(0);" onclick="checkinUserPaymentCheckin(' . (int) $this->player->id . ')" class="btn btn-primary" >' . JText::_('COM_EVENTS_CHECKIN_PAY_CHECKIN_USER_LABEL', true) . '</a></h3>';
		
		echo '</div>';
	} ?>
</div>