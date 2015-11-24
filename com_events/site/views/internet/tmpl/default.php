<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
?>

<!-- If next LAN is present, show details otherwise show default no information msg -->
	<p><strong><?php JText::_('COM_EVENTS_INTERNET_PERMISSIONS_DEFAULT_TITLE', true); ?></strong></p>
	
	<?php // Show header info & if allowed add machine button 
		echo '<p>' . JText::_('COM_EVENTS_INTERNET_PERMISSIONS_DEFAULT_HEADER_LABEL', true) . '</p>'; 
		
		if(JFactory::getUser()->authorise('core.edit.state','com_events'))
		{
			echo '<div id="button"><button type="button" onclick="addInternetToken()">' . JText::_('COM_EVENTS_INTERNET_PERMISSIONS_DEFAULT_ADD_LABEL', true) . '</button></div>';
		}
	?>
	<div id="details" >
	
	</div>
	<?php // user section ?>
	<table>
		<tr>
			<th><?php echo JText::_('COM_EVENTS_INTERNET_PERMISSIONS_DEFAULT_USER_ID_HEADING', true); ?></th>
			<th><?php echo JText::_('COM_EVENTS_INTERNET_PERMISSIONS_DEFAULT_USER_MACHINE_NAME_HEADING', true); ?></th>
			<th><?php echo JText::_('COM_EVENTS_INTERNET_PERMISSIONS_DEFAULT_USER_MAC_ADDRESS_HEADING', true); ?></th>
			<th><?php echo JText::_('COM_EVENTS_INTERNET_PERMISSIONS_DEFAULT_USER_IP_ADDRESS_HEADING', true); ?></th>
			<th><?php echo JText::_('COM_EVENTS_INTERNET_PERMISSIONS_DEFAULT_USER_CREATED_TIME_HEADING', true); ?></th>
			<th><?php echo JText::_('COM_EVENTS_INTERNET_PERMISSIONS_DEFAULT_USER_EXPIRE_TIME_HEADING', true); ?></th>
			<th><?php echo JText::_('COM_EVENTS_INTERNET_PERMISSIONS_DEFAULT_USER_INTERFACE_HEADING', true); ?></th>
		</tr>
		<?php foreach($this->userDetails as $r => $row)
		{ ?>
			<tr>
				<td><?php echo $row->id; ?></td>
				<td><?php echo $row->mac_address; ?></td>
				<td><?php echo $row->ip_address; ?></td>
				<td><?php echo $row->created_time; ?></td>
				<td><?php echo $row->expire_time; ?></td>
				<td><?php echo $row->intf; ?></td>
			</tr>
		<? } ?>
	
	</table>
	<?php // admin section ?>