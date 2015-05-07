<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/

	if($this->event->params->prepay !== '') 
	// Gets global setting if not explicitly set.
	{
		$prepay = intval($this->event->params->prepay);
	} 
	else
	{
		$prepay = intval($this->params->get('prepay'));
	}
	
	JHtml::_('behavior.tooltip');
	
	$user		= JFactory::getUser();
	//$listOrder	= $this->escape($this->state->get('list.ordering'));
	//$listDirn	= $this->escape($this->state->get('list.direction'));
	
	$app = JFactory::getApplication();
	$pathway = $app->getPathway();
	$pathway->addItem(JText::_('COM_EVENTS_EVENT_ATTENDEES_TITLE', true), JRoute::_('index.php?option=com_events&view=event&layout=players&id=' . $this->event->id));
?>	
<form action="<?php /*echo JRoute::_('index.php?option=com_events&view=event&id='.(int) $this->event->id); */?>"
	method="post" name="adminForm" id="event-players-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=event&id=' . $this->event->id); ?>"><?php echo $this->escape($this->event->title); ?></a> <strong> - </strong> 
		<a href="<?php echo JRoute::_('index.php?option=com_events&view=event&layout=attendees&id=' . $this->event->id); ?>"><?php echo JText::_('COM_EVENTS_EVENT_ATTENDEES_TITLE', true) ?></a></h2>
		
	<div class="row-fluid">
		<div class="span12">
			<!-- Player Listing To Be Inserted Here -->
			<h3><?php echo JText::_('COM_EVENTS_EVENT_SUBHEADING_ATTENDEES_LIST', true) ?></h3>
			<table class="list table table-striped">
				<thead>
					<tr>
						<th width="25%">
							<?php echo JHTML::_('grid.sort', 'COM_EVENTS_EVENT_TABLE_ATTENDEES_ORDER', 'id', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo JHTML::_('grid.sort', 'COM_EVENTS_EVENT_TABLE_ATTENDEES_ATTENDEE', 'p.username', $listDirn, $listOrder); ?>
						</th>
						<th width="15%">
							<?php echo JHTML::_('grid.sort', 'COM_EVENTS_EVENT_TABLE_ATTENDEES_STATUS', 'status', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="15">
							<?php //echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php 
						foreach ($this->users as $u => $user) :
						$user->max_ordering = 0;
						$ordering	= ($listOrder == 'id');
						
						if($prepay < 2 || $user->status == 4) :
					?>
						<tr class="row<?php echo $u % 2; ?>">
							<td class="left">
								<?php 
									if($user->status == 4)
									{ /******* Needs Language ********/
										if($prepay == 2)
										{
											echo JText::_('COM_EVENTS_EVENT_ATTENDEES_USER', true) . ' ' . ($u + 1) . ':'; 
										}
										else
										{
											echo JText::_('COM_EVENTS_EVENT_ATTENDEES_PREPAID_USER', true) . ' ' . ($u + 1) . ':'; 
										}
									} 
									else if($this->event->params->bump == 0 && $this->event->params->bump != null)
									{
										if($this->event->players_prepaid > $this->event->players_prepay)
										{
											$prepay = (int) $this->event->players_prepaid;
											$gap = 0;
										}
										else
										{
											$prepay = $this->event->players_prepay;
											$gap = $this->event->players_prepay - $this->event->players_prepaid;
											
										}
										
										if($prepay < 2)
										{
											if($u + $gap + 1 <= ($this->event->players_max))
											{
												
												echo JText::_('COM_EVENTS_EVENT_ATTENDEES_USER', true) . ' ' . ((int) ($u + 1) + $gap) . ':';
											}									
											else
											{
												echo JText::_('COM_EVENTS_EVENT_ATTENDEES_WAITING_USER', true) . ' ' . ($u + $gap + 1 - ($this->event->players_max)) . ':';
											}
										}
									}
									else
									{
										if($prepay < 2)
										{
											if($u <= $this->event->players_max)
											{
												
												echo JText::_('COM_EVENTS_EVENT_ATTENDEES_USER', true) . ' ' . ((int) ($u + 1)) . ':';
											}									
											else
											{
												echo JText::_('COM_EVENTS_EVENT_ATTENDEES_WAITING_USER', true) . ' ' . ($u - $this->event->players_max) . ':';
											}
										}
									}	
								?>
							</td>
							<td class="left">
								<?php echo $this->escape($user->username); ?>
							</td>
							<td class="center">
								<?php /*echo (int) $this->escape($user->status); */
								switch((int) $user->status)
								{
									case 1:
										echo JText::_('COM_EVENTS_EVENT_ATTENDEES_UNCONFIRMED', true);
										break;
									case 2: 
										echo JText::_('COM_EVENTS_EVENT_ATTENDEES_CONFIRMED', true);
										break;
									case 3: 
										echo JText::_('COM_EVENTS_EVENT_ATTENDEES_PAID', true);
										break;
									case 4:
										echo JText::_('COM_EVENTS_EVENT_ATTENDEES_PREPAID', true);
										break;
								}
								?>
							</td>
						</tr>
					<?php endif; endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</form>