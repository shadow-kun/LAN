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
	
	$app  	     = JFactory::getApplication();
	
	$listOrder	 = $this->escape($this->state->get('list.ordering'));
	$listDirn	 = $this->escape($this->state->get('list.direction'));
	$orderStatus = $app->getUserState('com_events.store.orders.status');
?>
<script type="text/javascript">
	// Attach a behaviour to the submit button to check validation.
	Joomla.submitbutton = function(task)
	{
		var form = document.id('store-form');
		if (task == 'store.cancel' || document.formvalidator.isValid(form)) {
			<?php echo $this->form->getField('body')->save(); ?>
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

<form action="<?php echo JRoute::_('index.php?option=com_events&view=store&layout=edit&id='.(int) $this->store->id); ?>"
	method="post" name="adminForm" id="store-form" class="form-validate">
	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
	
	
		
		
	<div class="form-horizontal"> <!--class="width-60 fltlft"-->
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_EVENTS_SHOP_STORE_TAB_GENERAL', true)); ?>
		
		<div class="row-fluid">
			<div class="span8">
				<fieldset class="adminform">
					<?php echo $this->form->getLabel('body'); ?>
					<?php echo $this->form->getInput('body'); ?>
				</fieldset>
			</div>
			
			<div class="span4 form-vertical">		
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
		
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'items', JText::_('COM_EVENTS_SHOP_STORE_TAB_ITEMS', true)); ?>
		<div class="span8">
			<?php foreach($this->groups as $group => $g)
			{ ?>
				<div>
					<h3><?php echo $g->title; ?> <button name="removeGroup" class="btn" value=<?php echo $g->id ?> onclick="Joomla.submitbutton('store.removegroup')"  ><?php echo JText::_('COM_EVENTS_SHOP_STORE_GROUP_REMOVE_LABEL'); ?></button></h3>
					<table class="adminlist table table-striped">
					<?php foreach($g->items as $item => $i)
					{ ?>
						<tr class="row<?php echo $item % 2; ?>">
							<td width="70%"><?php echo $i->title; ?></td>
							<td width="10%"><?php echo JText::_('COM_EVENTS_CURRENCY_SYMBOL') . $i->amount; ?></td>
							<td><button name="removeItem" class="btn" value="<?php echo $g->id . '-' . $i->id; ?>" onclick="Joomla.submitbutton('store.removeitem')"  ><?php echo JText::_('COM_EVENTS_SHOP_STORE_ITEM_REMOVE_LABEL'); ?></button></td>
						</tr>
					<?php } ?>
					</table>
				</div>
			<?php }	?>
		</div>
		
		<div class="span4">
			<?php // Controls ?> 
				
				<fieldset class="adminform">	
					<div class="control-group ">
						<?php echo $this->form->getLabel('add_group'); ?>
						<?php echo $this->form->getInput('add_group'); ?>
					</div>
					
					<div class="control-group ">
						<?php echo JText::_('COM_EVENTS_SHOP_STORE_ITEM_ADD_LABEL'); ?>
						
						<?php echo $this->form->getLabel('add_item'); ?>
						<?php echo $this->form->getInput('add_item'); ?>
						
						<?php echo $this->form->getLabel('add_item_group'); ?>
						<?php echo $this->form->getInput('add_item_group'); ?>
					</div>
				</fieldset>
				
			
			<fieldset class="adminform">
			<?php echo JHtml::_('sliders.start','event-sliders-'.$this->store->id, array('useCookie' => 1)); ?>
			</fieldset>
		</div>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'orders', JText::_('COM_EVENTS_SHOP_STORE_TAB_ORDERS', true)); ?>
		
		<?php echo $this->loadTemplate('orders'); ?>
		
		<div class="span4">
			<h3><?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDERS_SUMMARY_LABEL'); ?></h3>
			
			<table class="adminlist table table-striped">
				<tr>
					<th><?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDERS_SUMMARY_TABLE_ITEM_HEADING'); ?></th>
					<th><?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDERS_SUMMARY_TABLE_UNPAID_HEADING'); ?></th>
					<th><?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDERS_SUMMARY_TABLE_PAID_HEADING'); ?></th>
					<th><?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDERS_SUMMARY_TABLE_COLLECTED_HEADING'); ?></th>
					<th><?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDERS_SUMMARY_TABLE_SALES_HEADING'); ?></th>
				</tr>
				<?php foreach($this->ordersSummary as $i => $item)
				{ ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td><?php echo $item['title']; ?></td>
						<?php for((int) $j = 1; $j < 4; $j++) 
						{
							if(empty($item[$j]['quantity']))
							{
								$item[$j]['quantity'] = 0;
							}
							echo '<td width="5%">' . $item[$j]['quantity'] . '</td>';
						} ?>
						<td width="10%"><?php echo JText::_('COM_EVENTS_CURRENCY_SYMBOL') . number_format((float)($item[2]['amount'] + $item[3]['amount']), 2, '.', ''); ?></td>
							
					</tr>
				<?php } ?>
				</table>
			
			<h3><?php echo JText::_('COM_EVENTS_SHOP_STORE_ORDERS_SETTINGS_LABEL'); ?></h3>
			<?php /*$fieldSets = $this->form->getFieldsets('params');
			foreach ($fieldSets as $name => $fieldSet) :
				if($name == "order_filter") :
					/*echo JHtml::_('sliders.panel',JText::_($fieldSet->label), $name.'-params');*/
					/*if (isset($fieldSet->description) && trim($fieldSet->description)) :
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
			<?php endforeach;*/ ?>
			<p><?php echo JText::_('COM_EVENTS_SHOP_STORE_FIELD_ORDER_START_DATE_LABEL'); ?></p>
			<p><?php echo JHTML::calendar($app->getUserState('com_events.store.orders.start_date'),'orders_start_date', 'orders_start_date', '%Y-%m-%d',array('size'=>'8','maxlength'=>'10','class'=>' validate[\'required\']', 'onchange'=> 'Joomla.submitbutton(\'store.setStoreParameters\');',)); ?></p>
			<p><?php echo JText::_('COM_EVENTS_SHOP_STORE_FIELD_ORDER_END_DATE_LABEL'); ?></p>
			<p><?php echo JHTML::calendar($app->getUserState('com_events.store.orders.end_date'),'orders_end_date', 'orders_end_date', '%Y-%m-%d',array('size'=>'8','maxlength'=>'10','class'=>' validate[\'required\']', 'onchange'=> 'Joomla.submitbutton(\'store.setStoreParameters\');',)); ?></p>
			
			<p><?php echo JText::_('COM_EVENTS_SHOP_STORE_FIELD_ORDER_STATUS_LABEL'); ?></p>
			
			<p><select name="orders_status" onchange="Joomla.submitbutton('store.setStoreParameters')">
				<option value="">
					<?php echo JText::_('COM_EVENTS_SHOP_STORE_PARAMS_ORDER_FILTER_STATUS_OPTION_NONE', true); ?></option>
				<option value="1" 
					<?php if($orderStatus == 1) : 
						echo 'selected'; 
						endif; ?>
					><?php echo JText::_('COM_EVENTS_SHOP_STORE_PARAMS_ORDER_FILTER_STATUS_OPTION_UNPAID_ONLY', true); ?></option>
				<option value="2" 
					<?php if($order->status == 2) :
						echo 'selected'; 
						endif; ?>
					><?php echo JText::_('COM_EVENTS_SHOP_STORE_PARAMS_ORDER_FILTER_STATUS_OPTION_PAID_ONLY', true); ?></option>
				<option value="3" 
					<?php if($order->status == 3) :
						echo 'selected'; 
					endif; ?>
					><?php echo JText::_('COM_EVENTS_SHOP_STORE_PARAMS_ORDER_FILTER_STATUS_OPTION_COLLECTED_ONLY', true); ?></option>
				<option value="4" 
					<?php if($order->status == 4) :
						echo 'selected'; 
					endif; ?>
					><?php echo JText::_('COM_EVENTS_SHOP_STORE_PARAMS_ORDER_FILTER_STATUS_OPTION_PAID_COLLECTED', true); ?></option>
			</select></p>
			
		</div>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'analytics', JText::_('COM_EVENTS_SHOP_STORE_TAB_ANALYTICS', true)); ?>
		
		
		<?php echo $this->loadTemplate('payments'); ?>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'parameters', JText::_('COM_EVENTS_SHOP_STORE_TAB_PARAMETERS', true)); ?>
		
		
		<?php echo $this->loadTemplate('params'); ?>
		
		<?php echo JHtml::_('bootstrap.endTab'); ?>
	</div>

	<input type="hidden" name="task" value="store" />
	<input type="hidden" id="storeid" name="id" value="<?php echo $this->store->id; ?>"	/>
	<?php echo JHtml::_('form.token'); ?>
</form>