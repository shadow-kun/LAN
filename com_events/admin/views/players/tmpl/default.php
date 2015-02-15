<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		Events Party!
	* @subpackage	com_events
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/

	JHtml::_('behavior.tooltip');
	
	$user		= JFactory::getUser();
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_events&view=players');?>" method="post" name="adminForm" id="adminForm">
	<div id="j-main-container">
<?php
		// Search tools bar
		//echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
?>
	<fieldset class="filter clearfix">
		
		<div class="btn-toolbar">
			<div class="btn-group pull-left">
				<label for="filter_search">
					<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
				</label>
			</div>
			<div class="btn-group pull-left">
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" size="30" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>" data-placement="bottom">
					<span class="icon-search"></span><?php echo '&#160;' . JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" data-placement="bottom" onclick="document.id('filter_search').value='';this.form.submit();">
					<span class="icon-remove"></span><?php echo '&#160;' . JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			</div>
			<div class="btn-group pull-left"> <?php /*filter-select fltrt*/ ?>
				<select name="filter_event" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_EVENTS_PLAYERS_SELECT_EVENT');?></option>
					<?php echo JHtml::_('select.options', JHtml::_('category.options', '${OPTION_LOWER}'),
					'value', 'text', $this->state->get('filter.event'));?>
				</select>
			</div>
			<div class="clearfix"></div>
		</div>
		
	</fieldset>
	<div class="clr"> </div>
	
	<table class="adminlist table table-striped"">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(this)" />
				</th>
				<th class="center" >
					<?php echo JHTML::_('grid.sort', 'COM_EVENTS_FIELD_PLAYERS_NAME_TITLE', 'event', $listDirn, $listOrder); ?>
				</th>
				<th class="center" width="20%">
					<?php echo JHTML::_('grid.sort', 'COM_EVENTS_FIELD_PLAYERS_EVENT_TITLE', 'user', $listDirn, $listOrder); ?>
				</th>
				<th class="center" width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_EVENTS_FIELD_PLAYERS_STATUS_TITLE', 'a.status', $listDirn, $listOrder); ?>
				</th>
				<th class="center" width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_EVENTS_FIELD_PLAYERS_CHECKED_IN_TITLE', 'a.checked_in', $listDirn, $listOrder); ?>
				</th>
				<th class="center" width="1%">
					<?php echo JHTML::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($this->items as $i => $item) :
				$item->max_ordering = 0;
				$ordering	= ($listOrder == 'a.ordering');
				$canCreate	= $user->authorise('core.create',		'com_events.category.' . $item->event);
				$canEdit	= $user->authorise('core.edit',			'com_events.event.' . $item->id);
				$canCheckin = $user->authorise('core.manage',		'com_checkin') || $item->checked_out == $user->get('id') || $item-> checked_out == 0;
				$canChange	= $user->authorise('core.edit.state',	'com_events.event.' . $item->id) && $canCheckin;
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<?php if ($canCreate || $canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_events&task=player.edit&id=' . $item->id); ?>">
						<?php echo $this->escape($item->user); ?></a>
					<?php else : ?>
						<?php echo $this->escape($item->user); ?>
					<?php endif; ?>
					<!--<p class="smallsub">
						<?php if (empty($item->note)) : ?>
							<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
						<?php else : ?>
							<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias), $this->escape($item->note)); ?>

						<?php endif; ?>
					</p>-->
				</td>
				<!--<td class="center">
					<?php echo $this->escape($item->user); ?>
				</td>-->
				<td class="center">
					<?php echo $this->escape($item->event); ?>
				</td>
				<td class="center">
					<?php switch((int) $item->status)
					{
						case 1:
							echo JText::_('COM_EVENTS_EVENT_PLAYERS_UNCONFIRMED', true);
							break;
						case 2: 
							echo JText::_('COM_EVENTS_EVENT_PLAYERS_CONFIRMED', true);
							break;
						case 3: 
							echo JText::_('COM_EVENTS_EVENT_PLAYERS_PREPAID', true);
							break;
						case 4:
							echo JText::_('COM_EVENTS_EVENT_PLAYERS_PAID', true);
							break;
					}
					?>
				</td>
				<td class="center">
					<?php 
					if(!($item->checked_in)) :
						$this->escape($item->checked_in);
					else : 
						echo JText::_('COM_EVENTS_PLAYERS_NOT_CHECKED_IN_TITLE', true);
					endif;?>
				</td>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
 
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
							