<h2 class="page-header"><?php echo JText::_('COM_LAN_TEAMS_LIST'); ?></h2>
<div class="row-fluid">
	<!--<table>
		<tr>
			<th>Team</th>
			<th>Team Captain</th>
			<th>Team Members</th>
		</tr>
		<tr>
			<td>Team 1</td>
			<td>Admin User</td>
			<td>
				<ul>
				<li>User 1</li>
				<li>User 2</li>
				<li>Admin User</td>
				</ul>
			</td>
		</tr>
		<tr>
			<td>Team B</td>
			<td>Super User</td>
			<td>
				<ul>
				<li>User 1</li>
				<li>User 3</li>
				<li>Super User</td>
				</ul>
			</td>
		</tr>
	</table> -->
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
		</div>
	</div>
</div>