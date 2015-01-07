<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	 
	jimport('joomla.application.component.helper');
	
	
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	?>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=team&id='.(int) $this->team->id); ?>"
	method="post" name="adminForm" id="team-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=teams'); ?>"><?php echo JText::_('COM_EVENTS_TEAM_SUMMARY_TEAMS_LABEL', true); ?></a> - <a href="<?php echo JRoute::_('index.php?option=com_events&view=team&id=' . $this->team->id); ?>"><?php echo $this->escape($this->team->title); ?></a></h2>
	<div class="form-horizontal">
		
		<!-- Need to have a restrict access cause here -->
		
		<?php echo EventsHelpersView::load('team','_buttons','phtml'); ?>
		
		<div class="row-fluid">
			<div class="span12">
				<?php $tokens = explode('<hr id="system-readmore" />',$this->team->body);
					if(count($tokens) === 1)
					{
						echo $tokens[0];
					}
					else
					{
						echo $tokens[1];
					}
				?>
			</div>
		</div>
		
		<?php echo EventsHelpersView::load('team','_players','phtml'); ?>
		
		<input type="hidden" id="teamid" name="team" value="<?php echo $this->team->id; ?>" />
		<input type="hidden" name="task" value="team" />
		<?php echo JHtml::_( 'form.token' ); ?>
	</div>
</form>