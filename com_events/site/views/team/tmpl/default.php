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
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=team&id=' . $this->team->id); ?>"><?php echo $this->escape($this->team->title); ?></a></h2>
					
	<div class="form-horizontal">
		
		<!-- Need to have a restrict access cause here -->
		
		
		<?php if(JFactory::getUser()->guest) { 
			echo '<p><a href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
			echo JText::_('COM_EVENTS_TEAM_SUMMARY_LOGIN', true) . '</a>';
		} else { 
			if(isset($this->currentUser->id))
			{
				if($this->currentUser->status == 4)
				{
					echo '<p><button name="selection" class="btn btn-primary" value="team_edit_details" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_EDIT_TEAM_LABEL', true) . '</button> ';
					echo '<button name="selection" class="btn" value="team_edit_leader" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_EDIT_LEADER_LABEL', true) . '</button> ';
					echo '<button name="selection" class="btn" value="team_delete" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_DELETE_LABEL', true) . '</button></p>';
				}
				elseif($this->currentUser->status >= 2)
				{
					echo '<p><button name="selection" class="btn btn-primary" value="team_edit_details" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_EDIT_TEAM_LABEL', true) . '</button> ';
					echo '<button name="selection" class="btn" value="unregister_player_team" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_UNREGISTER_LABEL', true) . '</button></p>';
				}
				else
				{
					echo '<p><button name="selection" class="btn btn-primary" value="unregister_player_team" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_UNREGISTER_LABEL', true) . '</button></p>';
				}
				
				
		
			}
			else
			{ 
				echo '<p><button name="selection" class="btn btn-primary" value="register_player_team" >' . JText::_('COM_EVENTS_TEAM_SUMMARY_REGISTER_LABEL', true) . '</button></p>';
			}
		} ?></p>
		
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
		<h3><?php echo JText::_('COM_EVENTS_TEAM_SUBHEADING_USERS_LIST', true) ?></h3>
		<table class="list table table-striped">
			<thead>
				<tr>
					<th width="1%">
						<?php echo JHTML::_('grid.sort', 'COM_EVENTS_TEAM_TABLE_TEAM_USERS_ORDER', 'id', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHTML::_('grid.sort', 'COM_EVENTS_TEAM_TABLE_TEAM_USERS_USER', 'p.username', $listDirn, $listOrder); ?>
					</th>
					<th width="15%">
						<?php echo JHTML::_('grid.sort', 'COM_EVENTS_TEAM_TABLE_TEAM_USERS_STATUS', 'status', $listDirn, $listOrder); ?>
					</th>
					<?php if(isset($this->currentUser->status) && $this->currentUser->status >= 2) : ?>
						<th width="30%">
							<?php echo JText::_('COM_EVENTS_TEAM_TABLE_TEAM_USERS_MANAGEMENT'); ?>
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
				<?php foreach ($this->users as $u => $user) :
					$user->max_ordering = 0;
					$ordering	= ($listOrder == 'id');
				?>
					<tr class="row<?php echo $p % 2; ?>">
						<td class="left">
							<?php echo (int) $p + 1; ?>
						</td>
						<td class="left">
							<?php echo $this->escape($user->username); ?>
						</td>
						<td class="left">
							<?php switch((int) $user->status)
								{
									case 0:
										echo JText::_('COM_EVENTS_TEAM_FIELD_STATUS_OPTION_APPLYING');
										break;
									case 1:
										echo JText::_('COM_EVENTS_TEAM_FIELD_STATUS_OPTION_MEMBER');
										break;
									case 2:
										echo JText::_('COM_EVENTS_TEAM_FIELD_STATUS_OPTION_MODERATOR');
										break;
									case 4:
										echo JText::_('COM_EVENTS_TEAM_FIELD_STATUS_OPTION_LEADER');
										break;
								} ?>
						</td>
						<?php if(isset($this->currentUser->status) && $this->currentUser->status >= 2) : ?>
							<td class="left">
								<?php if($user->status == 0) :
									echo '<button name="selection" class="btn" value="team_status_reject#' . $user->id . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_REJECT_LABEL') . '</button> 
										  <button name="selection" class="btn btn-primary" value="team_status_approve#' . $user->id . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_APPROVE_LABEL') . '</button>';
								elseif ($user->status == 1) :
									echo '<button name="selection" class="btn" value="team_status_remove#' . $user->id . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_REMOVE_LABEL') . '</button>';
									if($this->currentUser->status == 4) :
										echo '<button name="selection" class="btn" value="team_status_moderator#' . $user->id . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_MODERATOR_LABEL') . '</button>';
									endif;
								elseif ($user->status == 2 && $this->currentUser->status == 4) :
									echo '<button name="selection" class="btn" value="team_status_remove#' . $user->id . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_REMOVE_LABEL') . '</button>';
									echo '<button name="selection" class="btn" value="team_status_member#' . $user->id . '" >' . JText::_('COM_EVENTS_TEAM_STATUS_MEMBER_LABEL') . '</button>';
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