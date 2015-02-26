<div id="buttons" >
	<?php if(JFactory::getUser()->guest) { 
		echo '<p><a class="btn btn-primary" href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
		echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_LOGIN_LABEL', true) . '</a>';
	} else { 
		if((int) $this->competition->params->competition_team == 0)
		{
			if(isset($this->currentUser->id))
			{
				if($this->currentUser->status >= 0)
					if(strtotime($this->competition->competition_start) > time())
					{
						echo '<p><a class="btn btn-primary" onclick="unregisterCompetitionUser()" href="javascript:void(0)" >' . JText::_('COM_EVENTS_COMPETITION_SUMMARY_USER_UNREGISTER_LABEL', true) . '</a> ';
					}
					else 
					{
						echo '<p><a class="btn" onclick="unregisterCompetitionUser()" href="javascript:void(0)" >' . JText::_('COM_EVENTS_COMPETITION_SUMMARY_USER_FORFEIT_LABEL', true) . '</a> ';
					}
			}
			else
			{ 
				if(strtotime($this->competition->competition_start) > time())
				{
					echo '<p><a class="btn btn-primary" onclick="registerCompetitionUser()" href="javascript:void(0)">' . JText::_('COM_EVENTS_COMPETITION_SUMMARY_USER_REGISTER_LABEL', true) . '</a> ';
				}
			}
		}
		else
		{
			echo '<p>';
			if($this->currentTeams['registered'] > 0 || $this->currentTeams['unregistered'] > 0)
			{
				if($this->currentTeams['registered'] >= 1 && $this->currentTeams['registered'] > $this->currentTeams['forfeit'] + $this->currentTeams['eliminated'])
				{
					echo ($this->currentTeams['registered'] - $this->currentTeams['forfeit'] - $this->currentTeams['eliminated'] == 1) ? '<select id="unregisterTeamID" name="unregisterTeamID" readonly >' : '<select id="unregisterTeamID" name="unregisterTeamID" >';
					foreach ($this->currentTeams as $t => $team)
					{				
						if(isset($team->entryid) && $team->status >= 0)
						{
							echo '<option value="' . $team->id . '">' . $team->name . '</option>';
						}
					}
					echo '</select>';
					
					if(strtotime($this->competition->competition_start) > time())
					{
						echo '<a class="btn btn-primary" onclick="unregisterCompetitionTeam()" href="javascript:void(0)" >' . JText::_('COM_EVENTS_COMPETITION_SUMMARY_TEAM_UNREGISTER_LABEL', true) . '</a> ';
					}
					else 
					{
						echo '<a class="btn" onclick="unregisterCompetitionTeam()" href="javascript:void(0)" >' . JText::_('COM_EVENTS_COMPETITION_SUMMARY_TEAM_FORFEIT_LABEL', true) . '</a> ';
					}
				}
			
				if($this->currentTeams['unregistered'] >= 1) 
				{
					if(strtotime($this->competition->competition_start) > time())
					{
						echo ($this->currentTeams['unregistered'] == 1) ? '<select id="registerTeamID" name="registerTeamID" readonly >' : '<select id="registerTeamID" name="registerTeamID" >';
						foreach ($this->currentTeams as $t => $team)
						{				
							if(!isset($team->entryid) && isset($team->name))
							{
								echo '<option value="' . $team->id . '">' . $team->name . '</option>';
							}
						}
						echo '</select>';
						
						echo '<a class="btn btn-primary" onclick="registerCompetitionTeam()" href="javascript:void(0)">' . JText::_('COM_EVENTS_COMPETITION_SUMMARY_TEAM_REGISTER_LABEL', true) . '</a> ';
					}
				}
				
			}
			else
			{
				echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_TEAM_REQUIRED_LABEL', true) . ' ';
			}
		}
	}
	echo '<a class="btn" onclick="showCompetitionEntrants()" href="javascript:void(0)">' . JText::_('COM_EVENTS_COMPETITION_SUMMARY_ENTRANTS_LABEL', true) . '</a></p>';	?>
</div>