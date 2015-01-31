<div id="details">	
	<h3><?php echo JText::_('COM_EVENTS_COMPETITION_SUBHEADING_ENTRANTS', true) ?></h3>
	<table class="list table table-striped">
		<thead>
			<tr>
				<th width="15%">
					<?php echo JHTML::_('grid.sort', 'COM_EVENTS_COMPETITION_TABLE_ENTRANTS_ORDER', 'id', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_EVENTS_COMPETITION_TABLE_ENTRANTS_ATTENDEE', 'p.username', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_EVENTS_COMPETITION_TABLE_ENTRANTS_STATUS', 'status', $listDirn, $listOrder); ?>
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
			<?php foreach ($this->users as $u => $user) :
				$user->max_ordering = 0;
				$ordering	= ($listOrder == 'id');
			?>
			<tr class="row<?php echo $p % 2; ?>">
				<td class="left">
					<?php 
						if($user->status == 4)
						{ /******* Needs Language ********/
							echo 'Pre-Paid User ' . ($p + 1) . ':'; 
						} 
						else if(($p + $this->event->players_prepay) <= $this->event->players_max)
						{
							echo 'User ' . ((int) ($p + 1) + $this->event->players_prepay) . ':';
						}
						else
						{
							echo 'Waiting ' . ($p - ($this->event->players_max - $this->event->players_prepay)) . ':';
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
			<?php endforeach; ?>
		</tbody>
	</table>
</div>