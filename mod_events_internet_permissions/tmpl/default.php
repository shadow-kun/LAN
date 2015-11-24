<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	MOD_EVENTS_event_summary
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
?>

<!-- If next LAN is present, show details otherwise show default no information msg -->
	<p><strong><?php JText::_('MOD_EVENTS_INTERNET_PERMISSIONS_DEFAULT_TITLE', true); ?></strong></p>
	
	<?php // Show header info & if allowed add machine button 
		echo '<p>' . JText::_('MOD_EVENTS_INTERNET_PERMISSIONS_DEFAULT_HEADER_LABEL', true) . '</p>'; 
		
		if(JFactory::getUser()->authorise('core.view','mod_events_internet_permissions'))
		{
			echo '<button type="button" onclick="addMachine()">' . JText::_('MOD_EVENTS_INTERNET_PERMISSIONS_DEFAULT_ADD_LABEL', true) . '</button>';
		}
	?>
	
	<?php // user section ?>
	<table>
		<tr>
			<th><?php echo JText::_('MOD_EVENTS_INTERNET_PERMISSIONS_DEFAULT_USER_ID_HEADING', true); ?></th>
			<th><?php echo JText::_('MOD_EVENTS_INTERNET_PERMISSIONS_DEFAULT_USER_MACHINE_NAME_HEADING', true); ?></th>
			<th><?php echo JText::_('MOD_EVENTS_INTERNET_PERMISSIONS_DEFAULT_USER_MAC_ADDRESS_HEADING', true); ?></th>
			<th><?php echo JText::_('MOD_EVENTS_INTERNET_PERMISSIONS_DEFAULT_USER_IP_ADDRESS_HEADING', true); ?></th>
			<th><?php echo JText::_('MOD_EVENTS_INTERNET_PERMISSIONS_DEFAULT_USER_CREATED_TIME_HEADING', true); ?></th>
			<th><?php echo JText::_('MOD_EVENTS_INTERNET_PERMISSIONS_DEFAULT_USER_EXPIRE_TIME_HEADING', true); ?></th>
			<th><?php echo JText::_('MOD_EVENTS_INTERNET_PERMISSIONS_DEFAULT_USER_INTERFACE_HEADING', true); ?></th>
		</tr>
		<?php foreach($currentuser as $r => $row)
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