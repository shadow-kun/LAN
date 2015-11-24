<div id="details">
	<h3><?php echo JText::_('COM_EVENTS_TEAM_SUBHEADING_USERS_LIST', true) ?></h3>
	<table class="list table table-striped">
		<thead>
			<tr>
				<th width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_EVENTS_TEAM_TABLE_TEAM_USERS_ORDER', 'id', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_EVENTS_TEAM_TABLE_TEAM_USERS_USER', 'p.username', $listDirn, $listOrder); ?>
				</th>
				<th width="15%">
					<?php echo JHTML::_('grid.sort', 'COM_EVENTS_TEAM_TABLE_TEAM_USERS_STATUS', 'status', $listDirn, $listOrder); ?>
				</th>
				<?php if(isset($this->currentUser->status) && $this->currentUser->status >= 2) : ?>
					<th width="30%">
						<?php echo JText::_('COM_EVENTS_TEAM_TABLE_TEAM_USERS_MANAGEMENT'); ?>
					</th>
				<?php endif; ?>
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
			<?php foreach ($this->users as $u => $user) :
				$user->max_ordering = 0;
				$ordering	= ($listOrder == 'id');
			?>
				<tr class="row<?php echo $p % 2; ?>">
					<td class="left">
						<?php echo (int) $p + 1; ?>
					</td>
					<td class="left">
						<?php echo $this->escape($user->username); ?>
					</td>
					<td class="left">
						<?php switch((int) $user->status)
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
					<?php if(isset($this->currentUser->status) && $this->currentUser->status >= 2) : ?>
						<td class="left">
							<?php if($user->status == 0) :
								echo '<button name="selection" class="btn" value="team_status_reject#' . $user->id . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_REJECT_LABEL') . '</button> 
									  <button name="selection" class="btn btn-primary" value="team_status_approve#' . $user->id . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_APPROVE_LABEL') . '</button>';
							elseif ($user->status == 1) :
								echo '<button name="selection" class="btn" value="team_status_remove#' . $user->id . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_REMOVE_LABEL') . '</button>';
								if($this->currentUser->status == 4) :
									echo '<button name="selection" class="btn" value="team_status_moderator#' . $user->id . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_MODERATOR_LABEL') . '</button>';
								endif;
							elseif ($user->status == 2 && $this->currentUser->status == 4) :
								echo '<button name="selection" class="btn" value="team_status_remove#' . $user->id . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_REMOVE_LABEL') . '</button>';
								echo '<button name="selection" class="btn" value="team_status_member#' . $user->id . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_MEMBER_LABEL') . '</button>';
							endif; ?>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>