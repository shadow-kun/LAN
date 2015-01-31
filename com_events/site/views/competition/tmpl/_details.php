<div id="details">	
	<div class="row-fluid">
		<div class="span8">
			<?php $tokens = explode('<hr id="system-readmore" />',$this->competition->body);
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