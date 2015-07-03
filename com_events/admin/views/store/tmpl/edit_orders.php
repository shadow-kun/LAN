<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */ ?>
	 
	<div id="form-orders-details" class="span8">
		<?php foreach($this->orders as $o => $order)
		{ ?>
			<div>
			<h3><?php echo $order->id . ' - ' . JFactory::getUser($order->user)->name; ?></h3>
				<table class="adminlist table table-striped">
				<?php foreach($order->items as $i => $item)
				{ ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td ><?php echo $item->title; ?></td>
						<td width="5%"><?php echo $item->quantity; ?></td>
						<td width="10%"><?php echo JText::_('COM_EVENTS_CURRENCY_SYMBOL') . $item->amount; ?></td>
							
					</tr>
				<?php } ?>
				</table>
			</div>
		<?php }	?>
	</div>