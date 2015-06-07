<?php defined( '_JEXEC' ) or die( 'Restricted access' );
	
	/**
	 * @package		LAN
	 * @subpackage	com_lan
	 * @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	 * @license		GNU General Public License version 2 or later.
	 */
	jimport('joomla.application.component.helper');
	
	?>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=store&id='.(int) $this->store->id); ?>"
	method="post" name="adminForm" id="store-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_events&view=store&id=' . (int) $this->store->id); ?>"><?php echo $this->escape($this->store->title); ?></a></h2>
					
	<div class="form-horizontal">
		<div id="details">	
			<div class="row-fluid">
				<div >
					<?php $tokens = explode('<hr id="system-readmore" />', $this->store->body);
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
				
				<?php foreach($this->groups as $g => $group) : ?>
				<div class="group" >
					<h3><?php echo $group->title; ?></h3>
					<table class="list table table-striped" style="width: 100%">
						<?php foreach($group->items as $i => $item) : ?>
						<tr class="row<?php echo $i % 2; ?>">
							<td class="left" >
								<?php echo JHTML::Tooltip($item->title, JText::_('COM_EVENTS_SHOP_STORE_DESCRIPTION_LABEL'), '', $item->body, '', $item->title); ?>
							</td>
							<td class="right" width="10%">
								<?php echo JText::_('COM_EVENTS_SYMBOL_CURRENCY') . ' ' . $item->amount; ?>
							</td>
							<td width="10%">
								<select id="<?php echo 'item' . $group->id . '-' . $item->id; ?>" name="<?php echo 'item' . $group->id . '-' . $item->id; ?>">
									<option value=0>0</option>
									<option value=1>1</option>
									<option value=2>2</option>
									<option value=3>3</option>
									<option value=4>4</option>
									<option value=5>5</option>
									<option value=6>6</option>
									<option value=7>7</option>
									<option value=8>8</option>
									<option value=9>9</option>
									<option value=10>10</option>
								</select>
							</td>
						</tr>
						<?php endforeach; ?>	
					</table>
				</div>
			</div>
			<div id=buttons">
					<p align="right"><button class="btn " ><?php echo JText::_('COM_EVENTS_SHOP_STORE_BUTTON_BACK', true); ?></button>
						<a class="btn btn-primary" onclick="orderStoreNew()" href="javascript:void(0);" ><?php echo JText::_('COM_EVENTS_SHOP_STORE_BUTTON_ORDER', true); ?></a></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
	
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" id="storeid" name="store" value="<?php echo $this->store->id; ?>" />
	<input id="task" type="hidden" name="task" value="" />
</form>