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
	$pathway->addItem(JText::_('COM_LAN_EVENTS_UNREGISTER_TITLE', true), JRoute::_('index.php?option=com_lan&view=event&layout=unregister&id=' . $this->item->id));
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
				
<form action="<?php echo JRoute::_('index.php?option=com_events&view=event&id='.(int) $this->event->id); ?>"
	method="post" name="adminForm" id="event-unregister-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_lan&view=event&id=' . $this->item->id); ?>"><?php echo $this->escape($this->item->title); ?></a> <strong> - </strong> 
		<a href="<?php echo JRoute::_('index.php?option=com_lan&view=event&layout=unregister&id=' . $this->item->id); ?>"><?php echo JText::_('COM_LAN_EVENTS_UNREGISTER_TITLE', true) ?></a></h2>
	<div id="details">
		<p><?php echo JText::_('COM_EVENTS_EVENT_UNREGISTER_MSG'); ?></p>
		
		
		<div class="center">
			<p><button class="btn " ><?php echo JText::_('COM_EVENTS_EVENT_BUTTON_BACK', true); ?></button> 
				<a href="javascript:void(0);" onclick="registerEventUser()" class="btn btn-primary" ><?php echo JText::_('COM_EVENTS_EVENT_BUTTON_REGISTER', true); ?></a></p>
		</div>
		
	<input type="hidden" name="option" value="com_events" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="view" value="event" />
	<input type="hidden" id="eventid" name="event" value="<?php echo $this->event->id; ?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>