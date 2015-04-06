<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	
	
	//$app = JFactory::getApplication();
	//$pathway = $app->getPathway();
	//$pathway->addItem(JText::_('COM_EVENTS_EVENT_REGISTER_TITLE', true), JRoute::_('index.php?option=com_events&view=event&layout=register&id=' . $this->event->id));
?>

				
<form action="<?php echo JRoute::_('index.php?option=com_events&view=event&id='.(int) $this->event->id); ?>"
	method="post" name="adminForm" id="event-register-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=event&id=' . $this->event->id); ?>"><?php echo $this->escape($this->event->title); ?></a> <strong> - </strong> 
		<a href="<?php echo JRoute::_('index.php?option=com_events&view=event&layout=register&id=' . $this->event->id); ?>"><?php echo JText::_('COM_EVENTS_EVENT_REGISTER_TITLE', true) ?></a></h2>
	
	
	<div id="details">
		<?php echo EventsHelpersView::load('event','_terms','phtml'); ?>
	
			<div class="center">
				<p><button class="btn " ><?php echo JText::_('COM_EVENTS_EVENT_BUTTON_BACK', true); ?></button>
					<a class="btn btn-primary" onclick="registerEventUser()" href="javascript:void(0);" ><?php echo JText::_('COM_EVENTS_EVENT_BUTTON_REGISTER', true); ?></a></p>
			</div>
	</div>
	<input type="hidden" name="option" value="com_events" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="view" value="event" />
	<input type="hidden" id="eventid" name="event" value="<?php echo $this->event->id; ?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>