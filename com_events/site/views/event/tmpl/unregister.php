<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	
?>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=event&id='.(int) $this->event->id); ?>"
	method="post" name="adminForm" id="event-unregister-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=event&id=' . $this->event->id); ?>"><?php echo $this->escape($this->event->title); ?></a> <strong> - </strong> 
		<a href="<?php echo JRoute::_('index.php?option=com_events&view=event&layout=register&id=' . $this->event->id); ?>"><?php echo JText::_('COM_EVENTS_EVENT_UNREGISTER_TITLE', true) ?></a></h2>
		
	<div class="row-fluid" id="details" >
		<p><?php echo JText::_('COM_EVENTS_EVENT_UNREGISTER_MSG', true); ?></p>
	
		<div class="center">
			<p><button class="btn btn-primary" ><?php echo JText::_('COM_EVENTS_EVENT_BUTTON_BACK', true); ?></button>
				<a href="javascript:void(0);" onclick="unregisterEventUser()" class="btn" ><?php echo JText::_('COM_EVENTS_EVENT_BUTTON_UNREGISTER', true); ?></a></p>
		</div>
	</div>
	<input type="hidden" name="option" value="com_events" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="view" value="event" />
	<input type="hidden" id="eventid" name="event" value="<?php echo $this->event->id; ?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>