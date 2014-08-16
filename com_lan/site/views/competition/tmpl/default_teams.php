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
		
	$teams = null;
	 
	 ?>
	<?php if(JFactory::getUser()->guest) { 
		echo '<p><a href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
		echo JText::_('COM_LAN_COMPETITION_SUMMARY_LOGIN', true) . '</a>';
	} else {
	
		// Sorts teams into those currently registered and those not		
		$teams->registered[] = null;
		$teams->unregistered[] = null;
		
		foreach ($this->currentTeams as $t => $team)
		{
			if(isset($team->entryid))
			{
				$teams->registered[] = $team;
			}
			else
			{
				$teams->unregistered[] = $team;
			}
		}

		// Removes first null element from the stack.	
		array_shift($teams->registered);
		array_shift($teams->unregistered);
		
		
		echo '<div class="unregister-team">';
		// Teams in control of that are registered for the competition
		if(count($teams->registered) == 1)
		{
			echo '<p><button name="selection" class="btn btn-primary" value="unregister_team_competition" onclick="unregisterTeam()" >' . JText::_('COM_LAN_COMPETITION_SUMMARY_UNREGISTER_LABEL') . ' ' . $teams->registered[0]->name . '</button></p>';
			echo '<input type="hidden" name="unregisterTeamid" value="' . $teams->registered[0]->id . '" />';
		}
		elseif(count($teams->registered) > 1)
		{
			// Shows entered teams into this competition
			echo '<p><select name="unregisterTeamid">';
			foreach ($teams->registered as $t => $team) :
				echo '<option value="' . $team->id . '" >' . $team->name . '</option>';
			endforeach;
			echo '</select>';
			echo ' <button name="selection" class="btn btn-primary" value="unregister_team_competition" onclick="unregisterTeam()" >' . JText::_('COM_LAN_COMPETITION_SUMMARY_UNREGISTER_LABEL') . '</button></p>';
		}
		echo '</div>';
		
		echo '<div class="register-team">';
		// Teams in control of that are un-registered for the competition
		if(count($teams->unregistered) == 1)
		{
			echo '<p><button name="selection" class="btn btn-primary" value="register_team_competition" onclick="registerTeam()">' . JText::_('COM_LAN_COMPETITION_SUMMARY_REGISTER_LABEL') . ' ' . $teams->unregistered[0]->name . '</button></p>';
			echo '<input type="hidden" name="registerTeamid" value="' . $teams->unregistered[0]->id . '" />';
		}
		elseif(count($teams->unregistered) > 1)
		{
			// Shows non-entered teams into this competition
			echo '<p><select name="registerTeamid">';
			
			foreach ($teams->unregistered as $t => $team) :
				echo '<option value="' . $team->id . '" >' . $team->name . '</option>';
			endforeach;
			echo '</select>';
			echo ' <button name="selection" class="btn btn-primary" value="register_team_competition" onclick="registerTeam()" >' . JText::_('COM_LAN_COMPETITION_SUMMARY_REGISTER_LABEL') . '</button></p>';
		}
		echo '</div>';
		
	} ?>
	<h3><?php echo JText::_('COM_LAN_COMPETITION_SUBHEADING_TEAMS_LIST', true) ?></h3>
	<table class="list table table-striped">
		<thead>
			<tr>
				<th width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_LAN_COMPETITION_TABLE_COMPETITION_TEAMS_ORDER', 'id', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_LAN_COMPETITION_TABLE_COMPETITION_TEAMS_TEAM', 'name', $listDirn, $listOrder); ?>
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