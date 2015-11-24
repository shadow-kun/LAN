<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */ 
	
	$canDo	= EventsHelper::getActions(); ?>
			
	<div id="form-payments-details" class="span8">
		<?php if($canDo->get('core.admin'))
		{
			// Is admin for purposes of looking at transactions ?>
			<div>
				<h3><?php echo JText::_('COM_EVENTS_EVENT_PAYMENTS_HEADING'); ?></h3>
				
				<table class="adminlist table table-striped">
				<?php foreach($this->payments as $p => $payment)
				{ ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td><?php echo $payment->id; ?></td>
						<td><?php echo $payment->orderID; ?></td>
						<td><?php echo $payment->created_time; ?></td>
						<td><?php echo JFactory::getUser($payment->user)->name . '(' . JText::_('COM_EVENTS_EVENT_PAYMENTS_USERID_LABEL') . ' - ' . $payment->user . ')'; ?></td>
						<td><?php echo JText::_('COM_EVENTS_CURRENCY_SYMBOL') . number_format($payment->amount, 2); ?></td>
						<td><?php echo $payment->currency; ?></td>
						<td><?php echo $payment->transaction_id; ?></td>
						<td><?php foreach($payment->params as $pp => $param) {
							echo $pp . ' - ' . $param . ", "; } ?> </td>	
					</tr>
				<?php } ?>
				</table>
			</div>
		<?php } else {
			// Refuse access based off permissions
			echo JText::_('COM_EVENTS_AUTHORIZATION_FAILURE_LABEL'); 
		} ?>
		
	</div>