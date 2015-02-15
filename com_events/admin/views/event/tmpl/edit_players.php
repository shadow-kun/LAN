<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		Events Party!
	* @subpackage	com_events
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/
	JHtml::_('behavior.tooltip');
	
	$user		= JFactory::getUser();
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
	
	
?>

<table class="adminlist table table-striped"">
	
	<thead>
		<tr>
			<th width="1%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(this)" />
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'COM_EVENTS_EVENT_TABLE_PLAYERS_PLAYER', 'p.username', $listDirn, $listOrder); ?>
			</th>
			<th width="10%">
				<?php echo JHTML::_('grid.sort', 'COM_EVENTS_EVENT_TABLE_PLAYERS_STATUS', 'status', $listDirn, $listOrder); ?>
			</th>
			<th width="1%">
				<?php echo JHTML::_('grid.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="15">
				<?php //echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php foreach ($this->players as $p => $player) :
			$player->max_ordering = 0;
			$ordering	= ($listOrder == 'id');
		?>
		<tr class="row<?php echo $p % 2; ?>">
			<td class="center">
				<?php echo JHtml::_('grid.id', $p, $player->id); ?>
			</td>
			<td class="left">
				<?php echo $this->escape($player->username); ?>
			</td>
			<td class="center">
				<input type="hidden" name="player_status_current#<?php $player->id; ?>" value="<?php $player->status; ?>" />
				<select name="player_status_change#<?php echo $player->id; ?>" >
					<option value="1" 
						<?php if((int) $player->status == 1) : 
							echo 'selected'; 
							endif; ?>
						><?php echo JText::_('COM_EVENTS_EVENT_PLAYERS_UNCONFIRMED', true); ?></option>
					<option value="2" 
						<?php if((int) $player->status == 2) :
							echo 'selected'; 
							endif; ?>
						><?php echo JText::_('COM_EVENTS_EVENT_PLAYERS_CONFIRMED', true); ?></option>
					<option value="3" 
						<?php if((int) $player->status == 3) :
							echo 'selected'; 
							endif; ?>
						><?php echo JText::_('COM_EVENTS_EVENT_PLAYERS_PAID', true); ?></option>
					<option value="4" 
						<?php if((int) $player->status == 4) :
							echo 'selected'; 
							endif; ?>
						><?php echo JText::_('COM_EVENTS_EVENT_PLAYERS_PREPAID', true); ?></option>
					<option value="-2" ><?php echo JText::_('COM_EVENTS_EVENT_PLAYERS_REMOVE', true); ?></option>
				</select>
			</td>
			<td class="center">
				<?php echo (int) $p + 1; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>