<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	jimport('joomla.application.component.helper');
	
	//JHtml::addIncludePath(JPATH_COMPONENT.'helpers/html');
	JHtml::stylesheet('com_lan/admin.css', null, true);
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_lan&view=event&id='.(int) $this->item->id); ?>"
	method="post" name="adminForm" id="event-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_lan&view=event&id=' . $this->item->id); ?>"><?php echo $this->escape($this->item->title); ?></a></h2>
					
	<div class="form-horizontal">
		<p><strong><?php echo JText::_('COM_LAN_EVENT_SUMMARY_START_TIME_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->item->event_start_time))); ?><br />
			<strong><?php echo JText::_('COM_LAN_EVENT_SUMMARY_END_TIME_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->item->event_end_time))); ?></p>
			
			<p>
			<?php if(isset(json_decode($this->item->params)->venue)) : ?>
				<strong><?php echo JText::_('COM_LAN_EVENT_SUMMARY_VENUE', true); ?></strong> - 
				<?php echo $this->escape(json_decode($this->item->params)->location); ?>
				<?php if(isset(json_decode($this->item->params)->venue)) : 
					echo '<br />';
				endif; ?>
			<?php endif; ?>
			<?php if(isset(json_decode($this->item->params)->location)) : ?>
				<strong><?php echo JText::_('COM_LAN_EVENT_SUMMARY_LOCATION', true); ?></strong> - 
				<?php echo $this->escape(json_decode($this->item->params)->location); ?></p>
			<?php endif; ?>
			
		<p><strong><?php echo JText::_('COM_LAN_EVENTS_LIST_PLAYERS'); ?></strong> - <?php echo $this->escape($this->item->players_current); ?> / <?php echo $this->escape($this->item->players_confirmed); ?> / <?php echo $this->escape($this->item->players_max); ?><br />
			<strong><?php echo JText::_('COM_LAN_EVENTS_LIST_PREPAID'); ?></strong> - <?php echo $this->escape($this->item->players_prepaid); ?> / <?php echo $this->escape($this->item->players_prepay); ?></p>
			
		<!-- Need to have a restrict access cause here -->
		
		<?php if(JFactory::getUser()->guest) { 
			echo '<p><a href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
			echo JText::_('COM_LAN_EVENT_SUMMARY_LOGIN', true);
		} else { 
			
			if(isset($this->currentPlayer->status))
			{
				echo '<p><a href="' .  JRoute::_('index.php?option=com_lan&view=event&layout=unregister&id=' . $this->item->id) . '">';
				echo JText::_('COM_LAN_EVENT_SUMMARY_UNREGISTER', true) . '</a> ';
				if($this->item->params->get('confirmations_override') > 0 && $this->currentPlayer->status == 1) 
				{
					echo '<a href="' .  JRoute::_('index.php?option=com_lan&view=event&layout=confirm&id=' . $this->item->id) . '">';
					echo JText::_('COM_LAN_EVENT_SUMMARY_CONFIRM', true) . '</a>';
				}	 
			}
			else
			{
				echo '<p><a href="' .  JRoute::_('index.php?option=com_lan&view=event&layout=register&id=' . $this->item->id) . '">';
				echo JText::_('COM_LAN_EVENT_SUMMARY_REGISTER', true) . '</a>';
			}
		} 
		echo ' <a href="' .  JRoute::_('index.php?option=com_lan&view=event&layout=players&id=' . $this->item->id) . '">';
				echo JText::_('COM_LAN_EVENT_SUMMARY_PLAYERS', true) . '</a>';
		?></p>
		
		<div class="row-fluid">
			<div class="span8">
				<?php $tokens = explode('<hr id="system-readmore" />',$this->item->body);
					if(count($tokens) === 1)
					{
						echo $tokens[0];
					}
					else
					{
						echo $tokens[1];
					}
				?>
			</div>
		</div>
	</div>
</form>