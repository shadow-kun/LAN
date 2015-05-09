<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	jimport('joomla.application.component.helper');
	 
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
	 
	 ?>
	 <?php if(JFactory::getUser()->guest) { 
		echo '<p><a href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
		echo JText::_('COM_LAN_COMPETITION_SUMMARY_LOGIN', true) . '</a>';
	} else {
		if(isset($this->currentPlayer->id))
		{
			echo '<p><button name="selection" class="btn btn-primary" value="unregister_player_competition" onclick="unregisterPlayer()">' . JText::_('COM_LAN_COMPETITION_SUMMARY_UNREGISTER_LABEL') . '</button></p>';
	
		}
		else
		{ 
			echo '<p><button name="selection" class="btn btn-primary" value="register_player_competition" onclick="registerPlayer()" >' . JText::_('COM_LAN_COMPETITION_SUMMARY_REGISTER_LABEL') . '</button></p>';
		}
	} ?>
	
	<h3><?php echo JText::_('COM_LAN_COMPETITION_SUBHEADING_PLAYERS_LIST', true) ?></h3>
	<table class="list table table-striped">
		<thead>
			<tr>
				<th width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_LAN_COMPETITION_TABLE_COMPETITION_PLAYERS_ORDER', 'id', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_LAN_COMPETITION_TABLE_COMPETITION_PLAYERS_PLAYER', 'p.username', $listDirn, $listOrder); ?>
				</th>
				<th width="20%">
					<?php echo JHTML::_('grid.sort', 'COM_LAN_COMPETITION_TABLE_COMPETITION_PLAYERS_STATUS', 'status', $listDirn, $listOrder); ?>
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
				<td class="left">
					<?php echo (int) $p + 1; ?>
				</td>
				<td class="left">
					<?php echo $this->escape($player->username); ?>
				</td>
				<td class="center">
					<?php if(isset(json_decode($player->params)->status)) :
						echo $this->escape(json_decode($player->params)->status); 
					endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>