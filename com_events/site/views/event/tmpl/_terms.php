<div>
	<?php if($this->event->terms_global == 1)
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