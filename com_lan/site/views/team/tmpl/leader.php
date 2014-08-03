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
	$editor = JFactory::getEditor();
	
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
	
	if($this->currentPlayer->status < 4)
	{
		JError::raiseError(403, JText::_('COM_LAN_ERROR_FOBBIDEN'));
	}
	else
	{
?>
<script>
	Joomla.submitbutton = function(task)
	{
		var form = document.id(team-register-form);
		if (task == 'cancel' ) {
			Joomla.submitform(task, form);
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_lan&view=team&id=' . JRequest::getVar('id')); ?>"
	method="post" name="adminForm" id="team-form" class="form-validate">
		<h2><?php echo JText::_('COM_LAN_TEAM_NEW_LEADER_TITLE_LABEL');?></h2>
		<p><?php echo JText::_('COM_LAN_TEAM_NEW_LEADER_SUMMARY_DESC');?>
		<div class="form-horizontal"> <!--class="width-60 fltlft"-->
		
			<div class="row-fluid">
				<div class="span8">
					<fieldset class="adminform">
						<div>
							<select name="teamLeader">
								<?php foreach ($this->players as $p => $player) :
									$player->max_ordering = 0;
									$ordering	= ($listOrder == 'id');
								?>
									<option 
										<?php if($player->status == 4) : echo 'selected'; endif; ?> value="<?php echo $player->id; ?>"><?php echo $player->username; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</fieldset>
					<div><button name="selection" class="btn" value="cancel"><?php echo JText::_('COM_LAN_TEAM_CANCEL_LABEL');?></button>
					<button name="selection" class="btn btn-primary" value="team_new_leader_confirm"><?php echo JText::_('COM_LAN_TEAM_NEW_LEADER_CONFIRM_LABEL');?></button></div>
				</div>
				
			</div>
		</div>
	<input type="hidden" name="task" value="team" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>
<?php } ?>