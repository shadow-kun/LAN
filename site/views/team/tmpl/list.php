<h2 class="page-header"><?php echo JText::_('COM_LAN_TEAMS_LIST'); ?></h2>
<h4><a href="?option=com_lan&view=team&layout=create"><?php echo JText::_('COM_LAN_TEAM_CREATE_LINK'); ?></a></h4>
<div class="row-fluid">
	<div class="media well well-small span6">
		<div class="media-body">
			<a class="pull-left" href="?option=com_lan&view=team&layout=team&id=1">
				<img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower(trim($this->profile->email))); ?>?s=50" />
			</a>
			<h4 class="media-heading">
				<a href="?option=com_lan&view=team&layout=team&id=1">Team 1</a>
			</h4>
			<p><strong><?php echo JText::_('COM_LAN_TEAM_CAPTAIN'); ?></strong>: Admin User<br /><p>
			<p>Description Here<br /><br />
			<strong><?php echo JText::_('COM_LAN_TEAM_MEMBERS'); ?></strong>: 
				<ul>
					<li>User 1</li>
					<li>User 2</li>
					<li>Admin User</td>
				</ul>
			</p>
			<h4><a href="?option=com_lan&view=team&layout=edit&id=1"><?php echo JText::_('COM_LAN_TEAM_EDIT_LINK'); ?></a></h4>
		</div>
	</div>
</div>