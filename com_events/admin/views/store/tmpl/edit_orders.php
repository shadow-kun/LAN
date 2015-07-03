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
			
				<?php 
				$status = '';
				switch($order->status)
				{
					case 0: case 1:
						$status = JText::_('COM_EVENTS_SHOP_STORE_ORDER_STATUS_UNPAID_LABEL');
						break;
					case 2:
						$status = JText::_('COM_EVENTS_SHOP_STORE_ORDER_STATUS_PAID_LABEL');
						break;
					case 3:
						$status = JText::_('COM_EVENTS_SHOP_STORE_ORDER_STATUS_COLLECTED_LABEL');
						break;
				} ?>
				<h3><?php echo $order->id . ' - ' . JFactory::getUser($order->user)->name . ' (' . $status . ')'; ?></h3>
				
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
				<div align="right">
					<input type="hidden" name="order_status_current#<?php $order->id; ?>" value="<?php $order->status; ?>" />
					<select name="order_status_change#<?php echo $order->id; ?>" >
						<option value="1" 
							<?php if($order->status == 1) : 
								echo 'selected'; 
								endif; ?>
							><?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDER_STATUS_UNPAID_LABEL', true); ?></option>
						<option value="2" 
							<?php if($order->status == 2) :
								echo 'selected'; 
								endif; ?>
							><?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDER_STATUS_PAID_LABEL', true); ?></option>
						<option value="3" 
							<?php if($order->status == 3) :
								echo 'selected'; 
							endif; ?>
							><?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDER_STATUS_COLLECTED_LABEL', true); ?></option>
						<option value="-2" ><?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDER_REMOVE_LABEL', true); ?></option>
					</select>
					<?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDER_TOTAL_LABEL') . ': ' . JText::_('COM_EVENTS_CURRENCY_SYMBOL') . $order->amount; ?>
				</div>
			</div>
		<?php }	?>
	</div>