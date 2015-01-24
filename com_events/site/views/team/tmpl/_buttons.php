<div id="buttons" >
	<?php if(JFactory::getUser()->guest) { 
		echo '<p><a class="btn btn-primary" href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
		echo JText::_('COM_EVENTS_TEAM_SUMMARY_LOGIN_LABEL', true) . '</a>';
	} else { 
		if(isset($this->currentUser->id))
		{
			if($this->currentUser->status == 4)
			{
				echo '<p><a class="btn btn-primary" href="' . JRoute::_('index.php?option=com_events&view=team&layout=details&id=' . JRequest::getVar('id')) . '" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_EDIT_TEAM_LABEL', true) . '</a> ';
				echo '<a class="btn" onclick="showOptionTeamLeader()" href="javascript:void(0)" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_EDIT_LEADER_LABEL', true) . '</a> ';
				echo '<a class="btn" onclick="showOptionTeamDelete()" href="javascript:void(0)" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_DELETE_LABEL', true) . '</a></p>';
			}
			elseif($this->currentUser->status >= 2)
			{
				echo '<p><button name="selection" class="btn btn-primary" value="team_edit_details" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_EDIT_TEAM_LABEL', true) . '</button> ';
				echo '<a class="btn" onclick="unregisterTeamMember()" href="javascript:void(0)" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_UNREGISTER_LABEL', true) . '</a></p>';
			}
			else
			{
				echo '<p><a class="btn btn-primary" onclick="unregisterTeamMember()" href="javascript:void(0)" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_UNREGISTER_LABEL', true) . '</a></p>';
			}
			
			

		}
		else
		{ 
			echo '<p><a class="btn btn-primary" onclick="registerTeamMember()" href="javascript:void(0)">' . JText::_('COM_EVENTS_TEAM_SUMMARY_REGISTER_LABEL', true) . '</a></p>';
		}
	} ?></p>
</div>