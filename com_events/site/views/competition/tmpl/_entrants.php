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
				<th width="15%">
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
				<?php if((!(isset($this->competition->users_max)) && ($u <= $this->competition->users_max)) && (strtotime($this->competition->competition_start) <= time()))
				{ ?>
									
					<tr class="row<?php echo $u % 2; ?>">
						<td class="left">
							<?php 
								if(!(isset($this->competition->users_max)) && ($u <= $this->competition->users_max))
								{
									echo 'User ' . (int) ($u + 1) . ':';
								}
								else
								{
									echo 'Waiting ' . (int) ($u - ($this->competition->users_max)) . ':';
								}
									
							?>
						</td>
						<td class="left">
							<?php echo $this->escape($user->username); ?>
						</td>
						<td class="center">
							<?php  
							switch((int) $user->status)
							{
								case 0:
									if(strtotime($this->competition->competition_start) > time())
									{
										if(!(isset($this->competition->users_max)) && ($u <= $this->competition->users_max))
										{
											echo JText::_('COM_EVENTS_COMPETITION_USER_ENTERED', true);
										}
										else
										{
											echo JText::_('COM_EVENTS_COMPETITION_USER_WAITING', true);
										}							
									}
									else
									{
										if(!(isset($this->competition->users_max)) || ($u <= $this->competition->users_max))
										{
											echo JText::_('COM_EVENTS_COMPETITION_USER_COMPETING', true);
										}
									}
									break;
								case -1: 
									echo JText::_('COM_EVENTS_COMPETITION_USER_ELIMINATED', true);
									break;
								case -2:
									echo JText::_('COM_EVENTS_COMPETITION_USER_FORFEITED', true);
									break;
							}
							?>
						</td>
					</tr>
				<?php } ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>