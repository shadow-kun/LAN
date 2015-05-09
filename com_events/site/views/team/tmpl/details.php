<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	jimport('joomla.application.component.helper');
	
	//JHtml::_('behavior.tooltip');
	//JHtml::_('behavior.formvalidation');
	/*href="<?php echo JRoute::_('index.php?option=com_events&controller=edit&type=updateteamdetails&view=team&id=' . JRequest::getVar('id')); ?>" */
?>
<form action="<?php echo JRoute::_('index.php?option=com_events&controller=edit&type=updateteamdetails&view=team&id=' . JRequest::getVar('id')) ; ?>"
	method="post" name="adminForm" id="team-form">
		<div class="form-horizontal"> <!--class="width-60 fltlft"-->
		
			<div class="row-fluid">
				<div class="details span8">
					<fieldset class="adminform">
						<div><?php echo JText::_('COM_EVENTS_TEAM_EDIT_TITLE_LABEL');?><input type="text" name="title" value="<?php echo $this->team->title; ?>" /><br /></div>
						<div><?php echo $this->editor->display('body', $this->team->body, '100%', '350', '55', '20', false); ?></div>
					</fieldset>
					<div><button class="btn" value="cancel" ><?php echo JText::_('COM_EVENTS_TEAM_SUMMARY_CANCEL_LABEL');?></button>
					<button class="btn btn-primary" ><?php echo JText::_('COM_EVENTS_TEAM_SUMMARY_UPDATE_TEAM_LABEL');?></button></div>
				</div>
			</div>
		</div>
	<input type="hidden" name="task" value="team" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>
				