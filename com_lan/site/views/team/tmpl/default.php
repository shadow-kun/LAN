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
?>
<script>
	Joomla.submitbutton = function(task)
	{
		var form = document.id(team-register-form);
		alert(task);
		if (task == 'cancel' ) {
			Joomla.submitform(task, form);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_lan&view=team&id='.(int) $this->item->id); ?>"
	method="post" name="adminForm" id="team-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_lan&view=team&id=' . $this->item->id); ?>"><?php echo $this->escape($this->item->title); ?></a></h2>
					
	<div class="form-horizontal">
		
		<!-- Need to have a restrict access cause here -->
		
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
		
		<?php if(JFactory::getUser()->guest) { 
			echo '<p><a href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
			echo JText::_('COM_LAN_TEAM_SUMMARY_LOGIN', true) . '</a>';
		} else { 
			if(isset($this->currentPlayer->id))
			{
				echo '<p><button name="selection" class="btn btn-primary" value="unregister_player_team" >' . JText::_('COM_LAN_TEAM_SUMMARY_UNREGISTER_LABEL') . '</button></p>';
		
			}
			else
			{ 
				echo '<p><button name="selection" class="btn btn-primary" value="register_player_team" >' . JText::_('COM_LAN_TEAM_SUMMARY_REGISTER_LABEL') . '</button></p>';
			}
		} ?></p>
		
		<h3><?php echo JText::_('COM_LAN_TEAM_SUBHEADING_PLAYERS_LIST', true) ?></h3>
		<table class="list table table-striped">
			<thead>
				<tr>
					<th width="1%">
						<?php echo JHTML::_('grid.sort', 'COM_LAN_TEAM_TABLE_TEAM_PLAYERS_ORDER', 'id', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHTML::_('grid.sort', 'COM_LAN_TEAM_TABLE_TEAM_PLAYERS_PLAYER', 'p.username', $listDirn, $listOrder); ?>
					</th>
					<th width="15%">
						<?php echo JHTML::_('grid.sort', 'COM_LAN_TEAM_TABLE_TEAM_PLAYERS_STATUS', 'status', $listDirn, $listOrder); ?>
					</th>
					<?php if(isset($this->currentPlayer->status) && $this->currentPlayer->status >= 2) : ?>
						<th width="30%">
							<?php echo JText::_('COM_LAN_TEAM_TABLE_TEAM_PLAYERS_MANAGEMENT'); ?>
						</th>
					<?php endif; ?>
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
						<td class="left">
							<?php switch((int) $player->status)
								{
									case 0:
										echo JText::_('COM_LAN_TEAM_FIELD_STATUS_OPTION_APPLYING');
										break;
									case 1:
										echo JText::_('COM_LAN_TEAM_FIELD_STATUS_OPTION_MEMBER');
										break;
									case 2:
										echo JText::_('COM_LAN_TEAM_FIELD_STATUS_OPTION_MODERATOR');
										break;
									case 4:
										echo JText::_('COM_LAN_TEAM_FIELD_STATUS_OPTION_LEADER');
										break;
								} ?>
						</td>
						<?php if(isset($this->currentPlayer->status) && $this->currentPlayer->status >= 2) : ?>
							<td class="left">
								<?php if($player->status == 0) :
									echo '<button name="selection" class="btn" value="team_status_reject#' . $player->id . '" >' . JText::_('COM_LAN_TEAM_STATUS_REJECT_LABEL') . '</button> 
										  <button name="selection" class="btn btn-primary" value="team_status_approve#' . $player->id . '" >' . JText::_('COM_LAN_TEAM_STATUS_APPROVE_LABEL') . '</button>';
								elseif ($player->status == 1) :
									echo '<button name="selection" class="btn" value="team_status_remove#' . $player->id . '" >' . JText::_('COM_LAN_TEAM_STATUS_REMOVE_LABEL') . '</button>';
									if($this->currentPlayer->status == 4) :
										echo '<button name="selection" class="btn" value="team_status_moderator#' . $player->id . '" >' . JText::_('COM_LAN_TEAM_STATUS_MODERATOR_LABEL') . '</button>';
									endif;
								elseif ($player->status == 2 && $this->currentPlayer->status == 4) :
									echo '<button name="selection" class="btn" value="team_status_remove#' . $player->id . '" >' . JText::_('COM_LAN_TEAM_STATUS_REMOVE_LABEL') . '</button>';
									echo '<button name="selection" class="btn" value="team_status_member#' . $player->id . '" >' . JText::_('COM_LAN_TEAM_STATUS_MEMBER_LABEL') . '</button>';
								endif; ?>
							</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<input type="hidden" name="task" value="team" />
		<?php echo JHtml::_( 'form.token' ); ?>
	</div>
</form>