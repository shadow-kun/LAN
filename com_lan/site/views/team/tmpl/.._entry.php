<div class="media well well-small span6">
	<div class="media-body">
		<h4 class="media-heading"><a href="<?php echo JRoute::_('index.php?option=com_lendr&view=profile&layout=profile&profile_id='.$this->profile->id); ?>"><?php echo $this->profile->name; ?></a></h4>
		<p><strong><?php echo JText::_('COM_LAN_TEAM_CAPTAIN'); ?></strong>: <?php echo $this->teams->team_captain; ?><br />
			<strong><?php echo JText::_('COM_LAN_TEAM_MEMBERS'); ?></strong>: <?php echo $this->profile->team_members; ?>
		</p>
	</div>
</div>