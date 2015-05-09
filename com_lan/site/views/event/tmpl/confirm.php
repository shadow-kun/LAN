<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	 
	 //JHtml::addIncludePath(JPATH_COMPONENT.'helpers/html');
	JHtml::stylesheet('com_lan/admin.css', null, true);
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	
	$app = JFactory::getApplication();
	$pathway = $app->getPathway();
	$pathway->addItem(JText::_('COM_LAN_EVENTS_REGISTER_CONFIRM_TITLE', true), JRoute::_('index.php?option=com_lan&view=event&layout=confirm&id=' . $this->item->id));
?>

<script>
	Joomla.submitbutton = function(task)
	{
		var form = document.id(event-register-form);
		if (task == 'cancel' || document.formvalidator.isValid(form)) {
			Joomla.submitform(task, form);
		}
	}
</script>
				
<form action="<?php /*echo JRoute::_('index.php?option=com_lan&view=event&id='.(int) $this->item->id); */?>"
	method="post" name="adminForm" id="event-register-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_lan&view=event&id=' . $this->item->id); ?>"><?php echo $this->escape($this->item->title); ?></a> <strong> - </strong> 
		<a href="<?php echo JRoute::_('index.php?option=com_lan&view=event&layout=confirm&id=' . $this->item->id); ?>"><?php echo JText::_('COM_LAN_EVENTS_REGISTER_CONFIRM_TITLE', true) ?></a></h2>
		
	<div class="row-fluid">
		<p><?php echo JText::_('COM_LAN_EVENT_REGISTRATION_CONFIRM_MSG_1'); ?></p>
		<p><?php echo JText::_('COM_LAN_EVENT_REGISTRATION_CONFIRM_MSG_2'); ?></p>
		</br >
	</div>
				
	<p class="center"><input type="submit" name="cancel" class="btn" value="<?php echo JText::_('COM_LAN_EVENT_REGISTRATION_CONFIRM_FALSE'); ?>" />
		<input type="submit" name="confirm" class="btn btn-primary" value="<?php echo JText::_('COM_LAN_EVENT_REGISTRATION_CONFIRM_TRUE'); ?>" /></p> 
		
	<input type="hidden" name="option" value="com_lan" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="view" value="event" />
    <?php echo JHtml::_('form.token'); ?>
</form>