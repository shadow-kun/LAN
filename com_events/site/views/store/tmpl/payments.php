<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	
	/**
	 * @package		LAN
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	jimport( 'joomla.access.access' );
	
	jimport('joomla.application.component.helper');
	 
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
?>

	<div class="span5 well">
		<div class="row-fluid">
				<table class="list table table-striped">
					<thead>
						<tr>
							<th width="20%">
								<?php echo JText::_('COM_EVENTS_SHOP_ORDER_PAYMENT_TABLE_PAYMENT_TYPE_TITLE'); ?>
							</th>
							<th>
								<?php echo JText::_('COM_EVENTS_SHOP_ORDER_PAYMENT_TABLE_PAYMENT_DESC_TITLE'); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php $p = 0; 
						$paypal = intval(json_decode($this->store->params->paypal));
						if($paypal > 0)
						{
							echo EventsHelpersView::load('store','_prepay_paypal','phtml');
							$p++;
						}
						
						if($paypal < 2)
						{ ?>
							<tr class="row<?php echo $p % 2; ?>">
								<td><?php echo JText::_('COM_EVENTS_SHOP_ORDER_PAYMENT_ONSITE_LABEL'); ?></td>
								<td><?php echo JText::_('COM_EVENTS_SHOP_ORDER_PAYMENT_ONSITE_DESC'); ?></td>
							</tr>
						<?php $p++; } ?>
					</tbody>
				</table>
			</div>
	</div>
	