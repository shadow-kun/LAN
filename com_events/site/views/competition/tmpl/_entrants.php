<div id="details">	
	<h3><?php echo JText::_('COM_EVENTS_COMPETITION_SUBHEADING_ENTRANTS', true) ?></h3>
	<table class="list table table-striped">
		<thead>
			<tr>
				<th width="15%">
					<?php echo JHTML::_('grid.sort', 'COM_EVENTS_COMPETITION_TABLE_ENTRANTS_ORDER', 'id', $listDirn, $listOrder); ?>
				</th>
				<th>
					<?php 
						if((int) $this->competition->params->competition_team == 0)
						{
							echo JHTML::_('grid.sort', 'COM_EVENTS_COMPETITION_TABLE_ENTRANTS_ATTENDEE', 'p.username', $listDirn, $listOrder); 
						}
						else
						{
							echo JHTML::_('grid.sort', 'COM_EVENTS_COMPETITION_TABLE_ENTRANTS_TEAM', 't.name', $listDirn, $listOrder); 
						}
					?>
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
			<?php 
				if((int) $this->competition->params->competition_team == 0)
				{
					foreach ($this->users as $u => $user) :
					$user->max_ordering = 0;
					$ordering	= ($listOrder == 'id');
					?>
					<?php 
					if(!(isset($this->competition->params->competition_limit)) || ($u < $this->competition->params->competition_limit && strtotime($this->competition->competition_start) <= time()))
					{ ?>
										
						<tr class="row<?php echo $u % 2; ?>">
							<td class="left">
								<?php 
									if(!(isset($this->competition->params->competition_limit)) || ($u < $this->competition->params->competition_limit))
									{
										echo JText::_('COM_EVENTS_COMPETITION_USER_LABEL', true) . ' ' . (int) ($u + 1) . ':';
									}
									else
									{
										echo JText::_('COM_EVENTS_COMPETITION_WAITING_LABEL', true) . ' ' . (int) ($u + 1 - ($this->competition->params->competition_limit)) . ':';
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
											if(!(isset($this->competition->params->competition_limit)) || ($u < $this->competition->params->competition_limit))
											{
												echo JText::_('COM_EVENTS_COMPETITION_TABLE_STATUS_ENTERED', true);
											}
											else
											{
												echo JText::_('COM_EVENTS_COMPETITION_TABLE_STATUS_WAITING', true);
											}							
										}
										else
										{
											if(!(isset($this->competition->params->competition_limit)) || ($u < $this->competition->params->competition_limit))
											{
												echo JText::_('COM_EVENTS_COMPETITION_TABLE_STATUS_COMPETING', true);
											}
										}
										break;
									case -1: 
										echo JText::_('COM_EVENTS_COMPETITION_TABLE_STATUS_ELIMINATED', true);
										break;
									case -2:
										echo JText::_('COM_EVENTS_COMPETITION_TABLE_STATUS_FORFEITED', true);
										break;
								}
								?>
							</td>
						</tr>
					<?php 
					} 
				endforeach; 
			}
			else
			{
				foreach ($this->teams as $t => $team) :
					$team->max_ordering = 0;
					$ordering	= ($listOrder == 'id');
					?>
					
					<?php 
					if(!(isset($this->competition->params->competition_limit)) || ($t < $this->competition->params->competition_limit && strtotime($this->competition->competition_start) <= time()))
					{ ?>
										
						<tr class="row<?php echo $t % 2; ?>">
							<td class="left">
								<?php 
									if(!(isset($this->competition->params->competition_limit)) || ($t < $this->competition->params->competition_limit))
											{
										echo JText::_('COM_EVENTS_COMPETITION_TEAM_LABEL', true) . ' ' . (int) ($t + 1) . ':';
									}
									else
									{
										echo JText::_('COM_EVENTS_COMPETITION_WAITING_LABEL', true) . ' ' . (int) ($t + 1 - ($this->competition->params->competition_limit)) . ':';
									}
										
								?>
							</td>
							<td class="left">
								<?php echo $this->escape($team->name); ?>
							</td>
							<td class="center">
								<?php  
								switch((int) $team->status)
								{
									case 0:
										if(strtotime($this->competition->competition_start) > time())
										{
											if(!(isset($this->competition->params->competition_limit)) || ($t < $this->competition->params->competition_limit))
									{
												echo JText::_('COM_EVENTS_COMPETITION_TABLE_STATUS_ENTERED', true);
											}
											else
											{
												echo JText::_('COM_EVENTS_COMPETITION_TABLE_STATUS_WAITING', true);
											}							
										}
										else
										{
											if(!(isset($this->competition->params->competition_limit)) || ($t < $this->competition->params->competition_limit))
									{
												echo JText::_('COM_EVENTS_COMPETITION_TABLE_STATUS_COMPETING', true);
											}
										}
										break;
									case -1: 
										echo JText::_('COM_EVENTS_COMPETITION_TABLE_STATUS_ELIMINATED', true);
										break;
									case -2:
										echo JText::_('COM_EVENTS_COMPETITION_TABLE_STATUS_FORFEITED', true);
										break;
								}
								?>
							</td>
						</tr>
					<?php 
					} 
				endforeach; 
			}?>
		</tbody>
	</table>
</div>