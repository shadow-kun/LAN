<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	jimport('joomla.application.component.helper');
	
	?>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=competition&id='.(int) $this->competition->id); ?>"
	method="post" name="adminForm" id="competition-form" class="form-validate">
	
	<?php if((intval($this->competition->params->show_title) == 1) || ((strlen($this->competition->params->show_title) === 0) && (intval($this->params->get('show_title')) == 1))) : ?> 
		<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=competitions'); ?>" ><?php echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_COMPETITIONS'); ?></a> - <a href="<?php echo JRoute::_('index.php?option=com_events&view=competition&id=' . $this->competition->id); ?>"><?php echo $this->escape($this->competition->title); ?></a></h2>
	<?php endif; ?>
					
	<div class="form-horizontal">
		<p><strong><?php echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_START_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->competition->competition_start))); ?></br >
		<strong><?php echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_END_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->competition->competition_end))); ?></p>
		
		<?php if(!empty($this->competition->event)) : ?>
			<p><strong><?php echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_EVENT_LABEL', true); ?></strong> - 
				<?php echo $this->escape($this->competition->event); ?> </p>
		<?php endif; ?>
		
		<?php if(!empty($this->competition->params->competition_organisers)) : ?>
			<p><strong><?php echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_ORGANISERS_LABEL', true); ?></strong> - 
				<?php echo $this->escape($this->competition->params->competition_organisers); ?> </p>
		<?php endif; ?>
		
		<?php /* ****** Redo into plugins ****** */ ?>
		<p><strong><?php echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_TYPE_LABEL', true); ?></strong> - 		
			<?php switch((int) $this->competition->params->competition_team)
				{
					case 0: 
						echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_TYPE_OPTION_INDIVIDUAL');
						break;
					case 1:
						echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_TYPE_OPTION_TEAM');
						break;
				}
			?></p>
		
		<?php /* ****** Redo into plugins ****** */ ?>
		<?php if(isset($this->competition->params->competition_tournament)) : ?>
			<p><strong><?php echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_TOURNAMENT_LABEL', true); ?></strong> - 
				<?php switch((int) $this->competition->params->competition_tournament)
					{
						case 0: 
							echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_TOURNAMENT_OPTION_TOURNAMENT');
							break;
						case 1:
							echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_TOURNAMENT_OPTION_SINGLE_ELIMINATION');
							break;
						case 2:
							echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_TOURNAMENT_OPTION_DOUBLE_ELIMINATION');
							break;
						case 3:
							echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_TOURNAMENT_OPTION_SWISS');
							break;
						case 4:
							echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_TOURNAMENT_OPTION_ROUND_ROBIN');
							break;
						case 5:
							echo JText::_('COM_EVENTS_COMPETITION_SUMMARY_TOURNAMENT_OPTION_SUBMISSION');
							break;
					}
				?></p>
		<?php endif; ?>
		
		<?php if($this->competition->params->competition_team == 0) : ?>
			<p><strong><?php echo JText::_('COM_EVENTS_COMPETITIONS_CURRENT_USERS_LABEL'); ?></strong> - 
			<?php echo $this->model->getCompetitionPlayers($this->competition->id); ?>
			<?php if(isset($this->competition->params->competition_limit)) : 
				echo ' / ' . (int) $this->competition->params->competition_limit;
			endif; ?>
			</p>
		<?php else : ?>
			<p><strong><?php echo JText::_('COM_EVENTS_COMPETITIONS_CURRENT_TEAMS_LABEL'); ?></strong> - 
			<?php echo $this->model->getCompetitionTeams($this->competition->id); ?>
			<?php if(isset($this->competition->params->competition_limit)) : 
				echo ' / ' . (int) $this->competition->params->competition_limit;
			endif; ?>
			</p>
		<?php endif; ?>
		
		<?php echo EventsHelpersView::load('competition','_buttons','phtml'); ?>
			
		
		<?php echo EventsHelpersView::load('competition','_details','phtml'); ?>
			
		
		<?php /* Seperate teams / players competitions from this point onwards */ ?>
		
		<?php /*if($params->competition_team == 1)
		{
			echo $this->loadTemplate('teams');
		}
		else
		{
			echo $this->loadTemplate('players');
		} */?>
		<?php echo JHtml::_( 'form.token' ); ?>
	</div>
	
	<input type="hidden" id="competitionid" name="competition" value="<?php echo $this->competition->id; ?>" />
	<input id="task" type="hidden" name="task" value="" />
</form>