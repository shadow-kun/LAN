<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	jimport('joomla.application.component.helper');
	
	?>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=store&view=adminshop'); ?>"
	method="post" name="adminForm" id="store-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=store&view=adminshop'); ?>"><?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDERS_TITLE'); ?></a></h2>
					
	<div class="form-horizontal">
		<div id="details">	
			<div class="row-fluid">
			
			</div>
			<div id="items">
				<?php foreach($this->orders as $o => $order) : ?>
				<?php $total = 0; ?>
				<div class="group" >
					<h3><?php echo $order->user . ' (' . $order->title . JText::_('COM_EVENTS_SHOP_STORE_ORDERS_ID_LABEL') . ' - ' . $order->id . ')'; ?></h4>
					<p><strong><?php echo JText::_('COM_EVENTS_SHOP_STORE_ADMIN_STATUS_LABEL') . '</strong> - '; ?>
					<select onchange="orderStatusChange(<?php echo $order->id; ?>)" id="order<?php echo $order->id; ?>">
					<?php for($i = -2; $i < 5; $i++)
					{
						switch($i)
						{
							case -2:
								$status = 'COM_EVENTS_SHOP_STORE_ADMIN_STATUS_CANCELLED';
								break;
							case -1:
								$status = 'COM_EVENTS_SHOP_STORE_ADMIN_STATUS_FAILED';
								break;
							case 0:
								$status = 'COM_EVENTS_SHOP_STORE_ADMIN_STATUS_REQUIRED_PRE_PAYMENT';
								break;
							case 1:
								$status = 'COM_EVENTS_SHOP_STORE_ADMIN_STATUS_REQUIRED';
								break;
							case 2:
								$status = 'COM_EVENTS_SHOP_STORE_ADMIN_STATUS_PAID';
								break;
							case 3:
								$i = 4;
							case 4:
								$status = 'COM_EVENTS_SHOP_STORE_ADMIN_STATUS_COLLECTED';
								break;
						}
						
						if($i == $order->status)
						{
							echo '<option selected="selected" value=' . $i . '>' . JText::_($status) . '</option>';
						}
						else
						{
							echo '<option value=' . $i . '>' . JText::_($status) . '</option>';
						} 	
					}?>
					</select>
					<table class="list table table-striped" style="width: 100%">
						<tr>
							<th width="75%" align="left">
								<?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDERS_TABLE_HEADER_ITEM'); ?>
							</th>
							<th width="10%" align="right">
								<?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDERS_TABLE_HEADER_COST'); ?>
							</th>
							<th width="5%" align="right">
								<?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDERS_TABLE_HEADER_QUANTITY'); ?>
							</th>
							<th width="10%" align="right">
								<?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDERS_TABLE_HEADER_TOTAL'); ?>
							</th>
						</tr>
						<?php foreach($order->items as $i => $item) : ?>
						<tr class="row<?php echo $o % 2; ?>">
							<td><?php echo $item->title; ?></td>
							<td align="right"><?php echo JText::_('COM_EVENTS_SYMBOL_CURRENCY') . $item->amount; ?></td>
							<td align="right"><?php echo $item->quantity; ?></td>
							<td align="right"><?php $total += bcmul($item->amount, $item->quantity, 2); 
								echo JText::_('COM_EVENTS_SYMBOL_CURRENCY') . bcmul($item->amount, $item->quantity, 2); ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
					<div class="right" align="right" >
						<?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDERS_TOTAL_LABEL') . ': ' . JText::_('COM_EVENTS_SYMBOL_CURRENCY') . number_format($total, 2) . ' '; ?>
					</div>
				</div>
			</div>
			
			<?php endforeach; ?>
		</div>
	</div>
	
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" id="storeid" name="store" value="<?php echo $this->store->id; ?>" />
	<input id="task" type="hidden" name="task" value="" />
</form>