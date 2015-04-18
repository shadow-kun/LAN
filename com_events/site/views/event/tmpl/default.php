<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		LAN
	 * @subpackage	COM_EVENTS
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	jimport('joomla.application.component.helper');
	
	//JHtml::addIncludePath(JPATH_COMPONENT.'helpers/html');
	//JHtml::stylesheet('com_events/admin.css', null, true);
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	
	//$listOrder	= $this->escape($this->state->get('list.ordering'));
	//$listDirn	= $this->escape($this->state->get('list.direction'));
	if($this->event->params->prepay !== '') 
	// Gets global setting if not explicitly set.
	{
		$prepay = intval($this->event->params->prepay);
	} 
	else
	{
		$prepay = intval($this->params->get('prepay'));
	}
	
	if($this->event->params->confirmations_override !== '') 
	// Gets global setting if not explicitly set.
	{
		$confirmations = intval($this->event->params->confirmations_override);
	} 
	else
	{
		$confirmations = intval($this->params->get('confirmations_override'));
	}
	
?>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=event&id='.(int) $this->event->id); ?>"
	method="post" name="adminForm" id="event-form" class="form-validate">
	
	<?php if(!empty($this->event->params->event_image)) : ?>
		<div class="eventpic">
			<img src="<?php echo $this->event->params->event_image; ?>" style="max-width:200px; float:right;" />
		</div>
	<?php endif; ?>
	<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=event&id=' . $this->event->id); ?>"><?php echo $this->escape($this->event->title); ?></a></h2>
		
	<div class="form-horizontal">
		<p><strong><?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_START_TIME_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->event->event_start_time))); ?><br />
			<strong><?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_END_TIME_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->event->event_end_time))); ?></p>
			
		<p>
		<?php if(!empty($this->event->params->venue)) : ?>
			<strong><?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_VENUE', true); ?></strong> - 
			<?php echo $this->escape($this->event->params->location); ?>
			<?php if(!empty($this->event->params->venue)) : 
				echo '<br />';
			endif; ?>
		<?php endif; ?>
		<?php if(!empty($this->event->params->location)) : ?>
			<strong><?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_LOCATION', true); ?></strong> - 
			<?php echo $this->escape($this->event->params->location); ?>
		<?php endif; ?></p>
		
		<p>	<?php if($this->event->params->cost_prepay != $this->event->params->cost_event) :  ?>
			<strong><?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_PREPAY_COST_LABEL', true); ?></strong> - <?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_CURRENTCY_LABEL', true); ?>
			<?php echo $this->escape($this->event->params->cost_prepay); ?><br />
			<?php endif; ?>
			<strong><?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_EVENT_COST_LABEL', true); ?></strong> - <?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_CURRENTCY_LABEL', true); ?>
			<?php echo $this->escape($this->event->params->cost_event); ?></p>
		
		<p><strong><?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_REGISTRATION_OPEN_TIME_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->event->params->registration_open_time))); ?><br />
			<strong><?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_REGISTRATION_CLOSE_TIME_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->event->params->registration_close_time))); ?></p>
			
		<p><strong><?php echo JText::_('COM_EVENTS_EVENT_SUMMARY_REGISTRATION_CONFIRMATION_TIME_LABEL', true); ?></strong> - 
			<?php echo date('g:i A l, jS F Y', strtotime($this->escape($this->event->params->registration_confirmation_time))); ?></p>
			
		<p><strong><?php echo JText::_('COM_EVENTS_EVENT_LIST_PLAYERS'); ?></strong> - <?php echo $this->escape($this->event->players_current); ?> / <?php echo $this->escape($this->event->players_confirmed); ?> / <?php echo $this->escape($this->event->players_max); ?><br />
			<strong><?php echo JText::_('COM_EVENTS_EVENT_LIST_PREPAID'); ?></strong> - <?php echo $this->escape($this->event->players_prepaid); ?> / <?php echo $this->escape($this->event->players_prepay); ?></p>
		
		<!-- Need to have a restrict access cause here -->
		
		<?php if(JFactory::getUser()->guest) { 
			echo '<p><a href="' . JRoute::_('index.php?option=com_users&view=login') . '">';
			echo JText::_('COM_EVENTS_EVENT_SUMMARY_LOGIN', true);
		} else { 
			
			$app = JFactory::getApplication('site');
			$waitlist = $this->event->params->waitlist_override;
			// If player has a status entry
			if(isset($this->currentUser->status))
			{
				echo '<p>';
				// If registrations haven't closed
				if(!((strtotime($this->event->event_end_time) < time()) || ($this->event->params->event_closed == 1) || strtotime($this->event->params->registration_close_time) < time()))
				{
					// Confirmations opened
					if($this->currentUser->status == 1 && $confirmations != -1 && ($confirmations == 1 || (time() >= strtotime($this->event->params->registration_confirmation_time))))
					{
						echo '<a href="' .  JRoute::_('index.php?option=com_events&view=event&layout=confirmation&id=' . $this->event->id) . '" class="btn btn-primary">';
						echo JText::_('COM_EVENTS_EVENT_SUMMARY_CONFIRM', true) . '</a> ';
					}	 
					// Allows pre-payment
					if($prepay > 0 && $this->currentUser->status <= 2) 
					{
						
						echo '<a href="' .  JRoute::_('index.php?option=com_events&view=event&layout=prepay&id=' . $this->event->id) . '" class="btn">';
						echo JText::_('COM_EVENTS_EVENT_SUMMARY_PREPAY', true) . '</a> ';
					}	
					// If not pre-paid, allow un-registering from the event
					if((int) $this->currentUser->status <= 2)
					{
						echo '<a href="' .  JRoute::_('index.php?option=com_events&view=event&layout=unregister&id=' . $this->event->id) . '" class="btn">' .
							JText::_('COM_EVENTS_EVENT_SUMMARY_UNREGISTER', true) . '</a> '; 
					}
				}
			}
			// Registrations are closed and not registered
			else if((strtotime($this->event->event_end_time) < time()) || ($this->event->params->event_closed == 1) || strtotime($this->event->params->registration_close_time) < time())
			{
				echo '<p>' . JText::_('COM_EVENTS_EVENT_SUMMARY_CLOSED', true);
			}
			// Registrations opened though event is full with no wait list
			else if((isset($waitlist) && $waitlist == 0 || (!(isset($waitlist)) && $app->getParams('com_events')->get('waitlist') == 0)) && ($this->event->players_current >= $this->event->players_max))
			{
				echo '<p>' . JText::_('COM_EVENTS_EVENT_SUMMARY_FULL', true);
			}
			// User able register
			else if(time() < strtotime($this->event->params->registration_open_time) )
			{
				echo '<p>' . JText::_('COM_EVENTS_EVENT_SUMMARY_TOO_EARLY', true);
			}
			else
			{
				echo '<p><a href="' .  JRoute::_('index.php?option=com_events&view=event&layout=register&id=' . $this->event->id) . '" class="btn btn-primary">';
				echo JText::_('COM_EVENTS_EVENT_SUMMARY_REGISTER', true) . '</a>';
			}
		} 
		echo ' <a href="' .  JRoute::_('index.php?option=com_events&view=event&layout=attendees&id=' . $this->event->id) . '" class="btn" >';
				echo JText::_('COM_EVENTS_EVENT_SUMMARY_PLAYERS', true) . '</a>';
		?></p>
		
		<div class="row-fluid">
			<div class="span8">
				<?php $tokens = explode('<hr id="system-readmore" />',$this->event->body);
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
	<?php echo JHtml::_( 'form.token' ); ?>
</form>
