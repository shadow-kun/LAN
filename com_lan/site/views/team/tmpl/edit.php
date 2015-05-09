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

<form action="<?php echo JRoute::_('index.php?option=com_lan&view=team&layout=edit&id=' . JRequest::getVar('id')); ?>"
	method="post" name="adminForm" id="team-form" class="form-validate">
		<div class="form-horizontal"> <!--class="width-60 fltlft"-->
		
			<div class="row-fluid">
				<div class="span8">
					<fieldset class="adminform">
						<div><?php echo JText::_('COM_LAN_TEAM_EDIT_TITLE_LABEL');?><input type="text" name="title" value="<?php echo $this->item->title; ?>" /></div>
						<div><?php echo $editor->display('body', $this->item->body, '100%', '350', '55', '20', false); ?></div>
					</fieldset>
					<div><button name="selection" class="btn" value="cancel"><?php echo JText::_('COM_LAN_TEAM_CANCEL_LABEL');?></button>
					<button name="selection" class="btn btn-primary" value="team_edit_details_confirm"><?php echo JText::_('COM_LAN_TEAM_EDIT_SUBMIT_LABEL');?></button></div>
				</div>
				
			</div>
		</div>
	<input type="hidden" name="task" value="team" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>