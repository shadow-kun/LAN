<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */

	echo JHtml::_('sliders.panel',JText::_('COM_EVENT_METADATA_FIELDSET_LABEL'), 'metadata-options'); 
?>
<fieldset class="panelform">
	<ul class="adminformlist">
		<li><?php echo $this->form->getLabel('metadesc'); ?>
		<?php echo $this->form->getInput('metadesc'); ?></li>

		<li><?php echo $this->form->getLabel('metakey'); ?>
		<?php echo $this->form->getInput('metakey'); ?></li>

		<?php if ($this->item->created_time) : ?>
			<li><?php echo $this->form->getLabel('created_time'); ?>
			<?php echo $this->form->getInput('created_time'); ?></li>
		<?php endif; ?>

		<?php if (intval($this->item->modified_time)) : ?>
			<li><?php echo $this->form->getLabel('modified_time'); ?>
			<?php echo $this->form->getInput('modified_time'); ?></li>
		<?php endif; ?>
	</ul>
</fieldset>