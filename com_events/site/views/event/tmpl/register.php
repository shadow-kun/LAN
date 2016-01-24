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
	
	<?php if((intval($this->event->params->show_title) == 1) || ((strlen($this->event->params->show_title) === 0) && (intval($this->params->get('show_title')) == 1))) : ?> 
		<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=event&id=' . $this->event->id); ?>"><?php echo $this->escape($this->event->title); ?></a> <strong> - </strong> 
		<a href="<?php echo JRoute::_('index.php?option=com_events&view=event&layout=register&id=' . $this->event->id); ?>"><?php echo JText::_('COM_EVENTS_EVENT_REGISTER_TITLE', true) ?></a></h2>
	<?php endif; ?>
	
	<?php 
		$waitlist = $this->event->params->waitlist_override;
	if(JFactory::getUser()->guest) { 
		echo '<p><a href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
		echo JText::_('COM_EVENTS_EVENT_SUMMARY_LOGIN', true);
	} 
	else if(isset($this->currentUser->status))
	{
		echo '<p>' . JText::_('COM_EVENTS_EVENT_SUMMARY_REGISTERED', true) . '</p>';	
	}
	else if((strtotime($this->event->event_end_time) < time()) || ($this->event->params->event_closed == 1))
	{
		echo '<p>' . JText::_('COM_EVENTS_EVENT_SUMMARY_CLOSED', true) . '</p>';
	}
	else if((isset($waitlist) && $waitlist == 0 || (!(isset($waitlist)) && $app->getParams('com_events')->get('waitlist') == 0)) && ($this->event->players_current >= $this->event->players_max))
	{
		echo '<p>' . JText::_('COM_EVENTS_EVENT_SUMMARY_FULL', true) . '</p>';
	}
	else
	{ ?>
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
	<?php } ?>
	<?php echo JHtml::_('form.token'); ?>
</form>