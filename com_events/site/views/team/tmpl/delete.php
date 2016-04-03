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

<form action="<?php echo JRoute::_('index.php?option=com_events&view=team&id=' . $this->team->id); ?>"
	method="post" name="adminForm" id="team-delete-form" class="form-validate">
	<div id="details" >
		<div>
			<p><?php echo JText::_('COM_EVENTS_TEAM_DELETE_DETAILS_LABEL'); ?></p>
		</div>
		<div><a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_events&view=team&id=' . JRequest::getVar('id')); ?>"><?php echo JText::_('COM_EVENTS_TEAM_SUMMARY_CANCEL_LABEL');?></a>
			<a class="btn" name="delete" href="<?php echo JRoute::_('index.php?option=com_events&view=team&layout=delete&id=' . JRequest::getVar('id') . '&action=confirm'); ?>"><?php echo JText::_('COM_EVENTS_TEAM_SUMMARY_DELETE_LABEL');?></a></div>
	</div>
</form>