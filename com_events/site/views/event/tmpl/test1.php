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
	
	<?php echo $this->canDo; ?>
	<input type="hidden" name="option" value="com_events" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="view" value="event" />
	<input type="hidden" id="eventid" name="event" value="<?php echo $this->event->id; ?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>