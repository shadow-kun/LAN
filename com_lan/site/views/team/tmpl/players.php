<?php defined( '_JEXEC' ) or die( 'Restricted access' );
   /**
	* @version 		$Id$
	* @package		LAN
	* @subpackage	com_lan
	* @copyright	Copyright 2014 Daniel Johnson. All Rights Reserved.
	* @license		GNU General Public License version 2 or later.
	*/

	JHtml::_('behavior.tooltip');
	
	$user		= JFactory::getUser();
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
	
	$app = JFactory::getApplication();
	$pathway = $app->getPathway();
	$pathway->addItem(JText::_('COM_LAN_EVENTS_PLAYERS_TITLE', true), JRoute::_('index.php?option=com_lan&view=event&layout=players&id=' . $this->item->id));
?>	
<form action="<?php /*echo JRoute::_('index.php?option=com_lan&view=event&id='.(int) $this->item->id); */?>"
	method="post" name="adminForm" id="event-register-form" class="form-validate">
	
	<h2><a href="<?php echo JRoute::_('index.php?option=com_lan&view=event&id=' . $this->item->id); ?>"><?php echo $this->escape($this->item->title); ?></a> <strong> - </strong> 
		<a href="<?php echo JRoute::_('index.php?option=com_lan&view=event&layout=register&id=' . $this->item->id); ?>"><?php echo JText::_('COM_LAN_EVENTS_PLAYERS_TITLE', true) ?></a></h2>
		
	<div class="row-fluid">
		<div class="span8">
			<!-- Player Listing To Be Inserted Here -->
			<h3><?php echo JText::_('COM_LAN_EVENT_SUBHEADING_PLAYERS_LIST', true) ?></h3>
			<table class="list table table-striped">
				<thead>
					<tr>
						<th width="1%">
							<?php echo JHTML::_('grid.sort', 'COM_LAN_EVENT_TABLE_PLAYERS_ORDER', 'id', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo JHTML::_('grid.sort', 'COM_LAN_EVENT_TABLE_PLAYERS_PLAYER', 'p.username', $listDirn, $listOrder); ?>
						</th>
						<th width="10%">
							<?php echo JHTML::_('grid.sort', 'COM_LAN_EVENT_TABLE_PLAYERS_STATUS', 'status', $listDirn, $listOrder); ?>
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
					<?php foreach ($this->players as $p => $player) :
						$player->max_ordering = 0;
						$ordering	= ($listOrder == 'id');
					?>
					<tr class="row<?php echo $p % 2; ?>">
						<td class="left">
							<?php echo (int) $p + 1; ?>
						</td>
						<td class="left">
							<?php echo $this->escape($player->username); ?>
						</td>
						<td class="center">
							<?php /*echo (int) $this->escape($player->status); */
							switch((int) $player->status)
							{
								case 1:
									echo JText::_('COM_LAN_EVENT_PLAYERS_UNCONFIRMED', true);
									break;
								case 2: 
									echo JText::_('COM_LAN_EVENT_PLAYERS_CONFIRMED', true);
									break;
								case 3: 
									echo JText::_('COM_LAN_EVENT_PLAYERS_PREPAID', true);
									break;
								case 4:
									echo JText::_('COM_LAN_EVENT_PLAYERS_PAID', true);
									break;
							}
							?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</form>