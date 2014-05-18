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
	
?>

<form action="<?php echo JRoute::_('index.php?option=com_lan&view=event&id='.(int) $this->item->id); ?>"
	method="post" name="adminForm" id="event-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_lan&view=events') ?>"><?php echo JText::_('COM_LAN_EVENTS_TITLE', true) ?></a> <strong> > </strong> 
		<a href="<?php echo JRoute::_('index.php?option=com_lan&view=event&id=' . $this->item->id); ?>"><?php echo $this->escape($this->item->title); ?></a></h2>
					
	<div class="form-horizontal">
		<p><strong><?php echo JText::_('COM_LAN_EVENT_SUMMARY_START_TIME_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->item->event_start_time))); ?><br />
			<strong><?php echo JText::_('COM_LAN_EVENT_SUMMARY_END_TIME_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->item->event_end_time))); ?></p>
			
		<p><strong><?php echo JText::_('COM_LAN_EVENTS_LIST_PLAYERS'); ?></strong> - <?php echo $this->escape($this->item->players_current); ?> / <?php echo $this->escape($this->item->players_max); ?><br />
			<strong><?php echo JText::_('COM_LAN_EVENTS_LIST_PREPAID'); ?></strong> - <?php echo $this->escape($this->item->players_prepaid); ?> / <?php echo $this->escape($this->item->players_prepay); ?></p>
			
		<!-- Need to have a restrict access cause here -->
		<p><a href="<?php echo JRoute::_('index.php?option=com_lan&view=event&layout=register&id=' . $this->item->id); ?>"><?php echo JText::_('COM_LAN_EVENT_SUMMARY_REGISTER', true) ?></a></p>
		
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_LAN_EVENT_TAB_DETAILS', true)); ?>
		
			<div class="row-fluid">
				<div class="span8">
					<?php echo $this->item->body ?>
				</div>
			</div>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'players', JText::_('COM_LAN_EVENT_TAB_PLAYERS', true)); ?>
		
			<div class="row-fluid">
				<div class="span8">
					<!-- Player Listing To Be Inserted Here -->
					<h3><?php echo JText::_('COM_LAN_EVENT_SUBHEADING_PLAYERS_LIST', true) ?></h3>
				</div>
			</div>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	</div>
</form>