<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	 
	 //JHtml::addIncludePath(JPATH_COMPONENT.'helpers/html');
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	
	$app = JFactory::getApplication();
	$pathway = $app->getPathway();
	$pathway->addItem(JText::_('COM_EVENTS_EVENT_SUMMARY_PREPAY', true), JRoute::_('index.php?option=com_events&view=event&layout=prepay&id=' . $this->item->id));
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
				

	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=event&id=' . $this->event->id); ?>"><?php echo $this->escape($this->event->title); ?></a> <strong> - </strong> 
		<a href="<?php echo JRoute::_('index.php?option=com_events&view=event&layout=prepay&id=' . $this->event->id); ?>"><?php echo JText::_('COM_EVENTS_EVENT_PREPAY_TITLE', true) ?></a></h2>
	
		
		<?php echo EventsHelpersView::load('event','_prepay','phtml'); ?>
		
	<input type="hidden" name="option" value="com_events" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="view" value="event" />
	<input type="hidden" id="eventid" name="event" value="<?php echo $this->event->id; ?>" />
    <?php echo JHtml::_('form.token'); ?>
