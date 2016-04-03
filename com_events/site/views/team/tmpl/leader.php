<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	
	/**
	 * @package		LAN
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	 
	jimport('joomla.application.component.helper');
	
	
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	?>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=team&id='.(int) $this->team->id); ?>"
	method="post" name="adminForm" id="team-form" class="form-validate">
	
	<?php if((intval($this->team->params->show_title) == 1) || ((strlen($this->team->params->show_title) === 0) && (intval($this->params->get('show_title')) == 1))) : ?> 
		<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=teams'); ?>"><?php echo JText::_('COM_EVENTS_TEAM_SUMMARY_TEAMS_LABEL', true); ?></a> - <a href="<?php echo JRoute::_('index.php?option=com_events&view=team&id=' . $this->team->id); ?>"><?php echo $this->escape($this->team->title); ?></a></h2>
	<?php endif; ?>
	
	<div class="form-horizontal">
		
		<!-- Need to have a restrict access cause here -->
		
		<?php echo EventsHelpersView::load('team','_buttons','phtml'); ?>
		

		<div id="details" >
			<div>
				<p><?php echo JText::_('COM_EVENTS_TEAM_LEADER_TEAM_LEADER_LABEL'); ?> - 
				<select id="teamleader">
					<?php foreach ($this->users as $u => $user) :
						$user->max_ordering = 0;
						$ordering	= ($listOrder == 'id');
					?>
						<option 
							<?php if($user->user == 4) : echo 'selected'; endif; ?> value="<?php echo $user->userid; ?>"><?php echo $user->username; ?></option>
					<?php endforeach; ?>
				</select></p>
			</div>
			<div><a class="btn" href="<?php echo JRoute::_('index.php?option=com_events&view=team&id='.(int) $this->team->id); ?>"><?php echo JText::_('COM_EVENTS_TEAM_SUMMARY_CANCEL_LABEL');?></button>
			<a class="btn btn-primary" onclick="updateOptionTeamLeader()" href="javascript:void(0)"><?php echo JText::_('COM_EVENTS_TEAM_SUMMARY_LEADER_CONFIRM_LABEL');?></a></div>
		</div>

		<input type="hidden" id="teamid" name="team" value="<?php echo $this->team->id; ?>" />
		<input type="hidden" name="task" value="team" />
		<?php echo JHtml::_( 'form.token' ); ?>
	</div>
</form>