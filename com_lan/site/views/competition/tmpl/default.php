<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	jimport('joomla.application.component.helper');
	
	//JHtml::addIncludePath(JPATH_COMPONENT.'helpers/html');
	JHtml::stylesheet('com_lan/admin.css', null, true);
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
	
	$params = json_decode($this->item->params);
?>
<script>
	function registerTeam()
	{
		var form = document.id("ompetition-form");
		document.getElementById("task").value = "competitionRegisterTeam";
		Joomla.submitform("competitionRegisterTeam", form);
	}
	
	function unregisterTeam()
	{
		var form = document.id("ompetition-form");
		document.getElementById("task").value = "competitionUnregisterTeam";
		Joomla.submitform("competitionUnregisterTeam", form);
	}
	function registerPlayer()
	{
		var form = document.id("ompetition-form");
		document.getElementById("task").value = "competitionRegisterPlayer";
		Joomla.submitform("competitionRegisterPlayer", form);
	}
	
	function unregisterPlayer()
	{
		var form = document.id("ompetition-form");
		document.getElementById("task").value = "competitionUnregisterPlayer";
		Joomla.submitform("competitionUnregisterPlayer", form);
	}
	
</script>

<form action="<?php echo JRoute::_('index.php?option=com_lan&view=competition&id='.(int) $this->item->id); ?>"
	method="post" name="adminForm" id="competition-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_lan&view=competition&id=' . $this->item->id); ?>"><?php echo $this->escape($this->item->title); ?></a></h2>
					
	<div class="form-horizontal">
		<p><strong><?php echo JText::_('COM_LAN_COMPETITION_SUMMARY_START_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->item->competition_start))); ?></br >
		<strong><?php echo JText::_('COM_LAN_COMPETITION_SUMMARY_END_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->item->competition_end))); ?></p>
		
		<?php if(isset($params->competition_organisers)) : ?>
			<p><strong><?php echo JText::_('COM_LAN_COMPETITION_SUMMARY_ORGANISERS_LABEL', true); ?></strong> - 
				<?php echo $this->escape($params->competition_organisers); ?> </p>
		<?php endif; ?>
		
		<p><strong><?php echo JText::_('COM_LAN_COMPETITION_SUMMARY_TEAM_LABEL', true); ?></strong> - 		
			<?php switch((int) $params->competition_team)
				{
					case 0: 
						echo JText::_('COM_LAN_COMPETITION_FIELD_PARAM_TEAM_OPTION_INDIVIDUAL');
						break;
					case 1:
						echo JText::_('COM_LAN_COMPETITION_FIELD_PARAM_TEAM_OPTION_TEAM');
						break;
				}
			?></p>
		
		<?php if(isset($params->competition_tournament)) : ?>
			<p><strong><?php echo JText::_('COM_LAN_COMPETITION_SUMMARY_TOURNAMENT_LABEL', true); ?></strong> - 
				<?php switch((int) $params->competition_tournament)
					{
						case 0: 
							echo JText::_('COM_LAN_COMPETITION_FIELD_PARAM_TOURNAMENT_OPTION_TOURNAMENT');
							break;
						case 1:
							echo JText::_('COM_LAN_COMPETITION_FIELD_PARAM_TOURNAMENT_OPTION_SINGLE_ELIMINATION');
							break;
						case 2:
							echo JText::_('COM_LAN_COMPETITION_FIELD_PARAM_TOURNAMENT_OPTION_DOUBLE_ELIMINATION');
							break;
						case 3:
							echo JText::_('COM_LAN_COMPETITION_FIELD_PARAM_TOURNAMENT_OPTION_SWISS');
							break;
						case 4:
							echo JText::_('COM_LAN_COMPETITION_FIELD_PARAM_TOURNAMENT_OPTION_ROUND_ROBIN');
							break;
						case 5:
							echo JText::_('COM_LAN_COMPETITION_FIELD_PARAM_TOURNAMENT_OPTION_SUBMISSION');
							break;
					}
				?></p>
		<?php endif; ?>
		
		
			
		
		<div class="row-fluid">
			<div class="span8">
				<?php $tokens = explode('<hr id="system-readmore" />',$this->item->body);
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
		
		<?php /* Seperate teams / players competitions from this point onwards */ ?>
		
		<?php if($params->competition_team == 1)
		{
			echo $this->loadTemplate('teams');
		}
		else
		{
			echo $this->loadTemplate('players');
		} ?>
		<?php echo JHtml::_( 'form.token' ); ?>
	</div>
	<input id="task" type="textbox" name="task" value="" />
</form>