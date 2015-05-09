<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	MOD_EVENTS_event_summary
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
?>

<!-- If next LAN is present, show details otherwise show default no information msg -->
<?php if(isset($event->title)) 
	{ ?>
	<p><strong><?php echo $event->title; ?></strong></p>
	<?php if(!empty(json_decode($event->params)->display_image)) : ?>
	<p><img src="<?php echo json_decode($event->params)->display_image; ?>" style="max-width: 100%;"/></p>
	<?php endif; ?>
	<p><strong><?php echo JText::_('MOD_EVENTS_EVENT_START_TIME_LABEL', true); ?>:</strong> 
		<?php echo date('g:i a, d M', strtotime($event->startTime));?></br >
		<strong><?php echo JText::_('MOD_EVENTS_EVENT_END_TIME_LABEL', true); ?>:</strong> 
		<?php echo date('g:i a, d M', strtotime($event->endTime));?></p>
	
	<?php if(isset(json_decode($event->params)->location)) : ?>
		<p><strong><?php echo JText::_('MOD_EVENTS_EVENT_LOCATION_LABEL', true); ?>:</strong> <?php echo json_decode($event->params)->location; ?></p>
	<?php endif; ?>
	<p><strong><?php echo JText::_('MOD_EVENTS_EVENT_REGISTRATION_LABEL', true); ?>:</strong> <?php echo $event->playersCurrent . ' / ' . $event->playersMax ?></p>

	<?php if(JFactory::getUser()->guest) { 
			echo '<p><a href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
			echo JText::_('MOD_EVENTS_EVENT_LOGIN_LABEL', true) . ' | ' ;
		} else { 
			
			if(isset($player->status))
			{
				if(json_decode($event->params)->confirmations_override > 0 && $player->status == 1) 
				{
					echo '<a href="' .  JRoute::_('index.php?option=com_events&view=event&layout=confirm&id=' . $event->id) . '">';
					echo JText::_('MOD_EVENTS_EVENT_CONFIRM_LABEL', true) . '</a> | ';
				}	 
				elseif((json_decode($event->params)->prepay != 0 || !(json_decode($event->params)->prepay == 0 && json_decode($event->params)->prepay != null)) && ($player->status < 3))
				{
					echo '<p><a href="' .  JRoute::_('index.php?option=com_events&view=event&layout=prepay&id=' . $event->id) . '">';
					echo JText::_('MOD_EVENTS_EVENT_PREPAY_LABEL', true) . '</a> | ';
				}
				elseif($player->status < 3)
				{
					echo '<p><a href="' .  JRoute::_('index.php?option=com_events&view=event&layout=unregister&id=' . $event->id) . '">';
					echo JText::_('MOD_EVENTS_EVENT_UNREGISTER_LABEL', true) . '</a> | ';
				}
			}
			else
			{
				echo '<p><a href="' .  JRoute::_('index.php?option=com_events&view=event&layout=register&id=' . $event->id) . '">';
				echo JText::_('MOD_EVENTS_EVENT_REGISTER_LABEL', true) . '</a> | ';
			}
		} 
		
		echo '<a href="' .  JRoute::_('index.php?option=com_events&view=event&id=' . $event->id) . '">';
		echo JText::_('MOD_EVENTS_EVENT_INFORMATION_LABEL', true) . '</a>';
	?></p>
	
<?php } else { ?>
	<p><?php echo JText::_('MOD_EVENTS_EVENT_UNAVALIABLE_LABEL', true); ?></p>
<?php } ?>	