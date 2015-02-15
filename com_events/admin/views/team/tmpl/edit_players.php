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
				<?php echo JHTML::_('grid.sort', 'COM_EVENTS_TEAM_TABLE_PLAYERS_PLAYER', 'p.username', $listDirn, $listOrder); ?>
			</th>
			<th width="10%" class="center">
				<?php echo JHTML::_('grid.sort', 'COM_EVENTS_TEAM_TABLE_PLAYERS_STATUS', 'status', $listDirn, $listOrder); ?>
			</th>
			<th width="10%" class="center">
				<?php echo JText::_('COM_EVENTS_TEAM_TABLE_PLAYERS_OPTIONS'); ?>
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
				<?php switch((int) $player->status)
				{
					case 0:
						echo JText::_('COM_EVENTS_TEAM_FIELD_STATUS_OPTION_APPLYING');
						break;
					case 1:
						echo JText::_('COM_EVENTS_TEAM_FIELD_STATUS_OPTION_MEMBER');
						break;
					case 2:
						echo JText::_('COM_EVENTS_TEAM_FIELD_STATUS_OPTION_MODERATOR');
						break;
					case 4:
						echo JText::_('COM_EVENTS_TEAM_FIELD_STATUS_OPTION_LEADER');
						break;
				} ?>
			</td>
			<td class="left">
				<?php if($player->status != 4) : ?>
					<select name="player_status_change#<?php echo $player->id; ?>" onchange="this.form.submit()">
						<option value=""><?php echo JText::_('COM_EVENTS_TEAM_FIELD_OPTIONS_OPTION_SELECT_TITLE'); ?></option>
						<?php if($player->status == 0)
						{ ?>
							<option value="approve"><?php echo JText::_('COM_EVENTS_TEAM_FIELD_OPTIONS_OPTION_APPROVE_TITLE'); ?></option>
							<option value="reject"><?php echo JText::_('COM_EVENTS_TEAM_FIELD_OPTIONS_OPTION_REJECT_TITLE'); ?></option>
						<?php } else 
						{
							if($player->status == 1) 
							{ ?>
								<option value="moderator"><?php echo JText::_('COM_EVENTS_TEAM_FIELD_OPTIONS_OPTION_MODERATOR_TITLE'); ?></option>
							<?php } else { ?>
								<option value="member"><?php echo JText::_('COM_EVENTS_TEAM_FIELD_OPTIONS_OPTION_MEMBER_TITLE'); ?></option>
							<?php } ?>
							<option value="leader"><?php echo JText::_('COM_EVENTS_TEAM_FIELD_OPTIONS_OPTION_LEADER_TITLE'); ?></option>
							<option value="remove"><?php echo JText::_('COM_EVENTS_TEAM_FIELD_OPTIONS_OPTION_REMOVE_TITLE'); ?></option>
						<?php } ?>
					</select>
				<?php endif; ?>
			</td>
			<td class="center">
				<?php echo (int) $p + 1; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>