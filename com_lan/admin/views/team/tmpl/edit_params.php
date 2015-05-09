<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */

	$fieldSets = $this->form->getFieldsets('params');
	foreach ($fieldSets as $name => $fieldSet) :
		echo JHtml::_('sliders.panel',JText::_($fieldSet->label), $name.'-params');
		if (isset($fieldSet->description) && trim($fieldSet->description)) :
			echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
		endif; ?>
		<fieldset class="panelform">
			<div class="adminformlist">
			<?php foreach ($this->form->getFieldset($name) as $field) : ?>
				<p>
					<?php echo $field->label; ?>
					<?php echo $field->input; ?>
					</br>
				</p>
			<?php endforeach; ?>
			</div>
		</fieldset>
<?php endforeach; ?>
