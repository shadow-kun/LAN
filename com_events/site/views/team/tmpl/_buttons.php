<?php if(JFactory::getUser()->guest) { 
	echo '<p><a href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
	echo JText::_('COM_EVENTS_TEAM_SUMMARY_LOGIN', true) . '</a>';
} else { 
	if(isset($this->currentUser->id))
	{
		if($this->currentUser->status == 4)
		{
			echo '<p><button name="selection" class="btn btn-primary" value="team_edit_details" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_EDIT_TEAM_LABEL', true) . '</button> ';
			echo '<button name="selection" class="btn" value="team_edit_leader" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_EDIT_LEADER_LABEL', true) . '</button> ';
			echo '<button name="selection" class="btn" value="team_delete" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_DELETE_LABEL', true) . '</button></p>';
		}
		elseif($this->currentUser->status >= 2)
		{
			echo '<p><button name="selection" class="btn btn-primary" value="team_edit_details" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_EDIT_TEAM_LABEL', true) . '</button> ';
			echo '<button name="selection" class="btn" value="unregister_player_team" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_UNREGISTER_LABEL', true) . '</button></p>';
		}
		else
		{
			echo '<p><button name="selection" class="btn btn-primary" value="unregister_player_team" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_UNREGISTER_LABEL', true) . '</button></p>';
		}
		
		

	}
	else
	{ 
		echo '<p><a class="btn btn-primary" onclick="registerTeamMember()" href="javascript:void(0)">' . JText::_('COM_EVENTS_TEAM_SUMMARY_REGISTER_LABEL', true) . '</a></p>';
	}
} ?></p>