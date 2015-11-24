<div id="details" >
	<div>
		<p><?php echo JText::_('COM_EVENTS_TEAM_LEADER_TEAM_LEADER_LABEL'); ?> - 
		<select id="teamleader">
			<?php foreach ($this->users as $u => $user) :
				$user->max_ordering = 0;
				$ordering	= ($listOrder == 'id');
			?>
				<option 
					<?php if($user->user == 4) : echo 'selected'; endif; ?> value="<?php echo $user->userid; ?>"><?php echo $user->username; ?></option>
			<?php endforeach; ?>
		</select></p>
	</div>
	<div><button class="btn" value="cancel"><?php echo JText::_('COM_EVENTS_TEAM_SUMMARY_CANCEL_LABEL');?></button>
	<a class="btn btn-primary" onclick="updateOptionTeamLeader()" href="javascript:void(0)"><?php echo JText::_('COM_EVENTS_TEAM_SUMMARY_LEADER_CONFIRM_LABEL');?></a></div>
</div>