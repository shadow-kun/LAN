	<?php if(($this->currentUser->status <= 2)) : ?>	
		<div class="row-fluid">
			
			<?php if($this->event->params->prepay !== '') 
			// Gets global setting if not explicitly set.
			{
				$prepay = intval($this->event->params->prepay);
			} 
			else
			{
				$prepay = intval($this->params->get('prepay'));
			}
			
			if($prepay == 2)
			{			
				echo '<p>' . JText::_('COM_EVENTS_EVENT_PREPAY_MSG_MANDATORY') . '</p>';
			}
			elseif ($prepay == 1)
			{
				echo '<p>' . JText::_('COM_EVENTS_EVENT_PREPAY_MSG_OPTIONAL') . '</p>';
			}
			else 
			{
				echo '<p>' . JText::_('COM_EVENTS_EVENT_PREPAY_MSG_DISABLED') . '</p>';
			}?>
			</br >
		</div>
		<?php if($prepay > 0) : ?>
			<div class="row-fluid">
				<table class="list table table-striped">
					<thead>
						<tr>
							<th width="20%">
								<?php echo JText::_('COM_EVENTS_EVENT_PREPAY_TABLE_PAYMENT_TYPE_TITLE'); ?>
							</th>
							<th>
								<?php echo JText::_('COM_EVENTS_EVENT_PREPAY_TABLE_PAYMENT_DESC_TITLE'); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php echo $this->_prepay->render(); ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	<?php else :
		echo '<div class="row-fluid"><p>' . JText::_('COM_EVENTS_EVENT_PREPAY_MSG_PAID') . '</p></div>';
	endif; ?>