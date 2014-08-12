<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	 
	 ?>
	<?php if(JFactory::getUser()->guest) { 
		echo '<p><a href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
		echo JText::_('COM_LAN_COMPETITION_SUMMARY_LOGIN', true) . '</a>';
	} else {
		echo '<p>Signup Not Avaliable Currently</p>'; 
	} ?>
	<h3><?php echo JText::_('COM_LAN_COMPETITION_SUBHEADING_TEAMS_LIST', true) ?></h3>
	<table class="list table table-striped">
		<thead>
			<tr>
				<th width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_LAN_COMPETITION_TABLE_COMPETITION_TEAMS_ORDER', 'id', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_LAN_COMPETITION_TABLE_COMPETITION_TEAMS_TEAM', 't.name', $listDirn, $listOrder); ?>
				</th>
				<th width="20%">
					<?php echo JHTML::_('grid.sort', 'COM_LAN_COMPETITION_TABLE_COMPETITION_TEAMS_STATUS', 'status', $listDirn, $listOrder); ?>
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
			<?php foreach ($this->teams as $t => $team) :
				$team->max_ordering = 0;
				$ordering	= ($listOrder == 'id');
			?>
			<tr class="row<?php echo $t % 2; ?>">
				<td class="left">
					<?php echo (int) $t + 1; ?>
				</td>
				<td class="left">
					<?php echo $this->escape($team->name); ?>
				</td>
				<td class="center">
					<?php if(isset(json_decode($team->params)->status)) :
						echo $this->escape(json_decode($team->params)->status); 
					endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>