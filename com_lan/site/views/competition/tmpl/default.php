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
	Joomla.submitbutton = function(task)
	{
		var form = document.id(competition-register-form);
		alert(task);
		if (task == 'cancel' ) {
			Joomla.submitform(task, form);
		}
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
		<p><?php echo $params->competition_team; ?></p>
		
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
		
		<?php /* Seperate teams / players competitions from this point onwards */ ?>
		<?php if(JFactory::getUser()->guest) { 
			echo '<p><a href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
			echo JText::_('COM_LAN_COMPETITION_SUMMARY_LOGIN', true) . '</a>';
		} else { 
			if(isset($this->currentPlayer->id))
			{
				echo '<p><button name="selection" class="btn btn-primary" value="unregister_player_competition" >' . JText::_('COM_LAN_COMPETITION_SUMMARY_UNREGISTER_LABEL') . '</button></p>';
		
			}
			else
			{ 
				echo '<p><button name="selection" class="btn btn-primary" value="register_player_competition" >' . JText::_('COM_LAN_COMPETITION_SUMMARY_REGISTER_LABEL') . '</button></p>';
			}
		} ?></p>
		
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
		
		<h3><?php echo JText::_('COM_LAN_COMPETITION_SUBHEADING_PLAYERS_LIST', true) ?></h3>
		<table class="list table table-striped">
			<thead>
				<tr>
					<th width="1%">
						<?php echo JHTML::_('grid.sort', 'COM_LAN_COMPETITION_TABLE_COMPETITION_PLAYERS_ORDER', 'id', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHTML::_('grid.sort', 'COM_LAN_COMPETITION_TABLE_COMPETITION_PLAYERS_PLAYER', 'p.username', $listDirn, $listOrder); ?>
					</th>
					<th width="20%">
						<?php echo JHTML::_('grid.sort', 'COM_LAN_COMPETITION_TABLE_COMPETITION_PLAYERS_STATUS', 'status', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="15">
						<?php //echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach ($this->players as $p => $player) :
					$player->max_ordering = 0;
					$ordering	= ($listOrder == 'id');
				?>
				<tr class="row<?php echo $p % 2; ?>">
					<td class="left">
						<?php echo (int) $p + 1; ?>
					</td>
					<td class="left">
						<?php echo $this->escape($player->username); ?>
					</td>
					<td class="center">
						<?php if(isset(json_decode($player->params)->status)) :
							echo $this->escape(json_decode($player->params)->status); 
						endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<input type="hidden" name="task" value="register" />
		<?php echo JHtml::_( 'form.token' ); ?>
	</div>
</form>