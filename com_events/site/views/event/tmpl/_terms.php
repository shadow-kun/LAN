<div>
	<h3 class="center"><?php echo JText::_('COM_EVENTS_EVENT_REGISTRATION_HEADING_TERMS', true) ?></h3>
	<?php if($this->event->params->terms_global == 1)
		{
			echo $this->event->terms; 
		}
		else
		{
			echo $this->params->get('terms');
		}
	?>
	</br >
</div>