<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */

	//JHtml::addIncludePath(JPATH_COMPONENT.'helpers/html');
	JHtml::stylesheet('com_events/admin.css', null, true);
	JHtml::_('behavior.tooltip');
	JHtml::_('behavior.formvalidation');
	JHtml::_('behavior.keepalive');
	
	
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
	
?>
<script type="text/javascript">
	// Attach a behaviour to the submit button to check validation.
	Joomla.submitbutton = function(task)
	{
		var form = document.id('player-form');
		if (task == 'player.cancel' || document.formvalidator.isValid(form)) {
			Joomla.submitform(task, form);
		}
		else {
			<?php JText::script('COM_EVENTS_ERROR_N_INVALID_FIELDS'); ?>
			// Count the fields that are invalid.
			var elements = form.getElements('fieldset').concat(Array.from(form.elements));
			var invalid = 0;

			for (var i = 0; i < elements.length; i++) {
				if (document.formvalidator.validate(elements[i]) == false) {
					valid = false;
					invalid++;
				}
			}

			alert(Joomla.JText._('COM_EVENTS_ERROR_N_INVALID_FIELDS').replace('%d', invalid));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=player&layout=edit&id='.(int) $this->item->id); ?>"
	method="post" name="adminForm" id="player-form" >
	
	
	<div class="form-horizontal"> <!--class="width-60 fltlft"-->
		<div class="row-fluid">
			<div class="span3 form-vertical">
				<div class="control-group ">
					<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
				</div>
				<div class="control-group ">
					<div class="control-label"><?php echo $this->form->getLabel('event'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('event'); ?></div>
				</div>
				<div class="control-group ">
					<div class="control-label"><?php echo $this->form->getLabel('user'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('user'); ?></div>
				</div>
				<div class="control-group ">
					<div class="control-label"><?php echo $this->form->getLabel('note'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('note'); ?></div>
				</div>
				<div class="control-group ">
					<div class="control-label"><?php echo $this->form->getLabel('status'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('status'); ?></div>
				</div>
			</div>
		</div>
	</div>
	
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>