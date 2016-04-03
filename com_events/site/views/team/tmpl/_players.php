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
								echo '<a name="selection" class="btn" href="' . JRoute::_('index.php?option=com_events&view=team&layout=status&action=reject&id=' . $this->team->id . '&user='. $user->id) . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_REJECT_LABEL') . '</a> ';
								echo '<a name="selection" class="btn btn-primary" href="' . JRoute::_('index.php?option=com_events&controller=edit&view=team&layout=status&action=approve&id=' . $this->team->id . '&user=' . $user->id) . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_APPROVE_LABEL') . '</a>' ; 
							elseif ($user->status == 1) :
								echo '<a name="selection" class="btn" href="' . JRoute::_('index.php?option=com_events&view=team&layout=status&action=remove&id=' . $this->team->id . '&user='. $user->id) . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_REMOVE_LABEL') . '</a> ';
								if($this->currentUser->status == 4) :
									echo '<a name="selection" class="btn" href="' . JRoute::_('index.php?option=com_events&view=team&layout=status&action=moderator&id=' . $this->team->id . '&user='. $user->id) . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_MODERATOR_LABEL') . '</a> ';
								endif;
							elseif ($user->status == 2 && $this->currentUser->status == 4) :
								echo '<a name="selection" class="btn" href="' . JRoute::_('index.php?option=com_events&view=team&layout=status&action=remove&id=' . $this->team->id . '&user='. $user->id) . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_REMOVE_LABEL') . '</a> ';
								if($this->currentUser->status == 4) :
									echo '<a name="selection" class="btn" href="' . JRoute::_('index.php?option=com_events&view=team&layout=status&action=member&id=' . $this->team->id . '&user='. $user->id) . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_MEMBER_LABEL') . '</a> ';
								endif;
							endif; ?>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>