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
				echo '<a class="btn" href="'. JRoute::_('index.php?option=com_events&view=team&layout=leader&id=' . JRequest::getVar('id')) . '" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_EDIT_LEADER_LABEL', true) . '</a> ';
				echo '<a class="btn" href="'. JRoute::_('index.php?option=com_events&view=team&layout=delete&id=' . JRequest::getVar('id')) . '" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_DELETE_LABEL', true) . '</a></p>';
			}
			elseif($this->currentUser->status >= 2)
			{
				echo '<p><a class="btn btn-primary" href="' . JRoute::_('index.php?option=com_events&view=team&layout=details&id=' . JRequest::getVar('id')) . '" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_EDIT_TEAM_LABEL', true) . '</a> ';
				echo '<p><a class="btn btn-primary" href="' . JRoute::_('index.php?option=com_events&view=team&layout=unregister&id=' . JRequest::getVar('id')) . '" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_UNREGISTER_LABEL', true) . '</a> ';
			}
			else
			{
				
				echo '<p><a class="btn btn-primary" href="' . JRoute::_('index.php?option=com_events&view=team&layout=unregister&id=' . JRequest::getVar('id')) . '" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_UNREGISTER_LABEL', true) . '</a> ';
				
			}
			
			

		}
		else
		{ 
			echo '<p><a class="btn btn-primary" href="' . JRoute::_('index.php?option=com_events&view=team&layout=register&id=' . JRequest::getVar('id')) . '" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_REGISTER_LABEL', true) . '</a> ';
		}
	} ?></p>
</div>