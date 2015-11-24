<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	/**
	 * @package		Hello
	 * @subpackage	com_hello
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
		var form = document.id('event-form');
		if (task == 'event.cancel' || document.formvalidator.isValid(form)) {
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

			alert(Joomla.JText._('COM_LAN_ERROR_N_INVALID_FIELDS').replace('%d', invalid));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_events&layout=edit&id='.(int) $this->item->id); ?>"
	method="post" name="adminForm" id="event-form" class="form-validate">
	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
	
	<div class="form-horizontal"> <!--class="width-60 fltlft"-->
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_EVENTS_EVENT_TAB_GENERAL', true)); ?>
		
		<div class="row-fluid">
			<div class="span8">
				<fieldset class="adminform">
					<?php echo $this->form->getLabel('body'); ?>
					<?php echo $this->form->getInput('body'); ?>
				</fieldset>
			</div>
			<div class="span3 form-vertical">
				
				<div class="control-group ">
					<div class="control-label"><?php echo $this->form->getLabel('event_start_time'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('event_start_time'); ?></div>
				</div>
				
				<div class="control-group ">
					<div class="control-label"></div>
					<div class="controls">
						<select name="jform[event_start_hour]" class="span2">
							<?php for($h = 0; $h < 24; $h++)
							{
								echo '<option ';
								if((int) $h == $this->item->event_start_hour)
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
						</select> : <select name="jform[event_start_minute]" class="span2">
							<?php for($h = 0; $h < 60; $h++)
							{
								echo '<option ';
								if((int) $h == $this->item->event_start_minute)
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
						</select>
					</div>
				</div>
				
				<div class="control-group ">
					<div class="control-label"><?php echo $this->form->getLabel('event_end_time'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('event_end_time'); ?></div>
					
				</div>
				
				<div class="control-group ">
					<div class="control-label"></div>
					<div class="controls">
						<select name="jform[event_end_hour]" class="span2">
							<?php for($h = 0; $h < 24; $h++)
							{
								echo '<option ';
								if((int) $h == $this->item->event_end_hour)
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
						</select> : <select name="jform[event_end_minute]" class="span2">
							<?php for($h = 0; $h < 60; $h++)
							{
								echo '<option ';
								if((int) $h == $this->item->event_end_minute)
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
						</select>
					</div>
				</div>
				
				<?php echo $this->loadTemplate('location'); ?>
				
				<p><?php echo $this->form->getLabel('category_id'); ?>
				<?php echo $this->form->getInput('category_id'); ?></p>
			</div>
			
		</div>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'additions', JText::_('COM_EVENTS_EVENT_TAB_TERMS', true)); ?>
		
		<div class="row-fluid">
			<div class="span6">
				<fieldset class="adminform">
					<?php echo $this->form->getLabel('terms'); ?>
					<?php echo $this->form->getInput('terms'); ?>
				</fieldset>
			</div>
			<div class="span3">
				<?php $fieldSets = $this->form->getFieldsets('params');
					foreach ($fieldSets as $name => $fieldSet) :
					if($name == "terms") : 
						foreach ($this->form->getFieldset($name) as $field) : ?>
							<div class="control-group ">
								<div class="control-label"><?php echo $field->label; ?></div>
								<div class="controls"><?php echo $field->input; ?></div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'players', JText::_('COM_EVENTS_EVENT_TAB_PLAYERS', true)); ?>
		
		<div class="row-fluid">
			<div class="span8">
				<!-- Player Listing To Be Inserted Here -->
				<h3><?php echo JText::_('COM_EVENTS_EVENT_SUBHEADING_PLAYERS_LIST', true) ?></h3>
				<div class="control-group ">
					<div class="control-label"><?php echo $this->form->getLabel('add_user'); ?></div>
					<div class="controls" ><?php echo $this->form->getInput('add_user'); ?> <?php echo $this->form->getInput('add_user_status'); ?></div>
				</div>
				<?php echo $this->loadTemplate('players'); ?>
			</div>
			
			<div class="span3">
				<fieldset class="adminform">
					<h3><?php echo JText::_('COM_EVENTS_EVENT_SUBHEADING_PLAYERS_SUMMARY', true) ?></h3>
					
					<p><?php echo $this->form->getLabel('players_current'); ?>
					<?php echo $this->form->getInput('players_current'); ?>
										
					<p><?php echo $this->form->getLabel('players_confirmed'); ?>
					<?php echo $this->form->getInput('players_confirmed'); ?></p>
					
					<p><?php echo $this->form->getLabel('players_prepaid'); ?>
					<?php echo $this->form->getInput('players_prepaid'); ?></p>
					
					<p><?php echo $this->form->getLabel('players_max'); ?>
					<?php echo $this->form->getInput('players_max'); ?></p>
					
					<p><?php echo $this->form->getLabel('players_prepay'); ?>
					<?php echo $this->form->getInput('players_prepay'); ?></p>
					
				</fieldset>
			</div>
		</div>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'payments', JText::_('COM_EVENTS_EVENT_TAB_PAYMENTS', true)); ?>
		
		<?php echo $this->loadTemplate('payments'); ?>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'settings', JText::_('COM_EVENTS_EVENT_TAB_SETTINGS', true)); ?>
		<div class="row-fluid">
			<div class="span2">
				<h3><?php echo JText::_('COM_EVENTS_EVENT_SUBHEADING_SETTINGS_PUBLISHING_TITLE', true) ?></h3>
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
			<div class="span2">
				<?php /*echo JHtml::_('sliders.start','event-sliders-'.$this->item->id, array('useCookie' => 1));  */?>

				<?php echo $this->loadTemplate('params'); ?>
			</div>
			<div class="span3">
				<?php echo $this->loadTemplate('registration'); ?>
			</div> 
			<div class="span2">
				<?php echo $this->loadTemplate('paypal'); ?>
			</div> 
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>