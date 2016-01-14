<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */

	if($this->event->params->paypal_global == 0)
	{
		$paypalEmail		= $this->params->get('paypal_email');// Event Setting
		$paypalAmount	 	= $this->event->params->cost_prepay;// Event Setting
		$paypalCurrency 	= $this->params->get('paypal_currency'); // Event Setting
		$paypalCancel		= JURI::root() . 'index.php?option=com_events&view=event&id=' . $this->event->id; // Send to event page
		$paypalReturn		= JURI::root() . 'index.php?option=com_events&view=event&id=' . $this->event->id; // Send to event page
		$paypalItem			= 'Event Ticket'; // Website Setting or Event Setting?
		$paypalItemNumber	= 'E-' . (int) $this->currentUser->id;
		$paypalSandbox 		= intval($this->params->get('paypal_sandbox'));
	}
	else
	{
		$paypalEmail		= $this->event->params->paypal_email;// Event Setting
		$paypalAmount	 	= $this->event->params->cost_prepay;// Event Setting
		$paypalCurrency 	= $this->event->params->paypal_currency; // Event Setting
		$paypalCancel		= JURI::root() . 'index.php?option=com_events&view=event&id=' . $this->event->id; // Send to event page
		$paypalReturn		= JURI::root() . 'index.php?option=com_events&view=event&id=' . $this->event->id; // Send to event page
		$paypalItem			= 'Event Ticket'; // Website Setting or Event Setting?
		$paypalItemNumber	= 'E-' . (int) $this->currentUser->id;
		$paypalSandbox 		= intval($this->event->params->paypal_sandbox);
	}

	$paypalSiteSandbox = "www.sandbox.paypal.com"; 
	$paypalSiteProduction = "www.paypal.com";
	
	// Sets if sandbox mode is on or off.
	if($paypalSandbox == 1)
	{
		$paypalSite = $paypalSiteSandbox;
		$paypalNotifyURL	= JURI::root() . 'components/com_events/ipn/paypalSandboxIPN.php'; // COM_LAN or event setting
	}
	else
	{
		$paypalSite = $paypalSiteProduction;
		$paypalNotifyURL	= JURI::root() . 'components/com_events/ipn/paypalIPN.php'; // COM_LAN or event setting
	}
	$p = 0;
?>
<tr class="row<?php echo $p % 2; ?>">
	<td class="center">	
		<form action="https://<?php echo $paypalSite; ?>/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" value="<?php echo $paypalEmail; ?>" name="business">
			<input type="hidden" value="<?php echo $paypalItem; ?>"	name="item_name">
			<input type="hidden" value="<?php echo $paypalItemNumber; ?>" name="item_number">
			<input type="hidden" value="<?php echo $paypalReturn; ?>" name="return">
			<input type="hidden" value="<?php echo $paypalCancel; ?>" name="cancel">
			<input type="hidden" value="<?php echo $paypalAmount; ?>" name="amount">
			<input type="hidden" value="services" name="button_subtype">
			<input type="hidden" value="<?php echo $paypalCurrency; ?>" name="currency_code" >
			<input type="hidden" value="<?php echo $paypalNotifyURL; ?>" name="notify_url" >
			<input type="image" src="https://<?php echo $paypalSite; ?>/en_AU/i/btn/btn_paynow_LG.gif" border="0" name="submit" alt="<?php echo JText::_("COM_EVENTS_EVENT_PREPAY_PAYPAL_DESC"); ?>">
			<img alt="" border="0" src="https://<?php echo $paypalSite; ?>/en_AU/i/scr/pixel.gif" width="1" height="1">
		</form>
	</td>
	<td class="left">	
		<?php echo JText::_('COM_EVENTS_EVENT_PREPAY_PAYPAL_DESC'); ?>
	</td>
</tr>