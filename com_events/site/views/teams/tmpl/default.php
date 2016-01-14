<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/

	JHtml::_('behavior.tooltip');
	
	//$listOrder	= $this->escape($this->state->get('list.ordering'));
	//$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=teams');?>" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<?php if(JFactory::getUser()->guest) { 
			echo '<div><a href="' . JRoute::_('index.php?option=com_users&view=login') . '" class="btn btn-primary">' . 
				JText::_('COM_EVENTS_EVENT_SUMMARY_LOGIN', true) . '</a></div>';
		} else { 
			echo '<div><a name="newTeam" class="btn btn-primary" href="' . JRoute::_('index.php?option=com_events&view=teams&layout=add', false) . '" ">' .
				JText::_('COM_EVENTS_TEAMS_SUMMARY_NEW_LABEL') . '</a></div>';
		} ?>
		<div class="clr"></div>
		<?php foreach ($this->teams as $t => $team) :
				$team->max_ordering = 0;
				$ordering	= ($listOrder == 'a.ordering'); 
				$canCreate	= $this->user->authorise('core.create',		'com_events.category.' . $team->category_id);
				$canEdit	= $this->user->authorise('core.edit',			'com_events.team.' . $team->id);
				$canCheckin = $this->user->authorise('core.manage',		'com_checkin') || $team->checked_out == $this->user->get('id') || $team-> checked_out == 0;
				$canChange	= $this->user->authorise('core.edit.state',	'com_events.team.' . $team->id) && $canCheckin;
			?>
			<div class="media well well-small">
				<div class="media-body">
					<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=team&id=' . $team->id); ?>"><?php echo $this->escape($team->title); ?></a></h2>
					<?php 
						$tokens = explode('<hr id="system-readmore" />',$team->body);
						echo $tokens[0];
					?>
				</div>
			</div>
			<div class="clr"></div>
		<?php endforeach; ?>
	</div>
	<input type="hidden" name="task" value="team" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>