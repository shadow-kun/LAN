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

<form action="<?php echo JRoute::_('index.php?option=com_events&view=teams') ; ?>"
	method="post" name="adminForm" id="team-form">
		<div class="form-horizontal"> <!--class="width-60 fltlft"-->
		
			<div class="row-fluid">
				<div class="details span8" id="#details">
					<fieldset class="adminform">
						<div><?php echo JText::_('COM_EVENTS_TEAM_EDIT_TITLE_LABEL');?><input type="text" id="title" name="title" value="" /><br /></div>
						<div><?php echo JText::_('COM_EVENTS_TEAM_EDIT_DESC_LABEL');?><br /><textarea id="body" name="body" value="" cols="50" rows="10" ></textarea><br /></div>
					</fieldset>
					<div><a class="btn" value="cancel" ><?php echo JText::_('COM_EVENTS_TEAM_SUMMARY_CANCEL_LABEL');?></a>
					<a class="btn btn-primary" onclick="addTeam()" href="javascript:void(0)" ><?php echo JText::_('COM_EVENTS_TEAM_SUMMARY_CREATE_TEAM_LABEL');?></a></div>
					
				</div>
			</div>
		</div>
	<input type="hidden" name="task" value="team" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>