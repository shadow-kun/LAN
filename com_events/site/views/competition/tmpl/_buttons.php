<div id="buttons" >
	<?php if(JFactory::getUser()->guest) { 
		echo '<p><a class="btn btn-primary" href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
		echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_LOGIN_LABEL', true) . '</a>';
	} else { 
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
	echo '<a class="btn" onclick="showCompetitionEntrants()" href="javascript:void(0)">' . JText::_('COM_EVENTS_COMPETITION_SUMMARY_ENTRANTS_LABEL', true) . '</a></p>';	?>
</div>