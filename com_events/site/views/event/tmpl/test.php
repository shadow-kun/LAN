<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	
?>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=event&id='.(int) $this->event->id); ?>"
	method="post" name="adminForm" id="event-confirmation-form" class="form-validate">
	<?php var_dump($this->canDo); ?>
	<?php echo $this->canDo; ?>
	
	<?php echo '<p>Core.View - ' . JFactory::getUser()->authorise('core.view', 'com_events') . '</p>'; ?>
	
	
	
	
	
	
	<?php echo '<p>Core.View - ' . JFactory::getUser()->authorise('core.view', 'com_events.events.' . $id) . '</p>'; ?>
	
	<p>Access Levels</p>
	<?php
		echo '<p>' . var_dump(JAccess::getAuthorisedViewLevels(JFactory::getUser()->id)) . '</p>';
		echo '<p>' . $this->event->access . '</p>';
		
		if(in_array($this->event->access, JAccess::getAuthorisedViewLevels(JFactory::getUser()->id)))
		{
			echo '<p>YAY</p>';
		}
		else
		{
			echo '<p>Bad</p>';
		}
		
	?>
	<input type="hidden" name="option" value="com_events" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="view" value="event" />
	<input type="hidden" id="eventid" name="event" value="<?php echo $this->event->id; ?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>