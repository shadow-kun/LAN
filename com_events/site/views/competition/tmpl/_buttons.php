<div id="buttons" >
	<?php if(JFactory::getUser()->guest) { 
		echo '<p><a class="btn btn-primary" href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
		echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_LOGIN_LABEL', true) . '</a>';
	} else { 
		if(isset($this->currentUser->id))
		{
			
			{
				echo '<p><a class="btn btn-primary" onclick="unregisterCompetitionUser()" href="javascript:void(0)" >' . JText::_('COM_EVENTS_COMPETITION_SUMMARY_USER_UNREGISTER_LABEL', true) . '</a></p>';
			}
			
			

		}
		else
		{ 
			echo '<p><a class="btn btn-primary" onclick="registerCompetitionUser()" href="javascript:void(0)">' . JText::_('COM_EVENTS_COMPETITION_SUMMARY_USER_REGISTER_LABEL', true) . '</a></p>';
		}
	} ?></p>
</div>