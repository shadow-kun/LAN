<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	echo "<h3>" . JText::_("COM_EVENTS_EVENT_REGISTRATION_FIELDSET_LABEL") . "</h3>";
	$fieldSets = $this->form->getFieldsets('params');
	foreach ($fieldSets as $name => $fieldSet) :
		if($name == "registration") : 
			if (isset($fieldSet->description) && trim($fieldSet->description)) :
				echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
			endif; 	
			/*<div class="control-group ">
				<div class="control-label"><?php echo $this->form->getLabel('registration_open_time'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('registration_open_time'); ?></div>
				
			</div> */?>
			
			
			<fieldset class="panelform">
				<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset($name) as $field) : 
					$name = substr($field->fieldname, 0, strrpos($field->fieldname, '_'));
					if($name == 'registration_open')
					{
						$hour = $this->item->registration_open_hour;
						$minute = $this->item->registration_open_minute;
					}
					else if($name == 'registration_confirmation')
					{
						$hour = $this->item->registration_confirmation_hour;
						$minute = $this->item->registration_confirmation_minute;
					}else if($name == 'registration_close')
					{
						$hour = $this->item->registration_close_hour;
						$minute = $this->item->registration_close_minute;
					} ?>
						
					<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
					<div class="control-group ">
					<li><select name="jform[<?php echo $name; ?>_hour]" class="span2">
						<?php for($h = 0; $h < 24; $h++)
						{
							echo '<option ';
							if((int) $h == $hour)
							{
								echo 'selected ';
							}
							
							if($h < 10)
							{
								 echo 'value=0' . $h . '>0' . $h . '</option>';
							}
							else
							{
								 echo 'value=' . $h . '>' . $h . '</option>';
							}
						}
					?>
					</select> : <select name="jform[<?php echo $name; ?>_minute]" class="span2">
						<?php for($h = 0; $h < 60; $h++)
						{
							echo '<option ';
							if((int) $h == $minute)
							{
								echo 'selected ';
							}
							
							if($h < 10)
							{
								echo 'value=0' . $h . '>0' . $h . '</option>';
							}
							else
							{
								echo 'value=' . $h . '>' . $h . '</option>';
							}
						}
					?>
					</select></li>
				<?php endforeach; ?>
				</ul>
			</fieldset>			
			
		<?php endif; ?>
	<?php endforeach; ?>