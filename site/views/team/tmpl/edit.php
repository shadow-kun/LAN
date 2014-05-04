<h2 class="page-header"><a href="?option=com_lan&view=team"><?php echo JText::_('COM_LAN_TEAM_TITLE'); ?></a> > 
	<img src="http://www.gravatar.com/avatar/<?php echo md5(strtolower(trim($this->profile->email))); ?>?s=30" /> <?php echo JText::_('COM_LAN_TEAM_EDIT_HEADER'); ?> Team 1</h2>

<form class="form-validate" method="post" id="lan_team_create" name="lan_team_edit">
	<div class="media-body">
		<div class="row-fluid">
			<div class="media well well-small span6" name="lan_team_edit_basics">
				<h3><?php echo JText::_('COM_LAN_TEAM_EDIT_HEADING_BASICS'); ?></h3>
				<fieldset> 
					<dl>
					<dt><?php echo JText::_('COM_LAN_TEAM_NAME'); ?></dt>
					<dd><input type="text" name="lan_team_name" id="lan_team_name" value="Team 1"/></dd>
			
					<dt><?php echo JText::_('COM_LAN_TEAM_CAPTAIN'); ?></dt>
					<dd><select name="lan_team_captain" id="lan_team_captain" required="true">
						<option value="1" selected="selected">Admin User</option>
						<option value="2">User 1</option>
						<option value="3">User 2</option>
					</select></dd>
			
					<dt><?php echo JText::_('COM_LAN_TEAM_FORM_CREATE_LBL_ABOUT'); ?></dt>
					<dd><textarea name="lan_team_about" id="lan_team_about" rows="4" cols="50">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sit amet luctus quam. Phasellus ac libero risus. Fusce interdum, ante ac pretium suscipit, mi tellus aliquam massa, sed fermentum velit risus ac orci. Cras rutrum sapien nibh, eget consectetur tortor molestie non. Morbi fermentum pellentesque velit sed pellentesque. 
						</textarea></dd>
					</dl>
				</fieldset>
			</div>
			<div class="media well well-small span6" name="lan_team_edit_members">
				<h3><?php echo JText::_('COM_LAN_TEAM_EDIT_HEADING_MEMBERS'); ?></h3>
				<dl>
					<dt>User 1</dt>
					<dd><select name="lan_team_permissions_user_2" required="true">
						<option value="-1">Remove from Team</option>
						<option value="0" selected="selected">(No Extra Permissions)</option>
						<option value="1">Approve Team Applications</option>
						<option value="2">Modify Team Page</option>
					</select></dd>
					<dt>User 2</dt>
					<dd><select name="lan_team_permissions_user_3" required="true">
						<option value="-1">Remove from Team</option>
						<option value="0" selected="selected">(No Extra Permissions)</option>
						<option value="1">Approve Team Applications</option>
						<option value="2">Modify Team Page</option>
					</select></dd>
				</dl>
				<h4><?php echo JText::_('COM_LAN_TEAM_EDIT_MEMBERS_APPROVALS'); ?>
				<dl>
					<dt>User 4</dt>	
					<dd><select name="lan_team_permissions_user_6" required="true">
						<option value="0" selected="selected">Decide Later</option>
						<option value="1">Approve Application</option>
						<option value="2">Decline Application</option>
					</select></dd>
				</dl>
				
				<p><a href=""><?php echo JText::_('COM_LAN_TEAM_EDIT_MEMBERS_ADD'); ?></a></p>
			</div>
			<div class="media well well-small span6">
				<h3><?php echo JText::_('COM_LAN_TEAM_COMPETITIONS'); ?></h3>
				<ul>
					<li><a href="?option=com_lan&view=competition&layout=competition&id=1">League of Legends - 5 vs. 5</a> <a href="">(<?php echo JText::_('COM_LAN_COMPETITION_ENTERING_WITHDRAW_TEAM_SHORT'); ?>)</a> </li>
				<ul>
		</div>
		</div>
	</div>
<input type="submit" value="<?php echo JText::_('COM_LAN_TEAM_EDIT_SUBMIT'); ?>">
</form>
	