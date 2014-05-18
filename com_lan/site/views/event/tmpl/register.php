<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	 
?>

<form action="<?php echo JRoute::_('index.php?option=com_lan&view=event&layout=register&id='.(int) $this->item->id); ?>"
	method="post" name="adminForm" id="event-register-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_lan&view=events') ?>"><?php echo JText::_('COM_LAN_EVENTS_TITLE', true) ?></a> <strong> > </strong> 
		<a href="<?php echo JRoute::_('index.php?option=com_lan&view=event&id=' . $this->item->id); ?>"><?php echo $this->escape($this->item->title); ?></a> <strong> > </strong> 
		<a href="<?php echo JRoute::_('index.php?option=com_lan&view=event&layout=register&id=' . $this->item->id); ?>"><?php echo JText::_('COM_LAN_EVENTS_REGISTER_TITLE', true) ?></a></h2>
	
	
</form>