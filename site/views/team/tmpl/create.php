<h2 class="page-header"><a href="?option=com_lan&view=team"><?php echo JText::_('COM_LAN_TEAM_TITLE'); ?></a> > <?php echo JText::_('COM_LAN_TEAM_CREATE_TITLE'); ?></h2>

<div class="media-body">
	<div class="row-fluid">
		<form class="form-validate" method="post" id="lan_team_create" name="lan_team_create">
			<div class="media well well-small span6" name="lan_team_edit_basics">
				<fieldset> 
					<dl>
						<dt><?php echo JText::_('COM_LAN_TEAM_NAME'); ?></dt>
						<dd><input type="text" name="lan_team_name" id="lan_team_name" /></dd>
			
						<dt><?php echo JText::_('COM_LAN_TEAM_CAPTAIN'); ?></dt>
						<dd><input type="text" name="lan_team_captain" id="lan_team_captain" readonly="true" value="Admin User"></input></dd>
			
						<dt><?php echo JText::_('COM_LAN_TEAM_FORM_CREATE_LBL_ABOUT'); ?></dt>
						<dd><textarea name="lan_team_about" id="lan_team_about" rows="4" cols="50"></textarea></dd>
					</dl>
				</fieldset>
				
				<input type="submit" value="<?php echo JText::_('COM_LAN_TEAM_CREATE_SUBMIT'); ?>">
			</div>
		</form>
	</div>
</div>
		