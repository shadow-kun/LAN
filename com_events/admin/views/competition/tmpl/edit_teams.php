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
<h3><?php echo JText::_('COM_EVENTS_COMPETITION_SUBHEADING_TEAMS_LIST', true) ?></h3>
<div class="control-group ">
	<div class="control-label "><?php echo $this->form->getLabel('add_user'); ?></div>
	<div class="controls" ><?php echo $this->form->getInput('add_user'); ?></div>
</div>
<table class="adminlist table table-striped"">
	<thead>
		<tr>
			<th width="1%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(this)" />
			</th>
			<th>
				<?php echo JHTML::_('grid.sort', 'COM_EVENTS_COMPETITION_TABLE_TEAMS_NAME', 'name', $listDirn, $listOrder); ?>
			</th>
			<th width="10%" class="center">
				<?php echo JHTML::_('grid.sort', 'COM_EVENTS_COMPETITION_TABLE_TEAMS_STATUS', 'status', $listDirn, $listOrder); ?>
			</th>
			<th width="1%">
				<?php echo JHTML::_('grid.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="15">
				<?php //echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php foreach ($this->teams as $t => $team) :
			$team->max_ordering = 0;
			$ordering	= ($listOrder == 'id');
		?>
		<tr class="row<?php echo $p % 2; ?>">
			<td class="center">
				<?php echo JHtml::_('grid.id', $t, $team->id); ?>
			</td>
			<td class="left">
				<?php echo $this->escape($team->name); ?>
			</td>
			<td class="center">
				<?php if(isset(json_decode($team->params)->status)) :
					echo $this->escape(json_decode($team->params)->status); 
				endif; ?>
			</td>
			<td class="center">
				<?php echo (int) $t + 1; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>