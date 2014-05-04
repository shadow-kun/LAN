<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */

	$fieldSets = $this->form->getFieldsets('details');
	foreach ($fieldSets as $name => $fieldSet) :
		echo JHtml::_('sliders.panel',JText::_($fieldSet->label), $name.'-details');
	if (isset($fieldSet->description) && trim($fieldSet->description)) :
		echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
	endif; ?>
	<fieldset class="form-vertical ">
		<div class="control-group ">
			<div class="control-label"><?php echo $this->form->getLabel('event_start_time'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('event_start_time'); ?></div>
		</div>
		
		<div class="control-group ">
			<div class="control-label"><?php echo $this->form->getLabel('event_end_time'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('event_end_time'); ?></div>
		</div>
		
		<?php foreach ($this->form->getFieldset($name) as $field) : ?>
			<div class="control-group ">
				<div class="control-label"><?php echo $field->label; ?></div>
				<div class="controls"><?php echo $field->input; ?></div>
			</div>
		<?php endforeach; ?>
	</fieldset>
<?php endforeach; ?>