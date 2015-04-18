<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	
	echo "<h3>" . JText::_("COM_EVENTS_EVENT_PAYPAL_FIELDSET_LABEL") . "</h3>";
	$fieldSets = $this->form->getFieldsets('params');
	foreach ($fieldSets as $name => $fieldSet) :
		if($name == "paypal") :
			/*echo JHtml::_('sliders.panel',JText::_($fieldSet->label), $name.'-params');*/
			if (isset($fieldSet->description) && trim($fieldSet->description)) :
				echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
			endif; ?>
			<fieldset class="panelform">
				<ul class="adminformlist">
				<?php foreach ($this->form->getFieldset($name) as $field) : ?>
					<li><?php echo $field->label; ?>
					<?php echo $field->input; ?></li>
				<?php endforeach; ?>
				</ul>
			</fieldset>
		<?php endif; ?>
	<?php endforeach; ?>