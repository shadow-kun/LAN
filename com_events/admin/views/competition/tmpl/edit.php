<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Events Party!
	 * @subpackage	com_events
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */

	//JHtml::addIncludePath(JPATH_COMPONENT.'helpers/html');
	JHtml::stylesheet('com_lan/admin.css', null, true);
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
		var form = document.id('competition-form');
		if (task == 'competition.cancel' || document.formvalidator.isValid(form)) {
			<?php echo $this->form->getField('body')->save(); ?>
			Joomla.submitform(task, form);
		}
		else {
			<?php JText::script('COM_LAN_ERROR_N_INVALID_FIELDS'); ?>
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

<form action="<?php echo JRoute::_('index.php?option=com_events&view=competition&layout=edit&id='.(int) $this->item->id); ?>"
	method="post" name="adminForm" id="competition-form" class="form-validate">
	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
	
	<div class="form-horizontal"> <!--class="width-60 fltlft"-->
		
		<div class="row-fluid">
			<div class="span8">
				<fieldset class="adminform">
					<?php echo $this->form->getLabel('body'); ?>
					<?php echo $this->form->getInput('body'); ?>
				</fieldset>
			</div>
			<div class="span4 form-vertical">
				
				<div class="control-group ">
					<div class="control-label"><?php echo $this->form->getLabel('competition_start'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('competition_start'); ?></div>
				</div>
				
				<div class="control-group ">
					<div class="control-label"><?php echo $this->form->getLabel('competition_end'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('competition_end'); ?></div>
				</div>
				
				<div class="control-group ">
					<div class="control-label"><?php echo $this->form->getLabel('category_id'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('category_id'); ?></div>
				</div>
			</div>
			
			<div class="span4">
				<fieldset class="adminform">
					<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
				</fieldset>
			</div>
		</div>
	</div>
	<div class="form-vertical">
		<div class="span8">
			<fieldset class="adminform">
				<!-- Player Listing To Be Inserted Here -->
				<?php if($this->item->params['competition_team'] == 1)
				{
					echo $this->loadTemplate('teams'); 
				} else {
					echo $this->loadTemplate('players'); 
				} ?>
			</fieldset>
		</div>
		
		<div class="span4">
			<fieldset class="adminform">
			<?php echo JHtml::_('sliders.start','event-sliders-'.$this->item->id, array('useCookie' => 1)); ?>

			<?php echo $this->loadTemplate('params'); ?>
			</fieldset>
		</div>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>